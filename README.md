# Ansible Discovery

Ansible discovery host and filter by operating system.

## Server
### Nginx web server

Configuration file for Nginx
`/nginx/nginx.conf`

Root path web server `/www/`

### Crontab to generate the /etc/ansible/hosts file

Generate the file with the host list for Ansible
`* 20 * * * /usr/bin/python generate-host.py`

## Client
Foreach client do you need to create a cron with the command curl for annunce to the Ansible Discovery.
For example, if your client is an Ubuntu server, you can create the next cron.

`* 19 * * * curl --silent http://ansible-server -X POST -d "hostname=test.your-domain.com" -d "os=Ubuntu"`
