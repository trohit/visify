#!/bin/bash
# this file should be in the setup dir
# make the block files in the blocks directory

BLOCKDIR="../blocks"
for i in `cat ../block.ini|awk {'print $1'}`; 
do 
	echo $i; 
	touch $BLOCKDIR/$i
done
