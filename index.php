<?php

include "config.php";
$sessionId = $_POST['sessionId'];
$serviceCode = $_POST['serviceCode'];
$phoneNumber = $_POST['phoneNumber'];
$text = $_POST['text'];


$Myarray = [];
$reps = [];

// if ($text=="") {
//     $index = 1;
//         try {
//             $query = 'SELECT * FROM language';
//             $stmt = $dbh->query($query);
//             $results = $stmt->fetchAll();

//             if (count($results) > 0) {
//                 foreach ($results as $row) {
//                     if ($row['id'] === 'Language') {
//                         $response = 'CON What would you like to check\n';
//                         $keys = array_keys($row);
//                         for ( $index < count($keys); $index++) {
//                             $response .= "$index. " . $keys[$index] . "\n";
//                         }
//                         $response .= '*. Cancel';
//                     }
//                 }
//             }
//         } catch (Exception $e) {
//             error_log("Error executing query: " . $e->getMessage());
//             $response = 'END An error occurred. Please try again later.';
//         }
//    if($text== $index)
//         try {
//             $query = 'SELECT questions FROM question WHERE language="English"';
//             $stmt = $dbh->query($query);
//             $results = $stmt->fetchAll();

//             if (count($results) > 0) {
//                 foreach ($results as $row) {
//                     $Myarray[] = $row['questions'];
//                 }
//                 $response = "CON {$Myarray[0]}\n";
//                 $response .= "1. Yes\n";
//                 $response .= "2. No\n";

//                 // Additional nested logic for text == '1*1'
//                 if ($text === '1*1') {
//                     $query = 'SELECT state FROM state';
//                     $stmt = $dbh->query($query);
//                     $result3 = $stmt->fetchAll();
//                     if (count($result3) > 0) {
//                         foreach ($result3 as $row) {
//                             $reps[] = $row['state'];
//                         }
//                         $response = "CON {$Myarray[1]}\n";
//                         $response .= "1. {$reps[0]}\n";
//                         $response .= "2. {$reps[1]}\n";
//                         $response .= "3. {$reps[2]}\n";

//                         // Further logic for selecting location based on state
//                         if ($text === '1') {
//                             $locas=[];
//                             $query = 'SELECT location FROM location WHERE state="' . $reps[0] . '"';
//                             $stmt = $dbh->query($query);
//                             $locations = $stmt->fetchAll();
//                             for
//                         }
//                     }
//                 }
//             }
//         } catch (Exception $e) {
//             error_log("Error executing query: " . $e->getMessage());
//             $response = 'END An error occurred. Please try again later.';
//         }
//     // Add more cases as needed

// }
// elseif ($text=="*") {
//     $response = 'END An error occurred. Please try again later.';
// }
// header('Content-Type: text/plain');
// echo $response;

// Read the variables sent via POST from our API
// $sessionId   = $_POST["sessionId"];
// $serviceCode = $_POST["serviceCode"];
// $phoneNumber = $_POST["phoneNumber"];
// $text        = $_POST["text"];

if ($text == "") {
    $response = 'CON What would you like to check\n';
    $query = 'SELECT language FROM language';
            $stmt = $dbh->query($query);
            $results = $stmt->fetchAll();
            if (count($results) > 0) {
                foreach ($results as $row) {
                    if ($row['id'] === 'Language') {
                        
                        $keys = array_keys($row);
                        for ($index=0; $index < count($keys); $index++) {
                            $response .= "$index. +1" . $keys[$index] . "\n";
                        }
                        $response .= '*. Cancel';
                    }
                }
            }

} else if ($text == "1") {
    // Business logic for first level response
    $response = "CON Choose account information you want to view \n";
    $response .= "1. Account number \n";

} else if ($text == "2") {
    // Business logic for first level response
    // This is a terminal request. Note how we start the response with END
    $response = "END Your phone number is ".$phoneNumber;

} else if($text == "1*1") { 
    // This is a second level response where the user selected 1 in the first instance
    $accountNumber  = "ACC1001";

    // This is a terminal request. Note how we start the response with END
    $response = "END Your account number is ".$accountNumber;

}

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;

?>
