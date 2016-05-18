# Ansible Discovery
Ansible hosts discovery and filter by operating system.

## How this works ?
The Ansible server has a web server and each client has a curl who make a request to the web server every day.
The server generate a JSON file for each request from clients, the server execute the `generate-host.py` and this script generate the host list for Ansible.

JSON file for the hostname `client01`
```
{
    "hostname":"client01",
    "os":"centos",
    "time":"2016-05-18 14:30:00"
}
```

## Server

### Web server
I use this script with an Nginx web server, you can use Apache u other.
Web server root path: `/www/`
Web server root index: `/www/index.php`
Web server JSON file list: `/www/hosts/`

### Crontab to generate the /etc/ansible/hosts file
The script `generate-host.py` generate the file `/etc/ansible/hosts` for Ansible, the list of hosts are obtained from `/www/hosts/`.

## Client
Foreach client do you need to create a cron task with the command curl to annunce to the Ansible Discovery.

For example, if your client is an Ubuntu server, you can create the next cron task.
`* 19 * * * curl --silent http://ansible-server -X POST -d "hostname=test.your-domain.com" -d "os=Ubuntu"`

This cron execute every day at 19:00hs, this make an HTTP request method POST, with the hostname and os, as variables.
