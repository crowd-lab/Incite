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
    return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . getOmekaUrl() . '/';
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

function getWorkingGroupID() {
    if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
      return $_SESSION['Incite']['USER_DATA']['working_group']['id'];
    } else {
      return 0;
    }
  }
function loc_to_lat_long($loc_str)
{
    $states = array(
            'Alabama'=>'AL',
            'Alaska'=>'AK',
            'Arizona'=>'AZ',
            'Arkansas'=>'AR',
            'California'=>'CA',
            'Colorado'=>'CO',
            'Connecticut'=>'CT',
            'Delaware'=>'DE',
            'Florida'=>'FL',
            'Georgia'=>'GA',
            'Hawaii'=>'HI',
            'Idaho'=>'ID',
            'Illinois'=>'IL',
            'Indiana'=>'IN',
            'Iowa'=>'IA',
            'Kansas'=>'KS',
            'Kentucky'=>'KY',
            'Louisiana'=>'LA',
            'Maine'=>'ME',
            'Maryland'=>'MD',
            'Massachusetts'=>'MA',
            'Michigan'=>'MI',
            'Minnesota'=>'MN',
            'Mississippi'=>'MS',
            'Missouri'=>'MO',
            'Montana'=>'MT',
            'Nebraska'=>'NE',
            'Nevada'=>'NV',
            'New Hampshire'=>'NH',
            'New Jersey'=>'NJ',
            'New Mexico'=>'NM',
            'New York'=>'NY',
            'North Carolina'=>'NC',
            'North Dakota'=>'ND',
            'Ohio'=>'OH',
            'Oklahoma'=>'OK',
            'Oregon'=>'OR',
            'Pennsylvania'=>'PA',
            'Rhode Island'=>'RI',
            'South Carolina'=>'SC',
            'South Dakota'=>'SD',
            'Tennessee'=>'TN',
            'Texas'=>'TX',
            'Utah'=>'UT',
            'Vermont'=>'VT',
            'Virginia'=>'VA',
            'Washington'=>'WA',
            'West Virginia'=>'WV',
            'Wisconsin'=>'WI',
            'Wyoming'=>'WY',
        'District of Columbia' => 'DC');
    //mostly only use state and city but in case of no such city, we use county instead
    $elem  = explode("-", $loc_str);
    $state = "";
    $city  = "";
    $county = "";
    //Parse state and city names
    if (count($elem) >= 3) { //currently ignore extra info about location. Item 11 is an exception here!
        $state_index = trim(str_replace('State', '', str_replace('state', '', $elem[0])));
        if (!array_key_exists($state_index, $states)) {
            //default blacksburg:
            //return array('lat' => '37.23', 'long' => '-80.4178');
            return array();
        }
        $state  = $states[$state_index];
        $city   = trim($elem[2]);
        $county = trim(str_replace('County', '', $elem[1]));
    } else if (count($elem) == 2) {
        $state_index = trim(str_replace('State', '', str_replace('state', '', $elem[0])));
        if (!array_key_exists($state_index, $states)) {
            return array();
        } else {
            $state = $states[$state_index];
            $city  = strstr(trim($elem[1]), ' Indep.', true);
            if ($city == "")
                $city = trim($elem[1]);
        }
    } else {
        //Should send to log and to alert new format of location!
    }
    //Convert state and city to lat and long
    $result = array();
    $latlong_file = fopen('./plugins/Incite/zip_codes_states.csv', 'r') or die('no zip file!');
    while (($row = fgetcsv($latlong_file)) != FALSE) {
        //Just use the last result as our county guess
        if ($county == $row[5] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
        }
        //Just use the first result as our final result!
        if ($city == $row[3] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
            break;
        }
    }
    fclose($latlong_file);
    return $result;
}
function location_to_city_state_str($location_str)
{
    $elements = explode('-', $location_str);
    if (count($elements) < 2)
        $elements = explode(',', $location_str);
    if (count($elements) < 2)
        return $location_str; //I guess this is better than returning unknown places
    $state = trim(str_replace('State', '', str_replace('state', '', $elements[0])));
    $city = strstr(trim($elements[count($elements)-1]), ' Indep.', true);
    if (strlen($city) == 0)
        $city = trim($elements[count($elements)-1]);
    return $city.', '.$state;
}
function year_of_full_iso_date ($full_iso_date)
{
    $elements = explode('-', $full_iso_date);
    if (count($elements) != 3)
        return '???';
    return $elements[0];
}



?>
