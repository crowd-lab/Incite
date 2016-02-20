<?php

function get_image_url_for_item($item) {
    $url_path = (isset($_SERVER['HTTPS']) ? "https" : "http") . '://'. $_SERVER['HTTP_HOST'] . '/m4j/files/original/';
    if ($item == null)
        return '';

    $files = $item->getFiles();
    if (count($files) <= 0)
        return '';
    else if (count($files) == 1)
        return $item->getFile()->getProperty('uri');
    else {
        $filenames = get_jpeg_filenames_from_item($item);
        merge_images($filenames, 'incite_'.$item->id.'.jpeg');
        return $url_path.'incite_'.$item->id.'.jpeg';
    }
}
function get_jpeg_filenames_from_item($item) {
    $filenames = array();
    $files = $item->getFiles();
    for ($i = 0; $i < count($files); $i++) {
        if ($files[$i]['mime_type'] === "image/jpeg")
            $filenames[] = $files[$i]['filename'];
    }
    return $filenames;
}
function merge_images($filenames, $filename_result) {

    if (count($filenames) <= 0)
        return false;

    $path = getcwd().'/files/original/';
    $widths = array();
    $heights = array();
    for ($i = 0; $i < count($filenames); $i++) {
        list($width, $height) = getimagesize($path.$filenames[$i]);
        $widths[] = $width;
        $heights[] = $height;
    }

    $final_width = max($widths);
    $final_height = array_sum($heights);

    $final_image = imagecreatetruecolor($final_width, $final_height);

    $acc_height = 0;
    for ($i = 0; $i < count($filenames); $i++) {
        $image = imagecreatefromjpeg($path.$filenames[$i]);
        list($width, $height) = getimagesize($path.$filenames[$i]);
        imagecopy($final_image, $image, 0, $acc_height, 0, 0, $width, $height);
        $acc_height += $height;
        imagedestroy($image);
    }

    imagejpeg($final_image, $path.$filename_result);
    imagedestroy($final_image);

    return true;

}

function customizedTimeCmpFuncASC($a, $b)
{
    if ($a['time'] < $b['time'])
        return -1;
    else if ($a['time'] == $b['time'])
        return 0;
    else
        return 1;
}

function customizedTimeCmpFuncDESC($a, $b)
{
    if ($a['time'] < $b['time'])
        return 1;
    else if ($a['time'] == $b['time'])
        return 0;
    else
        return -1;
}
function addKeyValueToArray(&$a, $key, $val)
{
    $len = count($a);
    for ($i = 0; $i < $len; $i++)
        $a[$i][$key] = $val;
}

function getOmekaFolderName()
{
    //Get omeka directory indirectly via "controllers" folder where this file is located!
    //omake_dir/plugins/Incite/controllers
    $omeka_folder_name = basename(realpath(dirname(__FILE__).'/../../..'));
    return $omeka_folder_name;
}

function getOmekaUrl()
{
    $host = $_SERVER['HTTP_HOST'];
    return $host . '/' . getOmekaFolderName();
}

function getInciteUrl()
{
    return getOmekaUrl() . '/' . 'incite';
}

function getFullOmekaUrl()
{
    $host = $_SERVER['HTTP_HOST'];
    return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $host . '/' . getOmekaFolderName();
}

function getFullInciteUrl()
{
    return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . getOmekaUrl() . '/' . 'incite';
}

?>
