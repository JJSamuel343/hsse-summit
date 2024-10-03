<?php

require_once "./server/conn.php";

if (isset($_GET['ticket_number'])) {

  $user = $conn->execute_query("SELECT id, name, email, designation, company FROM users where ticket_number = ?", [$_GET['ticket_number']])->fetch_assoc();

  if (!isset($user)) {
    exit();
  }

  $conn->execute_query("INSERT INTO checkin (user_id, `date`) VALUES (?,NOW())", [$user['id']]);
  $user['id'] = null;
  unset($user['id']);


  header("Location: successfulscan.php?user=" . json_encode($user));
}
