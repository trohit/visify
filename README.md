# visify


# A Visitor Tracking and Management Utility

# Overview:
http://bit.ly/ecogatepass

![https://www.slideshare.net/slideshow/embed_code/56370363](https://raw.githubusercontent.com/trohit/visify/master/visitor-management-system.jpg)

# Minimum Hardware Needed:
1. A PC
2. An ESC POS compatible Printer
3. A flash card / USB for automated backups in the case of a disk outage

# Features:
1. Tracks visitors by their Mobile Number
1. Relevant details like visitor's photo, name, affiliation, host name and authorized areas of access captured
1. Cloud deployable / Self Hosted
1. Gate pass option
1. Automated Daily reports
1. Multi Gate Tracking
1. Easily scalable using tablets or mobile

Technologies:
LAMP Stack, AJAX, Jquery, Python

DEPENDS Upon:
1. esc pos
2. parsley validation
3. ccrypt for encrypting sql dumps
4. sudo apt-get install cron-apt
5. sudo pip install php # for php goodness in python
6. ccryot to encrypt mysql dumps
7. media.navigator.permission.disabled in firefox to toggled
8. Automatic security updates
   You can set up unattended-upgrades pretty easily by typing this in a
   terminal:
	sudo apt-get install unattended-upgrades
	sudo dpkg-reconfigure unattended-upgrades
9. Auto ssh and reverse connection for remote admin
10. audio player such as afplay to play wav files for checkout


Commands
sudo aptitude install --without-recommends ubuntu-desktop

sudo apt-get install libmysqlclient-dev
sudo pip install mysql-python

sudo apt-get install php5-dev make php-pear
sudo pecl install mongo
sudo echo "extension=mongo.so" | sudo tee /etc/php5/mods-available/mongo.ini
sudo apt-get install php5-mongo

sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo 'deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen' | sudo tee /etc/apt/sources.list.d/mongodb.list
sudo apt-get update
sudo apt-get install mongodb-org

Links

http://www.ubuntugeek.com/install-gui-in-ubuntu-server.html


