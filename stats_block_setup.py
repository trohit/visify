#!/usr/bin/env python
import os
from os.path import isfile, join
import sys

from ConfigParser import ConfigParser
from pprint       import pprint
import MySQLdb

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



def getBlockList(mypath):
        lsbl = []
        bl = os.listdir('blocks')
        
        for i in bl:
            if os.path.isfile(mypath + "/" + i):
                #print(i)
                lsbl.append(i)
                
            else:
                print("not a file "+ i)
        return lsbl

def getFlatList(mypath):
        with open(mypath) as f:
                fl = f.read().splitlines()
        return fl

def execQuery(username, database, password, host, query):
    # Open database connection
    #print ("host:" + host)
    #print ("username:"+username)
    #print ("password:"+password)
    #print ("database:"+database)

    #db = MySQLdb.connect(host, username, password, database)
    db = MySQLdb.connect(host, username, password, database)
    
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    # Prepare SQL query to INSERT a record into the database.
    sql = query

    #sql = """INSERT INTO EMPLOYEE(FIRST_NAME,
    #         LAST_NAME, AGE, SEX, INCOME)
    #         VALUES ('Mac', 'Mohan', 20, 'M', 2000)"""
    try:
       # Execute the SQL command
       cursor.execute(sql)
       # Commit your changes in the database
       db.commit()
    except:
       # Rollback in case there is any error
       db.rollback()
    
    # disconnect from server
    db.close()

def execSelectQuery(username, password, host, database, query):
    db = MySQLdb.connect(host, username, password, database)
    
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    # Prepare SQL query to INSERT a record into the database.
    sql = query

    if cursor:
       # Execute the SQL command
       cursor.execute(sql)
       rows = cursor.fetchall()
       for row in rows:
            print(row)
    
    # disconnect from server
    db.close()

def insertUnit(username, password, database, host, vblock, vflatnum, vintercom):
    q = 'INSERT INTO vunit(vblock,vflatnum,vintercom) VALUES( \'' + vblock + '\', \'' + vflatnum + "','')"    
    print(q)
    execQuery(username, database, password, host, q)

"""
get max_recordid
for each record from max_recordid to 1
    extract vrecordid, flat, block
    maintain dict of [block+flat] = vrecordid
    update n dict of [block+flat] if vrecord empty
"""    
#def updateUnitRecordId(username, password, database, host, vblock, vflatnum, vrecordId):
def updateUnitRecordIds(username, password, database, host):
    query = "SELECT MAX(vrecordid) FROM vrecord"
    maxRecord = execSelectQuery(username, password, host, database, query)
    print ("maxRecord:"+ str(maxRecord))


if __name__ == "__main__":
    # Get the total number of args passed to the demo.py
    total = len(sys.argv)
    #if total < 2:
    #        print("Usage:" + sys.argv[0] + " <subject> <body>")
    #        sys.exit(1)
    #subject = str(sys.argv[1])
    #body    = str(sys.argv[2])

    installationPath = "."
    config = readConfig(installationPath)
    pprint(config)
    #sys.exit(1)

    username 	    = config['Main']['username']
    #username 	    = 'root'
    password        = config['Main']['password']
    db              = config['Main']['db']
    host            = config['Main']['host']
    mypath = 'blocks'
    lsbl = getBlockList(mypath)
    #print(lsbl)
    for i in lsbl:
            lfl = getFlatList(mypath + "/" + i)
            #print(i + " : " + str(len(lfl)) + " flats")
            for fl in lfl:
                insertUnit(username, password, db, host, i, fl, "")
    updateUnitRecordIds(username, password, db, host)

