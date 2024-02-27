<?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:microhub.database.windows.net,1433; Database = sdt", "CloudSAd65eb16b", "Sumanth@12345");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "CloudSAd65eb16b", "pwd" => "{your_password_here}", "Database" => "sdt", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:microhub.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?>