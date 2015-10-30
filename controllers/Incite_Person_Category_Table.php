<?php

require_once("DB_Connect.php");

function createPersonTag($name, $birth_date, $death_date, $occupation, $known_for, $isFamous)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_people_category VALUES (NULL, ?, ?, ?, ?, ?, ?");
    $stmt->bind_param("sssssi", $name, $birth_date, $death_date, $occupation, $known_for, $isFamous);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function getPersonByName($name)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE UPPER(name) LIKE UPPER(%?%)");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}

function getPersonByDate($date)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE UPPER(birth_date) LIKE UPPER(%?%) OR UPPER(death_date) LIKE UPPER(%?%)");
    $stmt->bind_param("ss", $date, $date);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
function getPersonByOccupation($occupation)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE UPPER(occupation) LIKE UPPER(%?%)");
    $stmt->bind_param("s", $occupation);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
    
}

function getPersonByKnownFor($knownFor)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE UPPER(known_for) LIKE UPPER(%?%)");
    $stmt->bind_param("s", $knownFor);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}

function getPersonIfFamous(bool $isFamous)
{
    $bool = 0;
    if ($isFamous)
    {
        $bool = 1;
    }
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE is_famous = ?");
    $stmt->bind_param("i", $bool);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
function getPersonOnId(int $id)
{
    $result = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_people_category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($personObject);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $personObject;
    }
    $stmt->close();
    $db->close();
    return $result;
}
?>
