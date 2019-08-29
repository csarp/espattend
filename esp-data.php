<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">	
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<title>Καταγραφή Προσωπικού</title>
<!-- <script src="js/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    setInterval(function(){
      $.ajax({
        url: "load-users.php"
        }).done(function(data) {
        $('#cards').html(data);
      });
    },3000);
  });
</script> -->
</head>
<body>
<?php
$servername = "localhost";
// REPLACE with your Database name
$dbname = "nodemculog";
// REPLACE with Database user
$username = "nodemculog";
// REPLACE with Database user password
$password = "PASSWORD";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// Change character set to utf8
mysqli_set_charset($conn,"utf8");

//$sql = "SELECT id, rfid, date FROM rfid_user ORDER BY date DESC";
$sql = "SELECT rfid_user.id, rfid_user.rfid, employee.eponymo, employee.onoma, employee.tmima, rfid_user.date FROM rfid_user, employee where rfid_user.rfid = employee.rfid and DATE(rfid_user.date) = CURDATE() ORDER BY rfid_user.date DESC";
//SELECT rfid_user.id, rfid_user.rfid, eponymo, onoma, tmima, DATE_FORMAT(rfid_user.date, '%Y-%m-%d') FROM rfid_user, employee where rfid_user.rfid = employee.rfid and DATE(rfid_user.date) = CURDATE()
//SELECT rfid_user.id, rfid_user.rfid, eponymo, onoma, tmima, date FROM rfid_user, employee where rfid_user.rfid = employee.rfid ORDER BY date DESC
//
echo '<div class="container">';
echo '<h2>Ημερήσια Καταγραφή Προσωπικού</h2>';
echo '<table class="table table-hover" cellspacing="5" cellpadding="5">
      <tr> 
        <th scope="col">ID</th> 
        <th scope="col">Αρ. Κάρτας</th> 
        <th scope="col">Επώνυμο</th> 
        <th scope="col">Όνομα</th>
        <th scope="col">Τμήμα</th> 
        <th scope="col">Ημερομηνία</th> 
        <th scope="col">Ώρα</th> 
      </tr>';
 
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_rfid = $row["rfid"];
    	$row_eponymo = $row["eponymo"];
    	$row_onoma = $row["onoma"];
    	$row_tmima = $row["tmima"];
        $row_reading_time = $row["date"];
    	$timestamp = strtotime($row['date']);
    	list($date, $time) = explode('|', date('d/m/Y|H:i:s', $timestamp));
        echo '<tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_rfid . '</td>
                <td>' . $row_eponymo . '</td>
                <td>' . $row_onoma . '</td>
                <td>' . $row_tmima . '</td>
                <td>' . $date . '</td> 
                <td>' . $time . '</td> 
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>
<!-- <div id="cards" class="cards"></div> -->
</body>
</html>