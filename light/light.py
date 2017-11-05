from ConfigParser import ConfigParser
from os.path import expanduser
from datetime import datetime
import argparse
import json

parser = argparse.ArgumentParser(description='Light sensor toolset.')
parser.add_argument('--sensor', help='url of the video')
parser.add_argument('--sunrise', help='Set the sunrise time in format hh:mm')
parser.add_argument('--sunset', help='Set the sunset time in format hh:mm')
args = parser.parse_args()

filename = expanduser('~/assistant-helper/smarthome.ini')
section = 'light_' + args.sensor

config = ConfigParser()
config.read(filename)

# setter
if args.sunrise or args.sunset:
    if not config.has_section(section):
        config.add_section(section)
    config.set(section, 'sunrise', args.sunrise)
    config.set(section, 'sunset', args.sunset)

    with open(filename, 'w') as outfile:
        config.write(outfile)
else:
    sunrise = config.get(section, 'sunrise')
    sunset = config.get(section, 'sunset')
    
    now = datetime.now()
    m_today = (now - now.replace(hour=0, minute=0, second=0, microsecond=0)).total_seconds() / 60.0

    print json.dumps({
        "light_needed": m_today < int(sunrise[:-3]) * 60 + int(sunrise[-2:]) or m_today > int(sunset[:-3]) * 3600 + int(sunset[-2:]),
        "sunrise": sunrise,
        "sunset": sunset
    })
