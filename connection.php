<?php

//1.connect to docker
$conn = new mysqli("db", "user", "secret", "calendar");
$conn->set_charset("utf8mb4");