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

$email = $_GET['email'];
$passwords = $_GET['pass'];


$sql = "SELECT * FROM Users WHERE Email = '".$email."'";
$result = $conn->query($sql);

//If email not exists
if ($result->num_rows == 0) {
	$sql = "INSERT INTO users(Email, Passwords) VALUES ('".$email."', '".md5($passwords)."')";

	if ($conn->query($sql) === TRUE) {
		//echo "New user created successfully";
		//echo '<script language="javascript">';
		//echo 'alert("New User Created. ")';
		//echo '</script>';
	
		redirect("/cryptonotes/index.php");
	
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

else {
    echo "User Existed. ";
}

$conn->close();



function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
?>
