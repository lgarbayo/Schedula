<?php
include "calendar.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Schedula</title>

        <meta name="description" content="The smart calendar that keeps your life organized—your way">
    
        <link href="https://fonts.googleapis.com/css2?family=GFS+Neohellenic:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="index.css"/>
    </head>

    <body onload="">

        <header>
            <h1>Schedula<br></h1>
        </header>

        <?php if ($successMsg): ?>
            <div class="alert success"><?= $successMsg ?></div>
        <?php elseif ($errorMsg): ?>
            <div class="alert error"><?= $errorMsg ?></div>
        <?php endif; ?>

        <!--clock-->
        <div class="clock-container">
            <div id="clk"></div>
        </div>

        <!--calendar-->
        <div class="calendar">
            <div class="nav-btn-container">
                <button onclick="changeMonth(-1)" class="nav-btn">
                    <img id="botonPREV" src="./icons/PREV.png"/>
                </button>
                <h2 id="monthYear" style="margin: 0"></h2>
                <button onclick="changeMonth(1)" class="nav-btn">
                    <img id="botonNEXT" src="./icons/NEXT.png"/>
                </button>
            </div>

            <div class="calendar-grid" id="calendar"></div>

        </div>

        <!--Modal for add/edit/delete/showcurrent/search appointment-->
        <div class="modal" id="eventModal">
            <div class="modal-content">

                <!--Dropdown Selector-->
                <div id="eventSelectorWrapper" style="display: none;">
                    <label for="eventSelector">
                        <strong>Select Event:</strong>
                    </label>
                    <select id="eventSelector" onchange="handleEventSelection(this.value)">
                        <option disabled selected>
                            Choose Event...
                        </option>
                    </select>
                </div>

                <!--Main Form-->
                <form method="POST" id="eventForm">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="event_id" id="eventId">

                    <label for="eventName">Event Name:</label>
                    <input type="text" name="event_name" id="eventName" required>

                    <label for="instructorName">Instructor Name:</label>
                    <input type="text" name="instructor_name" id="instructorName" required>

                    <label for="startDate">Start Date:</label>
                    <input type="date" name="start_date" id="startDate" required>

                    <label for="endDate">End Date:</label>
                    <input type="date" name="end_date" id="endDate" required>

                    <label for="startTime">Start Time:</label>
                    <input type="time" name="start_time" id="startTime" required>

                    <label for="endTime">End Time:</label>
                    <input type="time" name="end_time" id="endTime" required>

                    <label for="descriptionEvent">Description:</label>
                    <input type="text" name="descript_event" id="descriptionEvent" required>

                    <button type="submit">Save</button>
                </form>

                <!--Delete-->
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="event_id" id="deleteEventId">
                    <button type="submit" class="submit-btn">
                        <img id="botonDLT" src="./icons/DLT.png" onclick="" />
                    </button>
                </form>

                <!--Cancel-->
                <button type="button" class="submit-btn" onclick="closeModal()" style="background:#ccc">Cancel</button>
            </div>
        </div>

        <script>
            const events = <?=json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE)?>;
        </script>
        <script src="calendar.js"></script>
    </body>


</html>