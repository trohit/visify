#! /usr/bin/env python
# Program to make a backup of the mysql database 
# two backups are made:
# 1. in the directory where its invoked
# 2. in the sd card if present
import sys,os,subprocess
import errno
import shutil

# removes files older than 24 hours
import os
import datetime

def remove_older_than_n_hours(search_dir, elapsed_hours=24):
    #dir_to_search = os.path.curdir
    dir_to_search = search_dir
    for dirpath, dirnames, filenames in os.walk(dir_to_search):
        for file in filenames:
            curpath = os.path.join(dirpath, file)
            file_modified = datetime.datetime.fromtimestamp(os.path.getmtime(curpath))
            if datetime.datetime.now() - file_modified > datetime.timedelta(hours=elapsed_hours):
                print "should remove " + curpath
                os.remove(curpath)
            else:
                print "should not remove " + curpath

def file_get_contents(filename):
    with open(filename, 'r') as f:
        mnt_path = f.readline().rstrip('\n')
        return mnt_path


def find_sdcard_path():
    # usually, sd cards are found in paths like 
    # /media/rohit/9016-4EF8
    # We wil be dumping to a path like:
    # /media/rohit/9016-4EF8/.dump/
    tmp_out = "/tmp/out"
    cmd = "sudo mount|grep media| grep '-'|awk {'print $3'} > " + tmp_out
    output = subprocess.check_output(cmd, shell=True)
    sdcard_path =  file_get_contents(tmp_out)
    return sdcard_path


def silentremove(filename):
    try:
        os.remove(filename)
    except OSError as e: # this would be "except OSError, e:" before Python 2.6
        if e.errno != errno.ENOENT: # errno.ENOENT = no such file or directory
            raise # re-raise exception if a different error occured

def silentmakedir(filename):
    try:
        os.makedirs(filename)
    except OSError as e: # this would be "except OSError, e:" before Python 2.6
        if e.errno != errno.ENOENT: # errno.ENOENT = no such file or directory
            raise # re-raise exception if a different error occured

def create_dump_dir_if_needed(dumpDirPath):
        res = os.path.isdir(dumpDirPath)
        if res == False:
                # sometimes it exists but is a file
                silentremove(dumpDirPath)
                silentmakedir(dumpDirPath)


def dump_mysql_and_prune_old_files(localDumpDir, isSdCardPresent, sdDumpdirFullPath, agedHours):
        # to decrypt use:
        # ccdecrypt -k keyfile .dump/mysqldump_04-08-20152221.sql.gz.cpt
        # gunzip .dump/mysqldump_04-08-20152207.sql.gz
        # to backup and restore use:
        # backup: # mysqldump -u root -p[root_password] [database_name] > dumpfilename.sql
        # restore:# mysql -u root -p[root_password] [database_name] < dumpfilename.sql
        
        #output = subprocess.check_output("cat syscall_list.txt | grep f89e7000 | awk '{print $2}'", shell=True)
        MYSQLDUMP=subprocess.check_output('which mysqldump', shell=True).rstrip('\n')
        GZIP=subprocess.check_output('which gzip', shell=True).rstrip('\n')
        CCRYPT=subprocess.check_output('which ccrypt', shell=True).rstrip('\n')
        #DBNAME="test"
        DBNAME="visify"
        DBUSER="backup"
        DBPASSWD="JSNCACNISQXGMA"
        KEYFILE="keyfile"
        TIMESTAMP=subprocess.check_output('date \"+%Y-%m-%d-%H%M\"', shell=True).rstrip('\n')
        MYSQLDUMP_OPTIONS=" --single-transaction "
        FULL_DUMP_PATH=localDumpDir + "/mysqldump_" +TIMESTAMP+".sql.gz.cpt"
        cmd=MYSQLDUMP + MYSQLDUMP_OPTIONS + " -u " + DBUSER + " -p" + DBPASSWD + " " + DBNAME + "| " + GZIP + " | " + CCRYPT +" -k " + KEYFILE + " > "+ FULL_DUMP_PATH
        print "cmd is[" +cmd+ "]"
        #sys.exit(0)
        output = False
        output = subprocess.check_output(cmd, shell=True)
        if isSdCardPresent == True:
            # copy to the sd card if it exists
            shutil.copy2(FULL_DUMP_PATH, sdDumpdirFullPath)

        if output != False:
            remove_older_than_n_hours(localDumpDir, agedHours)              
            if isSdCardPresent == True:
                remove_older_than_n_hours(sdDumpdirFullPath, agedHours)       

# TODO: Remember to change this to at least 96 hours
agedHours = 96

isSdCardPresent = False
dumpDirRelativePath = ".dump"
base_path = find_sdcard_path()
sdDumpdirFullPath = base_path + "/" + dumpDirRelativePath


create_dump_dir_if_needed(dumpDirRelativePath)
if len(base_path) > 0:
    isSdCardPresent = True
    print("Checking for "+ sdDumpdirFullPath)
    create_dump_dir_if_needed(sdDumpdirFullPath)
dump_mysql_and_prune_old_files(dumpDirRelativePath, isSdCardPresent, sdDumpdirFullPath, agedHours)

#sys.exit(0)

