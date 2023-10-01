<?php
function connectDB() {
    $login = $_ENV["DB_USER"];
    $pass = $_ENV["DB_PASS"];
    $server = $_ENV["DB_HOST"];
    $name_db = $_ENV["DB_NAME"];
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $link = mysqli_connect($server, $login, $pass, $name_db);
    mysqli_set_charset($link, 'utf8mb4');
    return $link;
}
?>
