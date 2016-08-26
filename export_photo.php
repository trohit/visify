<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");

function img_raw_to_file($data, $filename)
{
        //$data = 'data:image/png;base64,AAAFBfj42Pj4';

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        //file_put_contents('img.png', base64_decode($base64string));

        //file_put_contents('/tmp/image.png', $data);
        file_put_contents($filename, $data);
}
DB::$user = '####';
DB::$password = '####';
DB::$dbName = '####';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
$max_id = 1000; // max visitor id to export
for ($i =0;$i<$max_id;$i++) {

        $result = DB::queryFirstRow("SELECT vphoto FROM visitor WHERE visitor.vid=%i", $i);
        $piclen = strlen($result['vphoto']);
        $picpreview = substr($result['vphoto'], 0, 25);
        $picraw     =        $result['vphoto'];
        if ($piclen == 0) {
                //print("vid". $i . " is empty");
                continue;
        }
        print("\n". $i . " contents:".$picpreview . "len:" . $piclen);
        $filepath = $i . ".png";
        img_raw_to_file($picraw, $filepath);
}

exit;
?>
