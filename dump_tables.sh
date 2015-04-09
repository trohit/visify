#!/bin/sh
mysql -u root -p -e 'use test;show create table test.visitor;'|sed -e 's/\\n/\n/g'|tail --lines +2 >> tables.sql2
mysql -u root -p -e 'use test;show create table test.vrecord;'|sed -e 's/\\n/\n/g'|tail --lines +2 >> tables.sql2
mysql -u root -p -e 'use test;show create table test.vpic;'|sed -e 's/\\n/\n/g'|tail --lines +2 >> tables.sql2
