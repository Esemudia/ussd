<?php 
// DB credentials.
define('DB_HOST', 'ep-divine-forest-a4gxgzpg-pooler.us-east-1.aws.neon.tech');
define('DB_USER', 'default');
define('DB_PORT', '5432');
define('DB_PASS', '8PQgLa5TYjKi');
define('DB_NAME', 'verceldb');
try {
    $dbh = new PDO("pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>