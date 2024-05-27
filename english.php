<?php

class english {

    protected $dbh;
    protected $text;
    protected $sessionId;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function getmenu($textarray) {
        $level = count($textarray);
        $Myarray = [];
        $response = '';

        if ($level == 1) {
            $query = "SELECT questions FROM question WHERE language='English'";
            $stmt = $this->dbh->query($query);
            $results = $stmt->fetchAll();

            if (count($results) > 0) {
                foreach ($results as $row) {
                    $Myarray[] = $row['questions'];
                }
                $response = "CON {$Myarray[0]}\n";
                $response .= "1. Yes\n";
                $response .= "2. No\n";
            }
        } elseif ($level == 2) {
            $query = 'SELECT state FROM state';
            $stmt = $this->dbh->query($query);
            $result3 = $stmt->fetchAll();
            if (count($result3) > 0) {
                $id = 1;
                foreach ($result3 as $row) {
                    $reps[] = $row['state'];
                }
                $response = "CON {$Myarray[1]}\n";
                $response .= "1. {$reps[0]}\n";
                $response .= "2. {$reps[1]}\n";
                $response .= "3. {$reps[2]}\n";
            }
        }

        return $response;
    }
}
?>
