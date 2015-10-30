<?php

require_once("DB_Connect.php");

function getTypeOfCategoryId($string)
{
    //the values for the category table are preset
    if ($string == "person" || $string == "people")
    {
        return 1;
    }
    if ($string == "place")
    {
        return 2;
    }
}
?>
