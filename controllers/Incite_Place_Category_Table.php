<?php

require_once("DB_Connect.php");

function createPlaceTag($city, $state, $longitude, $latitude)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_place_category VALUES (NULL, ?, ?, ?, ?)");
    $stmt->bind_param("ssdd", $city, $state, $longitude, $latitude);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function getPlaceOnCity($city)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_place_category WHERE UPPER(city) LIKE UPPER(%?%)");
    $stmt->bind_param("s", $city);
    $stmt->bind_result($placeObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $placeObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}

function getPlaceOnState($state)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_place_category WHERE UPPER(state) LIKE UPPER(%?%)");
    $stmt->bind_param("s", $state);
    $stmt->bind_result($placeObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $placeObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
/**
 * Get the place object based on the coordinates
 * The precision variable is a positive decimal number that marks the percentage of accuracy.
 * For example, if the precision variable was 10, then we would say the user wants 10% within accuracy of the longitude
 * and latitude.
 * @param type $longitude Longitude of the coordinate
 * @param type $latitude Latitude of the coordinate
 * @param type $precision %percision withn range or longitude and latitude
 */
function getPlaceOnCoordinates($longitude, $latitude, $precision)
{
    $longitudeLowerBound = $longitude - ($longitude * $precision)/100;
    $longitudeUpperBound = $longitude + ($longitude * $precision)/100;
    $latitudeLowerBound = $latitude - ($latitude * $precision)/100;
    $latitudeUpperBound = $latitude + ($latitude * $precision)/100;
    
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_place_category WHERE (longitude BETWEEN ? AND ?) AND (latitude BETWEEN ? AND ?)");
    $stmt->bind_param("dddd", $longitudeLowerBound, $longitudeUpperBound, $latitudeLowerBound, $latitudeUpperBound);
    $stmt->bind_result($placeObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $placeObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
function getPlaceOnId(int $id)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_place_category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($placeObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $placeObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
?>
