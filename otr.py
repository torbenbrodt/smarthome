from ftplib import FTP
from ConfigParser import ConfigParser
from os.path import expanduser
import re

# Parse config file into constants.
config = ConfigParser()
config.read(expanduser('~/assistant-helper/smarthome.ini'))

ftp = FTP(config.get('otr', 'ftp_host'))
ftp.login(config.get('otr', 'ftp_user'), config.get('otr', 'ftp_pass')) 

files = []

try:
    files = ftp.nlst()
    files = [x for x in files if x[0] != "."]

except ftplib.error_perm, resp:
    if str(resp) == "550 No files found":
        print "No files in this directory"
    else:
        raise

for f in files:
    m = re.search("(.*)_([0-9]{2})\.([0-9]{2})\.([0-9]{2})_([0-9]{2}\-[0-9]{2})_([A-Za-z0-9]+)", f)
    print m.groups()
    #('Die_Hoehle_der_Loewen', '17', '10', '03', '20-15', 'vox')
