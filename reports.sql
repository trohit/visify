select vrecordid, vid, vitime, vblock,vflatnum,vtomeet,vpurpose from vrecord order by vrecordid desc limit 25;
select count(*) from test.vrecord where vitime between '2015-04-07 22:00:00' and '2015-04-07 23:59:59';
select count(*),vpurpose from vrecord order by vrecordid desc limit 25;

