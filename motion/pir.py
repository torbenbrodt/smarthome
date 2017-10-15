from ConfigParser import ConfigParser
from os.path import expanduser
import RPi.GPIO as GPIO
import time
import requests
import argparse
 
parser = argparse.ArgumentParser(description='Chromecast toolset.')
parser.add_argument('--sensorpin', help='Sensor Pin.')
args = parser.parse_args()

config = ConfigParser()
config.read(expanduser('~/assistant-helper/smarthome.ini'))

TRANSMITTER_URL = config.get('transmitter', 'api_url')
TRANSMITTER_USER = config.get('transmitter', 'api_user')
TRANSMITTER_PASS = config.get('transmitter', 'api_pass')

SENSOR_PIN = args.sensorpin
 
GPIO.setmode(GPIO.BCM)
GPIO.setup(SENSOR_PIN, GPIO.IN)
 
def mein_callback(channel):
    if GPIO.input(channel):
        switch = "an"
    else:
        switch = "aus"
    r = requests.post(TRANSMITTER_URL + "/transmitter?device=ecklampe&switch=" + switch, auth=(TRANSMITTER_USER, TRANSMITTER_PASS))

try:
    GPIO.add_event_detect(SENSOR_PIN , GPIO.BOTH, callback=mein_callback)

    while True:
        time.sleep(100)
except KeyboardInterrupt:
    print "Beende..."

GPIO.cleanup()
