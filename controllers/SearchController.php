<?php
/**
 * API for searching
 */
require_once("DB_Connect.php");

/**
 * Get document ids based on a location
 * @param String $text
 * @return array of ids
 */
function getDocumentIdOnLocation($text)
{
    
}
/**
 * Get document ids based on a certain time-period
 * @param String $time
 * @return array of ids
 */
function getDocumentIdOnTime($time)
{
    
}
/**
 * Get document ids based on a keyword
 * @param String $keyword
 * @return array of ids
 */
function getDocumentIdOnKeyword($keyword)
{
    
}
/**
 * Search for documents based on location, time and keyword.
 * @param String $array --> format{text, time, keywords, action}
 * return Array of integers for each document id
 */
function search($array)
{
    if ($array[0] != "")
    {
        //search for location
    }
    if ($array[1] != "")
    {
        //search for time
    }
    if ($array[2] != "keywords")
    {
        //search for keyword
    }
}
?>