<?php

class english {

    protected $dbh;
    protected $Myarray = [];
    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function getmenu($textarray) {
        $level = count($textarray);
       // $Myarray = [];
        $response = '';
        $reps = [];
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
                    $reps[] = $row['state'];
                }
                if (isset($this->Myarray[1])) {
                    $response = "CON {$this->Myarray[1]}\n";
                    $response .= "1. {$reps[0]}\n";
                    $response .= "2. {$reps[1]}\n";
                    $response .= "3. {$reps[2]}\n";
                } else {
                    $response = "END No further questions found.";
                }
            } else {
                $response = "END No states found.";
            }
        }

        return $response;
    }
}

?>
