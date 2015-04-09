#!/bin/bash
# to decrypt use:
# ccdecrypt -k keyfile .dump/mysqldump_04-08-20152221.sql.gz.cpt
# gunzip .dump/mysqldump_04-08-20152207.sql.gz
# to backup and restore use:
# backup: # mysqldump -u root -p[root_password] [database_name] > dumpfilename.sql
# restore:# mysql -u root -p[root_password] [database_name] < dumpfilename.sql
DBNAME=test
DUMP_PATH=.dump
#mysqldump -u backup -pJSNCACNISQXGMA –all-databases –routines| gzip > /root/MySQLDB_`date ‘+%m-%d-%Y’`.sql.gz
mysqldump -u backup -pJSNCACNISQXGMA $DBNAME| gzip |ccrypt -k keyfile > $DUMP_PATH/mysqldump_`date '+%m-%d-%Y%H%M'`.sql.gz.cpt




