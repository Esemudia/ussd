<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "config.php";
include_once "english.php";
include_once "hausa.php";


session_start();


$sessionId = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$text = $_POST['text'] ?? '';

$menu = new English($dbh);
$hausa= new Hausa($dbh);
$response = '';

try {
    $textarray = explode("*", $text);
    if ($text == '') {
        $query = 'SELECT language FROM language';
        $stmt = $dbh->query($query);
        $results = $stmt->fetchAll();

        $response = "CON Please choose your language:\n";
        if (count($results) > 0) {
            $index = 1;
            foreach ($results as $row) {
                $response .= $index++ . ". " . $row["language"] . "\n";
            }
            $response .= '0. Cancel';
        }
    } 
    else if ($text == '0') {
        $response = "END Thanks you for your feedback";
    } 
    else if($text=='1'){
       $response= english($textarray);
    }
    else if($text=='2'){
        $response = $hausa->reporthausa($textarray);
    }
} 
catch (Exception $e) {
    error_log("Error executing query: " . $e->getMessage());
    $response = 'END An error occurred. Please try again later. Details: ' . $e->getMessage();
}

public function english($textarray) {
    $level = count($textarray);
    $response = '';
    $reps = [];
    $state = $_SESSION['state'] ?? [];
    $Myarray = $_SESSION['Myarray'] ?? [];
    $lga = [];

    if ($level == 1) {
        $query = "SELECT questions FROM question WHERE language='English'";
        $stmt = $this->dbh->query($query);
        $results = $stmt->fetchAll();

        if (count($results) > 0) {
            foreach ($results as $row) {
                $Myarray[] = $row['questions'];
            }
            $_SESSION['Myarray'] = $Myarray;
            $response = "CON {$Myarray[0]}\n";
            $response .= "1. Yes\n";
            $response .= "2. No\n";
            
        } else {
            $response = "END No questions found for the selected language.";
        }
    } 
    elseif ($level == 2) {
        $query = "SELECT state FROM state";
        $stmt = $this->dbh->query($query);
        $result3 = $stmt->fetchAll();
        if (count($result3) > 0) {
            foreach ($result3 as $row) {
                $state[] = $row['state'];
            }
            $_SESSION['state'] = $state;
            if (isset($state[0], $state[1], $state[2])) {
                $response = "CON Select state your language:\n";
                $response .= "1. {$state[0]}\n";
                $response .= "2. {$state[1]}\n";
                $response .= "3. {$state[2]}\n";
            } else {
                $response = "END Not enough states found.";
            }
        } else {
            $response = "END No states found.";
        }
    } 
    elseif ($level == 3) {
        if ($textarray[2]== 1) { // Corrected index
            $query = "SELECT lga FROM location WHERE state='Lagos'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON Select location:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['lga'] . "\n";
                }
            } else {
                $response = "END No LGAs found.";
            }
        }
        if ($textarray[2]== 2){
            $query = "SELECT lga FROM location WHERE state='Abuja'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON Select location:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['lga'] . "\n";
                }
            } else {
                $response = "END No LGAs found.";
            }
        }
        if ($textarray[2]== 3){
            $query = "SELECT lga FROM location WHERE state='Adamawa'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON  Select location:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['lga'] . "\n";
                }
            } else {
                $response = "END No LGAs found.";
            }
        }
    } 
    elseif ($level == 4) {
        $response = "CON Select sex:\n";
        $response .= "1. Male\n";
        $response .= "2. Female\n";
    } 
    elseif ($level == 5) {
        $query = "SELECT *- FROM service_providers";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON Select service provider:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['name'] . "\n";
                }
            } else {
                $response = "END No Service provider found.";
            }
    }

    return $response;
}

header('Content-Type: text/plain');
echo $response;

?>
