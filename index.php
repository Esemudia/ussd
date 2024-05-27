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

$response = '';
$Myarray = [];
$reps = [];
$input = [];

try {
    $textarray=explode("*",$text);
    $level=$textarray;
    if ($text=='') {
       
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
                $response .= '0  Cancel';
            }
        }

       else if ($text=='0') {
            $response = "END Your phone number is $phoneNumber";
        }
       else if ($level==1) {
            $query = "SELECT questions FROM question WHERE language='English'";
            $stmt = $dbh->query($query);
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    $Myarray[] = $row['questions'];
                }
                $response = "CON {$Myarray[0]}\n";
                $response .= "1. Yes\n";
                $response .= "2. No\n";            
                
            }
    }

    elseif ($level==2) {
        $query = 'SELECT state FROM state';
        $stmt = $dbh->query($query);
        $result3 = $stmt->fetchAll();
        if (count($result3) > 0) {
            $id=1;
            foreach ($result3 as $row) {
                $reps[] = $id++ => $row['state'];
            }
            $response = "CON {$Myarray[1]}\n";
            $response .= "1. {$reps[0]}\n";
            $response .= "2. {$reps[1]}\n";
            $response .= "3. {$reps[2]}\n";

    }
   
} catch (Exception $e) {
    error_log("Error executing query: " . $e->getMessage());
    $response = 'END An error occurred. Please try again later. Details: ' . $e->getMessage();
}



header('Content-Type: text/plain');
echo $response;

?>
