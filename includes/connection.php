<?php
$servername = "us-cluster-east-01.k8s.cleardb.net";
$username = "bdbd8cf3909997"; 
$password = "ab26be31"; 
$dbname = "heroku_a206025092379f3";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
