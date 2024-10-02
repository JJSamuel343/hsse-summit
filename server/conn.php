<?php


$dbname = "synapzem_hsse_summit_2024";
$username = "synapzem_hsse_summit_2024";
$servername = "localhost";
$password = "5iR8nTnK8HHfciB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
