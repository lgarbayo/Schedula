<?php

//1.connect to local mysql server (using xampp or mamp)
$username = "root";
$conn = new mysqli("localhost", $username, "", "calendar");
$conn->set_charset("utf8mb4");