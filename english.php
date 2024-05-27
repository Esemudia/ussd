<?php

class English {
    protected $dbh;
    protected $Myarray = [];
    protected $state = [];

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function getmenu($textarray) {
        $level = count($textarray);
        $response = '';
        $reps = [];
        $lga = [];

        if ($level == 1) {
            $query = "SELECT questions FROM question WHERE language='English'";
            $stmt = $this->dbh->query($query);
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    $this->Myarray[] = $row['questions'];
                }
                $response = "CON {$this->Myarray[0]}\n";
                $response .= "1. Yes\n";
                $response .= "2. No\n";
            } else {
                $response = "END No questions found for the selected language.";
            }
        } elseif ($level == 2) {
            $query = 'SELECT state FROM state';
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $this->state[] = $row['state'];
                }
                $response = "CON Select location:\n";
                $response .= "1. {$this->state[0]}\n";
                $response .= "2. {$this->state[1]}\n";
                $response .= "3. {$this->state[2]}\n";
            } else {
                $response = "END No states found.";
            }
        } elseif ($level == 3) {
            $var = $textarray[2];
            $int = $var - 1;
            $query = "SELECT lga FROM location WHERE state='{$this->state[$int]}'";
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
        } elseif ($level == 4) {
            $response = "CON Select sex:\n";
            $response .= "1. Male\n";
            $response .= "2. Female\n";
        } elseif ($level == 5) {
            // Additional logic for level 5
        }

        return $response;
    }
}

?>
