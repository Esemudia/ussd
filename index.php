<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include "config.php";
include_once "english.php";

// Read incoming request
$sessionId = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$text = $_POST['text'] ?? '';

$menu = new english($dbh);
$response = '';
$Myarray = [];
$reps = [];
$input = [];

try {
    $textarray = explode("*", $text);
    error_log("Text array: " . print_r($textarray, true)); // Log the text array

    if ($text == '') {
        $query = 'SELECT language FROM language';
        $stmt = $dbh->query($query);
        $results = $stmt->fetchAll();

        $response = "CON Please choose what you would like to check:\n";
        if (count($results) > 0) {
            $index = 1;
            foreach ($results as $row) {
                $input[] = $index;
                $response .= $index++ . ". " . $row["language"] . "\n";
            }
            $response .= '0. Cancel';
        }
    } else if ($text == '0') {
        $response = "END Your phone number is $phoneNumber";
    } else if ($textarray[0]) {
        $response = $menu->getmenu($textarray); // Ensure getmenu() returns a response
    }

} catch (Exception $e) {
    error_log("Error executing query: " . $e->getMessage());
    $response = 'END An error occurred. Please try again later. Details: ' . $e->getMessage();
}

header('Content-Type: text/plain');
echo $response;

?>
