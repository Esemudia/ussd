<?php

class Hausa {

    protected $dbh;
    protected $Myarray = [];
    
    public function __construct($dbh) {
        $this->dbh = $dbh;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function reporthausa($textarray) {
        $level = count($textarray);
        $response = '';
        $state = $_SESSION['state'] ?? [];
        $Myarray = $_SESSION['Myarray'] ?? [];

        error_log("Level: $level"); // Debugging statement

        if ($level == 1) {
            $query = "SELECT questions FROM question WHERE language='Hausa'";
            $stmt = $this->dbh->query($query);
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    $Myarray[] = $row['questions'];
                }
                $_SESSION['Myarray'] = $Myarray;
                $response = "CON {$Myarray[0]}\n";
                $response .= "1. iya\n";
                $response .= "2. a'a\n";
                
            } else {
                $response = "END No questions found for the selected language.";
            }
        } 
        elseif ($level == 2) {
            error_log("Processing Level 2"); // Debugging statement

            $query = "SELECT state FROM state";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $state[] = $row['state'];
                }
                $_SESSION['state'] = $state;
                if (isset($state[0], $state[1], $state[2])) {
                    $response = "CON zabi jiha :\n";
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
            error_log("Processing Level 3"); // Debugging statement

            $selectedIndex = $textarray[1] - 1; // Corrected index for selection
            if (isset($state[$selectedIndex])) {
                $selectedState = $state[$selectedIndex];
                $query = "SELECT lga FROM location WHERE state=?";
                $stmt = $this->dbh->prepare($query);
                $stmt->execute([$selectedState]);
                $result3 = $stmt->fetchAll();
                if (count($result3) > 0) {
                    $response = "CON zabi waje :\n";
                    $i = 1;
                    foreach ($result3 as $row) {
                        $response .= $i++ . ". " . $row['lga'] . "\n";
                    }
                } else {
                    $response = "END No LGAs found.";
                }
            } else {
                $response = "END Invalid state selection.";
            }
        } 
        elseif ($level == 4) {
            error_log("Processing Level 4"); // Debugging statement

            $response = "CON Zabi jinsi :\n";
            $response .= "1. namiji\n";
            $response .= "2. ta mace\n";
            $response .= "3. Sauran\n";
        } 
        elseif ($level == 5) {
            error_log("Processing Level 5"); // Debugging statement

            $query = "SELECT name FROM service_providers WHERE language='Hausa'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON Zabi irin taimakon da ake bukata:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['name'] . "\n";
                }
            } else {
                $response = "END No Service provider found.";
            }
        } else {
            $response = "END Invalid selection.";
        }

        return $response;
    }
}
?>
