import RPi.GPIO as GPIO
import time
import requests
 
SENSOR_PIN = 23
 
GPIO.setmode(GPIO.BCM)
GPIO.setup(SENSOR_PIN, GPIO.IN)
 
def mein_callback(channel):
    if GPIO.input(channel):
        switch = "an"
    else:
        switch = "aus"
    r = requests.post("http://localhost:9900/transmitter?device=ecklampe&switch=" + switch, auth=('apiai', 'XXX'))

try:
    GPIO.add_event_detect(SENSOR_PIN , GPIO.BOTH, callback=mein_callback)

    while True:
        time.sleep(100)
except KeyboardInterrupt:
    print "Beende..."

GPIO.cleanup()
