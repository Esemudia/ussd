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
        $state=[];
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
        } 
        elseif ($level == 2) {
            $query = 'SELECT state FROM state';
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $reps[] = $row['state'];
                    array_push($state,$row['state']);
                }
                    $response = "CON select location \n";
                    $response .= "1. {$reps[0]}\n";
                    $response .= "2. {$reps[1]}\n";
                    $response .= "3. {$reps[2]}\n";
                } else {
                    $response = "END No states found.";
                }
        } 
        elseif ($level == 3) {
            $var = $textarray[2];
            $int= $var-1;
            $query = "SELECT lga FROM location where state='{$state[$int]}'";
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            $i=1;
            if (count($result3) > 0) {
                $response = "CON select location \n";
                foreach ($result3 as $row) {
                    $response .=$i++. " " . $row['lga'];
                }
                   
                } else {
                    $response = "END No states found.";
                }
        } 
        
        else if ($level=4) {
                    $response = "CON Select sex \n";
                    $response .= "1. Male\n";
                    $response .= "2. {Female\n";
              
        }
        elseif ($level == 5) {
            $query = 'SELECT state FROM state';
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                foreach ($result3 as $row) {
                    $reps[] = $row['state'];
                }
                    $response = "CON select location \n";
                    $response .= "1. {$reps[0]}\n";
                    $response .= "2. {$reps[1]}\n";
                    $response .= "3. {$reps[2]}\n";
                } else {
                    $response = "END No states found.";
                }
        } 

        return $response;
    }
}




?>
