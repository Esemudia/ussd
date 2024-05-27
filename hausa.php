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

    public function getmenu($textarray) {
        $level = count($textarray);
        $response = '';
        $reps = [];
        $state = $_SESSION['state'] ?? [];
        $Myarray = $_SESSION['Myarray'] ?? [];
        $lga = [];

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
            $query = 'SELECT state FROM state';
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
            if ($textarray[2]== 1) { // Corrected index
                $query = "SELECT lga FROM location WHERE state='Lagos'";
                $stmt = $this->dbh->query($query);
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
            }
            if ($textarray[2]== 2){
                $query = "SELECT lga FROM location WHERE state='Abuja'";
                $stmt = $this->dbh->query($query);
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
            }
            if ($textarray[2]== 3){
                $query = "SELECT lga FROM location WHERE state='Adamawa'";
                $stmt = $this->dbh->query($query);
                $result3 = $stmt->fetchAll();
                if (count($result3) > 0) {
                    $response = "CON  zabi waje :\n";
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
            $response = "CON Zabi jinsi :\n";
            $response .= "1. namiji\n";
            $response .= "2. ta mace\n";
            $response .= "3. Sauran\n";
        } 
        elseif ($level == 5) {
            $query = "SELECT *- FROM service_providers where language='Hausa'";
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
        }

        return $response;
    }
}

?>
