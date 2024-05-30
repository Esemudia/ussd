<?php
session_start();
$_SESSION['statearray'];
class English {

    protected $dbh;
    protected $Myarray = [];
    protected $state = [];

    public function __construct($dbh) {
        $this->dbh = $dbh;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getmenu($textarray) {
        $level = count($textarray);
        $response = '';
        $Myarray = $_SESSION['Myarray'] ?? [];
        $state = [];

        error_log("Level: $level"); // Debugging statement

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
        } elseif ($level == 2) {
            error_log("Processing Level 2"); // Debugging statement
            $query = "SELECT state FROM state";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $state= $row['state'];
                }
                $_SESSION['statearray'] = $state;
                if (isset($state[0], $state[1], $state[2])) {
                    $response = "CON Select your state:\n";
                    $response .= "1. {$state[0]}\n";
                    $response .= "2. {$state[1]}\n";
                    $response .= "3. {$state[2]}\n";
                } else {
                    $response = "END Not enough states found.";
                }
            } else {
                $response = "END No states found.";
            }
        } elseif ($level == 3) {
            error_log("Processing Level 3"); // Debugging statement
            if (isset($_SESSION['statearray'])) { // Corrected index
                $retarray=$_SESSION['statearray'];
                $val=$textarray[2]-1;
                $query = "SELECT lga FROM location WHERE state='$retarray[$val]'";
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
        } elseif ($level == 4) {
            error_log("Processing Level 4"); // Debugging statement
            $response = "CON Select sex:\n";
            $response .= "1. Male\n";
            $response .= "2. Female\n";
        } elseif ($level == 5) {
            error_log("Processing Level 5"); // Debugging statement
            $query = "SELECT name FROM service_providers WHERE language='English'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $response = "CON Select service provider:\n";
                $i = 1;
                foreach ($result3 as $row) {
                    $response .= $i++ . ". " . $row['name'] . "\n";
                }
            } else {
                $response = "END No service providers found.";
            }
        } elseif ($level == 6) {
            $response = "CON Select:\n";
            $response .= "1. Submit";
        } elseif ($level == 7) {
            $response = "END Thank you for your response.\n";
        } else {
            $response = "END Invalid selection.";
        }

        return $response;
    }
}
?>
