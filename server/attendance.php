<?php
session_start();

require_once("./conn.php");

$attendance_data = $conn->execute_query("SELECT u.name, u.email, u.designation, u.company, c.date 
          FROM users u 
          JOIN checkin c ON u.id = c.user_id")->fetch_all(MYSQLI_ASSOC);

echo json_encode([
  "data" => $attendance_data
]);
