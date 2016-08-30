<?php

$servername = "localhost";
$username = "Your_Database_Username";
$password = "Your_Database_Passwords";
$dbname = "Your_Database_Name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

session_start();

$email = "";
$passwords = "";
if (isset($_SESSION['email'])) {$email = $_SESSION['email']; }
if (isset($_SESSION['pass'])) { $passwords = $_SESSION['pass']; }

$title = $_GET['title'];
//$email = $_GET['email'];
//$passwords = $_GET['pass'];
//$date = date("Y/m/d");
//$notes = $_GET['notes'];

//$sql = "INSERT INTO Notes(Title, Date, Email, Content) VALUES ('".$title."', '".$date."', '".$email."', '".$notes."')";

//Validate User first
$sql = "SELECT * FROM users WHERE Email = '".$email."' AND PASSWORDS = '".$passwords."'";
$result = $conn->query($sql);

//If success, insert notes
if ($result->num_rows > 0) {
	$sql = "DELETE FROM notes WHERE Title='".$title."' AND Email='".$email."'";

	if ($conn->query($sql) === TRUE) {
		//echo '<script language="javascript">';
		//echo 'alert("Selected Notes Deleted. ")';
		//echo '</script>';
	
		redirect("/cryptonotes/CryptoNotes.php");
	} else {
		echo "Error deleting Notes: " . $conn->error;
	}

} else {
    echo "Invalid Login";
}

$conn->close();

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
?>