# -*- coding: utf-8 -*-
# @Author: minhducnho
# @Date:   2018-03-22 23:10:29
# @Last Modified by:   minhducnho
# @Last Modified time: 2018-04-04 23:52:36
import os
import sys

def createVirlHost(domain, path):
	vir = open('/etc/apache2/sites-available/' + domain + ".conf", "w")
	vir.write ("<VirtualHost *:8044>")
	vir.write("\n\tServerName " + domain)
	vir.write("\n\tServerAlias " + domain)
	vir.write("\n\tServerAdmin " + domain + "@localhost")
	vir.write("\n\tDocumentRoot /var/www/public_html/" + path + "/" + domain)
	vir.write("\n\t<Directory /var/www/public_html/" + path + "/" + domain + " >")
	vir.write("\n\t\tOptions Indexes FollowSymLinks MultiViews")
	vir.write("\n\t\tAllowOverride All")
	vir.write("\n\t\tRequire all granted")
	vir.write("\n\t</Directory>")
	vir.write("\n\tErrorLog /var/www/public_html/" + path + "/" + domain + "/error.log")
	vir.write("\n\tCustomLog /var/www/public_html/" + path + "/" + domain + "/access.log combined")
	vir.write("\n</VirtualHost>")
	vir.close()
	os.system('a2ensite ' + domain)
	os.system('mkdir -p /var/www/public_html/' + path + "/" + domain)
	os.system('chown phuongle:phuongle /var/www/public_html/' + path + "/" + domain)
	os.system('service apache2 restart')

def createDomain(domain):
	os.system('sudo -- sh -c "echo 127.0.0.1       '+domain+' >> /etc/hosts"')

euid = os.geteuid()
if euid != 0:
    print "Script not started as root. Running sudo.."
    args = ['sudo', sys.executable] + sys.argv + [os.environ]
    # the next line replaces the currently-running process with the sudo
    os.execlpe('sudo', *args)

domain = raw_input("Enter your domain: ")
path = raw_input("Enter your path site: ")
if domain == "" or path == "":
	print "not domain or path site"
else:
	createDomain(domain)
	createVirlHost(domain, path)
