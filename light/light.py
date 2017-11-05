from ConfigParser import ConfigParser
import argparse
import sys

parser = argparse.ArgumentParser(description='Light sensor toolset.')
parser.add_argument('--sensor', help='url of the video')
parser.add_argument('--sunrise', help='Set the sunrise time in format hh:mm')
parser.add_argument('--sunset', help='Set the sunset time in format hh:mm')
args = parser.parse_args()

filename = expanduser('~/assistant-helper/smarthome.ini')

config = ConfigParser()
config.read(filename)

# setter
if args.sunrise || args.sunset:
    config['light'] = {
        args.sensor: {
            sunrise: args.sunrise,
            sunset: args.sunset
        }
    }

    #with open(filename, 'w') as outfile:
    #    config.write(outfile)
    config.write(sys.stdout)
else:
    print config['light']
