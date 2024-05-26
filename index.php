<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include "config.php";

// Read incoming request
$sessionId = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$text = $_POST['text'] ?? '';

$response = '';
$Myarray = [];
$reps = [];

try {
    switch ($text) {
        case '':
            $query = 'SELECT language FROM language';
            $stmt = $dbh->query($query);
            $results = $stmt->fetchAll();

            $response = 'CON Please Chose  you like to check\n';
            if (count($results) > 0) {
                $index=0;
                foreach ($results as $row) {
                    $response .= "$index++. " . $row["language"] . "\n";
                    
                }
                $response .= '*. Cancel';
            }
            break;

        case '*':
            $response = "END Your phone number is $phoneNumber";
            break;

        case '1':
            $query = 'SELECT questions FROM question WHERE language="English"';
            $stmt = $dbh->query($query);
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    $Myarray[] = $row['questions'];
                }
                $response = "CON {$Myarray[0]}\n";
                $response .= "1. Yes\n";
                $response .= "2. No\n";

                // Additional nested logic for text == '1*1'
                if ($text === '1*1') {
                    $query = 'SELECT state FROM state';
                    $stmt = $dbh->query($query);
                    $result3 = $stmt->fetchAll();
                    if (count($result3) > 0) {
                        foreach ($result3 as $row) {
                            $reps[] = $row['state'];
                        }
                        $response = "CON {$Myarray[1]}\n";
                        $response .= "1. {$reps[0]}\n";
                        $response .= "2. {$reps[1]}\n";
                        $response .= "3. {$reps[2]}\n";

                        // Further logic for selecting location based on state
                        if ($text === '1') {
                            $locas = [];
                            $query = 'SELECT location FROM location WHERE state="' . $reps[0] . '"';
                            $stmt = $dbh->query($query);
                            $locations = $stmt->fetchAll();
                            foreach ($locations as $location) {
                                $locas[] = $location['location'];
                            }
                            // Add logic to process locations if needed
                        }
                    }
                }
            }
            break;

        // Add more cases as needed
    }
} catch (Exception $e) {
    error_log("Error executing query: " . $e->getMessage());
    $response = 'END An error occurred. Please try again later.';
}

header('Content-Type: text/plain');
echo $response;

?>
