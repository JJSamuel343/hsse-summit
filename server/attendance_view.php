<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
  // If not authenticated, redirect to the login page
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Attendance</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables CSS & JS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- DataTables Export Buttons CSS & JS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

  <style>
    table {
      margin: 20px auto;
      width: 80%;
    }
  </style>
</head>

<body>
  <h2 style="text-align: center;">User Attendance</h2>

  <table id="attendanceTable" class="display nowrap" style="width:100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Designation</th>
        <th>Company</th>
        <th>Check-In Date</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data will be loaded by DataTables -->
    </tbody>
  </table>

  <script>
    $(document).ready(function() {
      $('#attendanceTable').DataTable({
        "ajax": "attendance.php", // Data source
        "columns": [{
            "data": "name"
          },
          {
            "data": "email"
          },
          {
            "data": "designation"
          },
          {
            "data": "company"
          },
          {
            "data": "date"
          }
        ],
        "dom": 'Bfrtip', // Add export buttons
        "buttons": [
          'csv', 'excel', 'print'
        ]
      });
    });
  </script>
</body>

</html>