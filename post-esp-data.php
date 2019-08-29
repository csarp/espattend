<?php
$servername = "localhost";
// REPLACE with your Database name
$dbname = "nodemculog";
// REPLACE with Database user
$username = "nodemculog";
// REPLACE with Database user password
$password = "password";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "Random-Api-Key-Password";

$api_key= $sensor = $location = $value1 = $value2 = $value3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $rfid = test_input($_POST["rfid"]);
        /*$location = test_input($_POST["location"]);
        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]); */
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO rfid_user (rfid)
        VALUES ('" . $rfid . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkInDB($id){
 //εύρεση τελευταίας εγγραφής του συγκεκριμένου RFID την τρέχουσα ημέρα
  $query2 = "SELECT rfiddata.* FROM rfid_user rfiddata INNER JOIN (SELECT max(date) as max_ts, rfid FROM rfid_user WHERE rfid='".$id."' GROUP BY rfid) latest_take ON rfiddata.date=latest_take.max_ts and rfiddata.rfid=latest_take.rfid and DATE(rfiddata.date)=CURDATE()";
  $result2 = mysqli_query($this->link,$query2) or die('Errant query:  '.$query2);
 	if (mysqli_num_rows($result2) != 0)
		{
//results found

		} else {
// results not found
			//$this->storeInDBmesa($id);
		} 
  
}