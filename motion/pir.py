from ConfigParser import ConfigParser
from os.path import expanduser
import RPi.GPIO as GPIO
import time
import json
import requests
import argparse

parser = argparse.ArgumentParser(description='Chromecast toolset.')
parser.add_argument('--sensorpin', type=int, help='Sensor Pin.')
args = parser.parse_args()

config = ConfigParser()
config.read(expanduser('~/assistant-helper/smarthome.ini'))

TRANSMITTER_URL = config.get('transmitter', 'api_url')
TRANSMITTER_USER = config.get('transmitter', 'api_user')
TRANSMITTER_PASS = config.get('transmitter', 'api_pass')

LIGHT_URL = config.get('light_router', 'api_url')
LIGHT_USER = config.get('light_router', 'api_user')
LIGHT_PASS = config.get('light_router', 'api_pass')

SENSOR_PIN = args.sensorpin

GPIO.setmode(GPIO.BCM)
GPIO.setup(SENSOR_PIN, GPIO.IN)

def mein_callback(channel):
    r = requests.get(LIGHT_URL + "/light?sensor=router", auth=(LIGHT_USER, LIGHT_PASS))
    res = r.json()
    if not json.loads(res['result'][0]['result'])['light_needed']:
        return

    if GPIO.input(channel):
        switch = "an"
    else:
        switch = "aus"
    r = requests.post(TRANSMITTER_URL + "/transmitter?device=display&switch=" + switch, auth=(TRANSMITTER_USER, TRANSMITTER_PASS))

mein_callback(SENSOR_PIN)

try:
    GPIO.add_event_detect(SENSOR_PIN , GPIO.BOTH, callback=mein_callback)

    while True:
        time.sleep(100)
except KeyboardInterrupt:
    print "Beende..."

GPIO.cleanup()
