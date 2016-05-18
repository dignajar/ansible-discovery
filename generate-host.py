#!/usr/bin/python

# Where are stored the JSON files
JSON_DIR = '/www/hosts/'

# Where Ansible store the host list
ANSIBLE_HOSTS_FILE = '/etc/ansible/hosts'

# Import Python libraries
import glob
import os
import json
from datetime import datetime, timedelta

# Current time in UTC
currentTime = datetime.utcnow()
yesterday = currentTime - timedelta(days=1)

# OS Lists
availableOS = {'debian' : [], 'ubuntu' : [], 'redhat' : [], 'centos' : [], 'other' : [], 'notReported' : []}

# Change current working directory
os.chdir(JSON_DIR)

# Foreach JSON file
for filename in sorted(glob.glob("*.json")):

    with open(JSON_DIR+filename) as dataFile:

        # Get JSON data
        data = json.load(dataFile)

        # Time, OS and Hostname from JSON
        time = datetime.strptime(data['time'], "%Y-%m-%d %H:%M:%S")
        os = data['os']
        hostname = data['hostname']

        # If the host is out of date add to not reported list
        if(time > yesterday):
            availableOS[os].append(hostname)
        else:
            availableOS['notReported'].append(hostname)

# Generate the /etc/ansible/hosts file
handle = open(ANSIBLE_HOSTS_FILE, "w")

for os, hosts in availableOS.items():

    handle.write("["+os+"]\n")

    for host in hosts:
        handle.write(host+"\n")

    handle.write("\n")

handle.close()
