select vrecordid, vid, vitime, vblock,vflatnum,vtomeet,vpurpose from vrecord order by vrecordid desc limit 25;
select count(*) from test.vrecord where vitime between '2015-04-07 22:00:00' and '2015-04-07 23:59:59';
select count(*),vpurpose from vrecord order by vrecordid desc limit 25;

# flats visited today
select count(*) from vunit where lvitime >= '2015-07-30';
#flats never visited
select count(*) from vunit where lvrecordid is NULL;
#stats - top 20 most visited flats
select vunit.vblock, vunit.vflatnum, count(*) as count from vrecord,vunit where vunit.vblock=vrecord.vblock and vunit.vflatnum=vrecord.vflatnum and vitime >'2015-05-01' group by vunit.vblock,vunit.vflatnum order by count desc limit 20;
#vehicles
select vvehicle_reg_num,vvehicle_type from vrecord where vvehicle_reg_num IS NOT NULL and vvehicle_reg_num != '0'  and vitime LIKE '2015-05-01%' and vvehicle_type='4w'  ;
# show top n with resident name and purpose
SELECT vunit.vblock AS Block, vunit.vflatnum AS Flatnum, vtomeet as Resident, vpurpose, count(*) AS COUNT FROM vrecord,vunit WHERE vunit.vblock=vrecord.vblock AND vunit.vflatnum=vrecord.vflatnum AND vitime >='2015-05-01' GROUP BY vunit.vblock,vunit.vflatnum ORDER BY COUNT DESC LIMIT 5;

