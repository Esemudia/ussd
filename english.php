<?php

class English {

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
            $query = 'SELECT state FROM state';
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $state[] = $row['state'];
                }
                $_SESSION['state'] = $state;
                if (isset($state[0], $state[1], $state[2])) {
                    $response = "CON Select location:\n";
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
            if (isset($state[$textarray[1] - 1])) { // Corrected index
                $stateSelected = $state[$textarray[1] - 1];
                $query = "SELECT lga FROM location WHERE state='$stateSelected'";
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
            } else {
                $response = "END Invalid state selection.";
            }
        } 
        elseif ($level == 4) {
            $response = "CON Select sex:\n";
            $response .= "1. Male\n";
            $response .= "2. Female\n";
        } 
        elseif ($level == 5) {
            // Additional logic for level 5
        }

        return $response;
    }
}

?>
