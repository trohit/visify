#!/bin/bash
# sample commands


# create db
#CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_bin;
#secret
#CREATE USER 'rohit'@'%' IDENTIFIED BY PASSWORD '*14E65567ABDB5135D0CFD9A70B3032C179A49EE7';
# can fail if user exists
GRANT SELECT, INSERT, UPDATE, DELETE, DROP, ALTER, CREATE TEMPORARY TABLES ON test.* TO rohit@localhost;
##GRANT ALL ON test TO 'rohit'@'%';
##GRANT CREATE ON test TO 'rohit'@'%';
FLUSH PRIVILEGES;

USE test;


#CREATE TABLE `visitor` (
#  `vid` int(11) NOT NULL AUTO_INCREMENT,
#  `vname` varchar(32) NOT NULL,
#  `vphone` varchar(12) NOT NULL,
#  `vgender` varchar(12) DEFAULT NULL,
#  `vage` int(11) NOT NULL,
#  `vaddress` varchar(256) NOT NULL,
#  `vcomments` varchar(512) DEFAULT NULL,
#  `vctime` datetime NOT NULL,
#  `vmtime` datetime NOT NULL,
#  `vatime` datetime NOT NULL,
#  `vphoto` mediumblob DEFAULT NULL,
#  PRIMARY KEY (`vid`),
#  UNIQUE KEY `vphone` (`vphone`)
#) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

CREATE TABLE `vrecord` (
  `vid` int(11) NOT NULL,
  `vrecordid` int(11) NOT NULL AUTO_INCREMENT,
  `vvehicle_reg_num` varchar(32) DEFAULT NULL,
  `vvehicle_details` varchar(128) DEFAULT NULL,
  `vitime` datetime NOT NULL,
  `votime` datetime DEFAULT NULL,
#  `vphoto` blob,
  `vflatnum` varchar(24) NOT NULL,
  `vblock` varchar(32) NOT NULL,
  `vpurpose` varchar(128) DEFAULT NULL,
  `vtomeet` varchar(32) NOT NULL,
  PRIMARY KEY (`vrecordid`),
  KEY `vid` (`vid`),
  CONSTRAINT `vrecord_ibfk_1` FOREIGN KEY (`vid`) REFERENCES `visitor` (`vid`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

CREATE TABLE `vpic` (
	  `vpicid` int(11) NOT NULL AUTO_INCREMENT,
	  `vid` int(11) NOT NULL,
	  `vphoto` MEDIUMBLOB,
	  PRIMARY KEY (`vpicid`),
	  KEY `vid` (`vid`),
	  CONSTRAINT `vpic_ibfk_1` FOREIGN KEY (`vid`) REFERENCES `visitor` (`vid`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;


ALTER TABLE `vrecord` ADD `vvehicle_type` VARCHAR( 16 ) NULL AFTER `vvehicle_reg_num` ;
ALTER TABLE `vrecord` ADD `vnum_visitors` int(11) DEFAULT NULL AFTER `vtomeet` ;
ALTER TABLE `vrecord` ADD `vrcomments` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `vnum_visitors`;
ALTER TABLE `vrecord` ADD `vduration_fillup` INT( 11 ) NULL ;
