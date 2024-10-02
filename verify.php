<?php

require_once "./server/conn.php";

if (isset($_GET['ticket_number'])) {

  $user = $conn->execute_query("SELECT name, email, designation, company FROM users where ticket_number = ?", [$_GET['ticket_number']])->fetch_assoc();

  if (!isset($user)) {
    exit();
  }


  header("Location: successfulscan.php?user=" . json_encode($user));
}
