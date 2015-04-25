#! /usr/bin/python

import smtplib
import sys
import os.path

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

from ConfigParser import ConfigParser
from pprint       import pprint

def readConfig(installationPath):
	config = ConfigParser()
	#r = config.read("sample.ini")
        print("installationPath is:" + installationPath)

        #installationPath="/var/www/html/d3/"
        configAbsPath = installationPath + "/" + "credentials.ini"
	r = config.read(configAbsPath)
	
	dictionary = {}
	for section in config.sections():
	    dictionary[section] = {}
	    for option in config.options(section):
	        dictionary[section][option] = config.get(section, option)
	#pprint(dictionary)
	#print("sendto is something like:"+ dictionary["Reports"]['sendto'])
        #sys.exit(1)
	return dictionary




def send_mail(subject, body, installationPath):

    config = readConfig(installationPath)

    username 	= config['Reports']['from_username']
    password 	= config['Reports']['from_password']
    to 		= config['Reports']['sendto']

    print("Sending mail to :" + to)

    # Gmail Login
    #username = 'XXXX@gmail.com'
    #password = 'secret'
    fromaddr = username
    
    # Create message container - the correct MIME type is multipart/alternative.
    msg = MIMEMultipart('alternative')
    #msg['Subject'] = "Link"
    #msg['From'] = fromaddr
    #msg['To'] = you
    msg['Subject']  = subject
    msg['From']     = username
    msg['To']       = to

    # can add some more to addrs if needed
    TOADDRS         = to
    
    # Create the body of the message (a plain-text and an HTML version).
    text = "Please enable HTML formatting in your email client to view this mail properly"
    htmlHeader = """\
    <html>
      <head></head>
      <body>
      <p>
      <pre>
"""
    htmlFooter = """\
        </pre>
        </p>
      </body>
    </html>
    """

    # Check if its a file. If not, take msgs as-is
    if(os.path.isfile(body)):
        print("Sending contents of " + body)
        fp = open(body, "r")
        BODY = fp.read()
    else:        
        print("Sending a msg")
        body_line1 = "Content-Type: text/plain; charset=UTF-8\n"
        BODY     = body_line1 + str(msg)
    html = htmlHeader + BODY + htmlFooter
    #sys.exit(1)
    

    
    # Record the MIME types of both parts - text/plain and text/html.
    part1 = MIMEText(text, 'plain')
    part2 = MIMEText(html, 'html')
    
    # Attach parts into message container.
    # According to RFC 2046, the last part of a multipart message, in this case
    # the HTML message, is best and preferred.
    msg.attach(part1)
    msg.attach(part2)
    
    #msg = 'Subject: %s\n\n%s' % (SUBJECT, BODY)
    
    # Send the message via local SMTP server.
    #s = smtplib.SMTP('localhost')
    
    # Sending the mail  
    s = smtplib.SMTP('smtp.gmail.com:587')
    s.starttls()
    s.login(username,password)
    
    # sendmail function takes 3 arguments: sender's address, recipient's address
    # and message to send - here it is sent as one string.
    s.sendmail(fromaddr, TOADDRS, msg.as_string())
    s.quit()

if __name__ == "__main__":
        # Get the total number of args passed to the demo.py
        total = len(sys.argv)
        if total < 2:
                print("Usage:" + sys.argv[0] + " <subject> <body>")
                sys.exit(1)
        subject = str(sys.argv[1])
        body    = str(sys.argv[2])

        # get installation path
        installationPath = ""
        isLink = os.path.islink(sys.argv[0])
        #print("Filename SELF is:"+ sys.argv[0])
        if isLink == True:
            targetPath = os.readlink(sys.argv[0])
            installationPath = os.path.dirname(targetPath)
        else:
            #print("self :"+ sys.argv[0] + " is not a link")
            targetPath = (sys.argv[0])
            installationPath = os.path.dirname(targetPath)
        #print("targetPath:"+ targetPath +" installationPath:"+ installationPath)
        send_mail(subject, body, installationPath)
