<!doctype html>
<html lang="en">

<?php

require_once "./server/conn.php";

if (!isset($_GET['user'])) {
  exit();
}

$user = json_decode($_GET['user'], true);
$name = $user['name'];
$designation = $user['designation'];
$company = $user['company'];


?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HSSE Partners Summit 2024</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets\css\main.css">
</head>

<body>
  <div class="container-fluid imgbg section">
    <div class="row section justify-content-center align-content-center">
      <div class="col-8 col-sm-6 col-lg-3 hideori fadein">
        <img src="assets\img\middlealignlogo.png" class="standardimg" alt="">
      </div>
      <div class="col-12"></div>
      <div class="col-5 text-center mt-4   hideori fadein2 ">
        <h4>Welcome</h4>
      </div>
      <div class="col-12"></div>
      <div class="col-6 text-center mt-4 mb-4">
        <h1 class="hideori fadein3"><?php $name ?></h1>
        <h2 class=" hideori fadein4"><?php $designation ?> <?php $company ?></h2>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>