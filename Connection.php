<?php
$host = "localhost";
$port = 5432;  // Port should be an integer
$dbname = "Music";
$user = "postgres";
$password = "A35195lo0Z";

// Establish a connection
$dbcon = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$dbcon) {
    echo "Connection failed";
    die();
} else {
}
?>
