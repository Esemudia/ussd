<?php

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
        $_SESSION['statearray'] = $_SESSION['statearray'] ?? [];

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
                    $this->state[] = $row['state'];
                }
                $_SESSION['statearray'] = $this->state;

                $response = "CON Select your state:\n";
                for ($i = 0; $i < count($this->state); $i++) {
                    $response .= ($i + 1) . ". {$this->state[$i]}\n";
                }
            } else {
                $response = "END No states found.";
            }
        } elseif ($level == 3) {
            error_log("Processing Level 3"); // Debugging statement
            if (isset($_SESSION['statearray']) && count($_SESSION['statearray']) > 0) {
                $retarray = $_SESSION['statearray'];
                $val = intval($textarray[2]) - 1;
                if (isset($retarray[$val])) {
                    $query = "SELECT lga FROM location WHERE state=?";
                    $stmt = $this->dbh->prepare($query);
                    $stmt->execute([$retarray[$val]]);
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
            } else {
                $response = "END State array not found.";
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
