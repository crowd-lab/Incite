<?php
require_once("Email.php");

function get_image_url_for_item($item, $is_thumbnail = false) {
    //$url_path = (isset($_SERVER['HTTPS']) ? "https" : "http") . '://'. $_SERVER['HTTP_HOST'] . '/m4j/files/original/';
    $url_path = getFullOmekaUrl();
    if ($is_thumbnail)
        $url_path .= '/files/thumbnails/';
    else
        $url_path .= '/files/original/';
    $path = getcwd().'/files/original/';
    if ($item == null)
        return '';

    $files = $item->getFiles();
    if (count($files) <= 0)
        return '';
    else if (count($files) == 1) {
        if ($is_thumbnail)
            return $item->getFile()->getProperty('thumbnail_uri');
        else
            return $item->getFile()->getProperty('uri');
    } else {
        $filenames = get_jpeg_png_filenames_from_item($item);
        $full_target_filename = $path.'incite_'.$item->id.'.jpeg';
        $target_filename = 'incite_'.$item->id.'.jpeg';
        if (!file_exists($full_target_filename))
            merge_images($filenames, $target_filename);
        return $url_path.'incite_'.$item->id.'.jpeg';
    }
}
function get_jpeg_png_filenames_from_item($item) {
    $filenames = array();
    $files = $item->getFiles();
    for ($i = 0; $i < count($files); $i++) {
        if ($files[$i]['mime_type'] === "image/jpeg")
            $filenames[] = array('file' => $files[$i]['filename'], 'type' => 'jpg');
        else if ($files[$i]['mime_type'] === "image/png")
            $filenames[] = array('file' => $files[$i]['filename'], 'type' => 'png');
    }
    return $filenames;
}
function merge_images($filenames, $filename_result) {

    if (count($filenames) <= 0)
        return false;

    $path = getcwd().'/files/original/';
    $oldcwd = getcwd();
    chdir('./files/original/');

    $file_names = '';

    foreach ((array)$filenames as $filename) {
        $file_names .= $filename['file']." ";
    }

    //full size
    system('convert '.$file_names.'-append '.$filename_result);

    //thumbnail
    system('convert '.$filename_result.' -thumbnail \'200x200\' ../thumbnails/'.$filename_result);

//using php functions: too much memory consumption!
/*
    $widths = array();
    $heights = array();
    for ($i = 0; $i < count($filenames); $i++) {
        list($width, $height) = getimagesize($path.$filenames[$i]['file']);
        $widths[] = $width;
        $heights[] = $height;
    }

    $final_width = max($widths);
    $final_height = array_sum($heights);
        echo $final_width." -- ".$final_height."\n<br>";
        die();

    $final_image = imagecreatetruecolor($final_width, $final_height);

    $acc_height = 0;
    for ($i = 0; $i < count($filenames); $i++) {
        if ($filenames[$i]['type'] === 'jpg')
            $image = imagecreatefromjpeg($path.$filenames[$i]['file']);
        else if ($filenames[$i]['type'] === 'png')
            $image = imagecreatefrompng($path.$filenames[$i]['file']);
        list($width, $height) = getimagesize($path.$filenames[$i]['file']);
        imagecopy($final_image, $image, 0, $acc_height, 0, 0, $width, $height);
        $acc_height += $height;
        imagedestroy($image);
    }


    imagejpeg($final_image, $filename_result);
    imagedestroy($final_image);
*/

    chdir($oldcwd);

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

function getOmekaPath()
{
    return dirname($_SERVER['PHP_SELF']);
}

function getOmekaUrl()
{
    $host = $_SERVER['HTTP_HOST'];
    return $host . getOmekaPath();
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

function getReadableTimeFromMySQL($time)
{
}




/**
 * A helper function that protects again two large bugs:
 *
 * 1.) XSS/SQL injection is prevented by the strip_tags/json_encode
 * 2.) Provides a workaround for sending strings with " or ' in them to javascript as strings
 *     Ex: Prevents var title = "<?php echo $this->title; ?>"; from breaking if title contains a "
 */
function sanitizeStringInput($input) {
    if (empty($input)) {
        return json_encode(array('value' => ''));
    }

    $json_encoded_array = json_encode(array('value' => strip_tags($input)));

    //json_encode will fail if ?bad characters? are present, not sure what exactly causes this
    if (empty($json_encoded_array)) {
        return json_encode(array('value' => "PARSING ERROR, BAD CHARACTERS"));
    } else {
        return $json_encoded_array;
    }
}

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

function getPositiveSubjects($subjects) {

}
?>
