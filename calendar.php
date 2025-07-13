<?php

//connect to the db calendar
include "connection.php";
$successMsg= "";
$errorMsg= "";
$eventsFromDB = []; //initialize a new array to store the fetched events

//handle add appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"]??'') === "add") {
    $event = trim($_POST["event_name"] ?? '');
    $instructor = trim($_POST["instructor_name"] ?? '');
    $start = $_POST["start_date"] ?? '';
    $end = $_POST["end_date"] ?? '';
    $startTime = $_POST["start_time"] ?? '';
    $endTime = $_POST["end_time"] ?? '';
    $description = trim($_POST["descript_event"] ?? '');

    if ($event && $instructor && $start && $end) {
        $stmt = $conn->prepare(
            "INSERT INTO events (event_name, instructor_name, start_date, end_date, start_time, end_time, descript_event) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssssss", $event, $instructor, $start, $end, $startTime, $endTime, $description);
        
        $stmt->execute();

        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
        exit;
    }
}

//handle edit appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"]??'') === "edit") {
    $id = $_POST["event_id"] ?? null;
    $event = trim($_POST["event_name"] ?? '');
    $instructor = trim($_POST["instructor_name"] ?? '');
    $start = $_POST["start_date"] ?? '';
    $end = $_POST["end_date"] ?? '';
    $startTime = $_POST["start_time"] ?? '';
    $endTime = $_POST["end_time"] ?? '';
    $description = trim($_POST["descript_event"] ?? '');

    if ($id && $event && $instructor && $start && $end) {
        $stmt = $conn->prepare(
            "UPDATE events SET event_name = ?, instructor_name = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ?, descript_event = ? WHERE id = ?"
        );

        $stmt->bind_param("sssssssi", $event, $instructor, $start, $end, $startTime, $endTime, $description, $id);
        
        $stmt->execute();

        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=2");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=2");
        exit;
    }
}

//handle delete appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"]??'') === "delete") {
    $id = $_POST["event_id"] ?? null;

    if ($id) {
        $stmt = $conn->prepare(
            "DELETE FROM events WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        
        $stmt->execute();

        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=3");
        exit;
    }
}

//success/error msgs
if (isset($_GET["success"])) {
    $successMsg = match($_GET["success"]) {
        '1' => "Appointment added succesfully",
        '2' => "Appointment updated succesfully",
        '3' => "Appointment deleted succesfully",
        default => ""
    };
}

if (isset($_GET["error"])) {
    $errorMsg ="Error occured. Please check your input.";
}

//fetch all appointments and spread over date range
$result = $conn->query("SELECT * FROM events");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row["start_date"]);
        $end = new DateTime($row["end_date"]);

        while ($start <= $end) {
            $eventsFromDB[] = [
                'id' => $row["id"],
                "title" => $row['event_name'],
                "date" => $start->format('d-m-y'),
                "start" => $row['start_date'],
                "end" => $row['end_date'],
                "start_time" => $row['start_time'],
                "end_time" => $row['end_time'],
                "description" => $row['descript_event']
            ];

            $start->modify('+1 day');
        }
    }
}

$conn->close();

?>