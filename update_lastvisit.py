#!/usr/bin/env python
import os
from os.path import isfile, join
import sys

from ConfigParser import ConfigParser
from pprint       import pprint
import MySQLdb

hitCount = 0
missCount = 0
otherCount= 0
missingRecordCount = 0

# http://stackoverflow.com/questions/635483/what-is-the-best-way-to-implement-nested-dictionaries-in-python
#dictUnitStats = dict()
#
#class AutoVivification(dict):
#    """Implementation of perl's autovivification feature."""
#    def __getitem__(self, item):
#        try:
#            return dict.__getitem__(self, item)
#        except KeyError:
#            value = self[item] = type(self)()
#            return value
class Vividict(dict):
    def __missing__(self, key):
        value = self[key] = type(self)()
        return value

#dictUnitStats = AutoVivification()
dictUnitStats = Vividict()

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

def execQuery(username, password, host, database, query):
    # Open database connection
    #print ("host:" + host)
    #print ("username:"+username)
    #print ("password:"+password)
    #print ("database:"+database)

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
    # Open database connection
    #print ("host:" + host)
    #print ("username:"+username)
    #print ("password:"+password)
    #print ("database:"+database)
    db = MySQLdb.connect(host, username, password, database)
    
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    # Prepare SQL query to INSERT a record into the database.
    sql = query

    if cursor:
       # Execute the SQL command
       numrows = cursor.execute(sql)
       rows = cursor.fetchall()
       #numrows = cursor.rowcount()
       #for row in rows:
       #     print(row)
    
    # disconnect from server
    db.close()
    if numrows > 0:
        return rows[0]
    else:
        return False

def insertUnit(username, password, database, host, vblock, vflatnum, vintercom):
    q = 'INSERT INTO vunit(vblock,vflatnum,vintercom) VALUES( \'' + vblock + '\', \'' + vflatnum + "','')"    
    print(q)
    execQuery(username, password, host, database, q)

def getRecordById(recId, username, password, database, host):
    global missingRecordCount
    query = 'SELECT vrecordid, vblock, vflatnum, vitime FROM vrecord WHERE vrecordid=' + str(recId)
    #print(query)
    recInfo = execSelectQuery(username, password, host, database, query)
    if recInfo == False:
        #print ("Empty val")
        missingRecordCount = missingRecordCount + 1
        #sys.exit(1)
        return False

    record = dict()
    record['recordid']  = recInfo[0]
    record['block']     = recInfo[1]
    record['flat']      = recInfo[2]
    record['vitime']    = recInfo[3]

    #return recInfo
    return record

def updateUnitStats(recInfo, username, password, database, host):
    global dictUnitStats
    #query = 'UPDATE VUNIT SET 
    recId = recInfo['recordid']
    block = recInfo['block']
    flat  = recInfo['flat']
    vitime = recInfo['vitime']
    dictUnitBlob = dict()
    dictUnitBlob['recordid'] = recId
    dictUnitBlob['vitime'] = vitime
    dictUnitStats[block][flat] = dictUnitBlob

"""
get max_recordid
for each record from max_recordid to 1
    extract vrecordid, flat, block
    maintain dict of [block+flat] = vrecordid
    update n dict of [block+flat] if vrecord empty
"""   
def isExistsKey(block, flat):
    k1 = block
    k2 = flat
    if k2 in dictUnitStats.get(k1, {}):
        #print "Found"
        return True
    else:
        #print "Missing"
        return False

#def updateUnitRecordId(username, password, database, host, vblock, vflatnum, vrecordId):
def updateUnitRecordIds(username, password, database, host):
    global hitCount
    global missCount
    global otherCount
    query = "SELECT MAX(vrecordid) FROM vrecord"
    maxRecord = execSelectQuery(username, password, host, database, query)[0]
    query = "SELECT MIN(vrecordid) FROM vrecord"
    minRecord = execSelectQuery(username, password, host, database, query)[0]
    print ("maxRecord:"+ str(maxRecord))
    print ("minRecord:"+ str(minRecord))
    #print ("type:"+ str(type(maxRecord)))
    #print ("type:"+ str(maxRecord[0]))
    t = maxRecord
    #t = 3712
    # TODO: if we need a quick check
    #t = 57
    #while t > maxRecord - 1000:
    while t > 0:
        recInfo = getRecordById(t, username, password, database, host)
        if recInfo != False:
            print(recInfo)
            recId = recInfo['recordid']
            block = recInfo['block']
            flat  = recInfo['flat']
            vitime = recInfo['vitime']
            if 'Other' in block:
                otherCount = otherCount + 1
            elif isExistsKey(block, flat) == False:
                # first time entries
                updateUnitStats(recInfo, username, password, database, host)
                hitCount = hitCount + 1
            else:
                # entry already present
                missCount = missCount + 1
        t -= 1
    #print(dictUnitStats)
    #k1 = 'MontTitlis-R'
    #k2 = '503'
    #print(isExistsKey(k1, k2))
    print ("Hits:"+ str(hitCount) + " Miss:" + str(missCount) + " Other:" + str(otherCount))
    print ("MissingRecords:" + str(missingRecordCount))



def recurse(d):
  if type(d)==type({}):
    for k in d:
      print "Recursing through " + k
      recurse(d[k])
  else:
    print(type(d))
    print d

def updateUnitWithRecordId(username, password, database, host, block,flat,rid, vitime):
    # update block flat with record
    query = "UPDATE vunit set lvrecordid="+rid+ ", lvitime='" +vitime +"'  WHERE vblock='"+ block+ "' AND vflatnum='" + flat +"'"
    print query
    execQuery(username, password, host, database, query)

#time to upoad dict values into the db
def phase3(username, password, database, host):
    # uses dictUnitStats
    #print(dictUnitStats)
    #recurse(dictUnitStats)
    for block in dictUnitStats:
        #print "Printing " + block + " contents"
        dictFlatsInSomeBlock = dict(dictUnitStats[block])
        #pprint(dictFlatsInSomeBlock)

        for flat in dictFlatsInSomeBlock:
            dictUnitRecord = dictFlatsInSomeBlock[flat] 

            rid = str(dictUnitRecord['recordid'])
            vitime = str(dictUnitRecord['vitime'])
            #print "Unit " + block + ":" + flat + " :" + rid
            updateUnitWithRecordId(username, password, database, host, block, flat, rid, vitime)

    sys.exit(1)


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
    #pprint(config)
    #sys.exit(1)

    username 	    = config['Main']['username']
    #username 	    = 'root'
    password        = config['Main']['password']
    db              = config['Main']['db']
    host            = config['Main']['host']
    updateUnitRecordIds(username, password, db, host)
    phase3             (username, password, db, host)

