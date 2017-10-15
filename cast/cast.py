# pip install -r requirements.txt

from __future__ import print_function
from ConfigParser import ConfigParser
from os.path import expanduser
import time
import pychromecast
import argparse

parser = argparse.ArgumentParser(description='Chromecast toolset.')
parser.add_argument('--url', help='url of the video')
parser.add_argument('--seekforward', type=int, help='Skip by n seconds')
parser.add_argument('--seekback', type=int, help='Rewind by n seconds')
parser.add_argument('--stop', help='Send the STOP command.', nargs='?')
parser.add_argument('--status', help='Print status information.', nargs='?')
parser.add_argument('--pause', help='Send the PAUSE command.')
parser.add_argument('--play', help='Send the PLAY command.')
parser.add_argument('--skip', help='Skips rest of the media.')
parser.add_argument('--rewind', help='Starts playing the media from the beginning.')
args = parser.parse_args()

config = ConfigParser()
config.read(expanduser('~/assistant-helper/smarthome.ini'))

CHROMECAST_HOST = config.get('cast', 'chromecast_ip')

if CHROMECAST_HOST:
    cast = pychromecast.Chromecast(CHROMECAST_HOST)
else:
    chromecasts = pychromecast.get_chromecasts()
    cast = next(cc for cc in chromecasts if cc.device.friendly_name == "WohnzimmerTV")

# Wait for cast device to be ready
cast.wait()

print("wait for chromecast")

mc = cast.media_controller

if args.url:
    mc.play_media(args.url, 'video/mp4')

mc.block_until_active()

if args.play:
    mc.play()

if args.pause:
    mc.pause()

if args.seekforward:
    mc.seek(mc.status.current_time + args.seekforward);

if args.seekback:
    mc.seek(mc.status.current_time - args.seekback);

if args.stop:
    mc.stop()

if args.pause:
    mc.pause()

if args.skip:
    mc.skip()

if args.rewind:
    mc.rewind()

if args.status:
    print(cast.device)
    print(cast.status)
    print(mc.status)

