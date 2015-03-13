<?php
$image = imagecreatefrompng('image3.png');

imagefilter($image, IMG_FILTER_CONTRAST, -80); 
if($image && imagefilter($image, IMG_FILTER_BRIGHTNESS, 70))
{
    echo 'Image brightness changed.';

    imagepng($image, 'sean.png');
    imagedestroy($image);
}
else
{
    echo 'Image brightness change failed.';
}
    echo '<img src="sean.png" alt="Image With A Contrast Effect Applied" />';
?>

