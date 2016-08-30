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

$sql = "SELECT * FROM users WHERE Email = '".$email."' AND PASSWORDS = '".md5($passwords)."'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_start();
	#session_register($username);
	#session_register($password); 
	$_SESSION['email']= $email;
	$_SESSION['pass']= md5($passwords);
	
	//setcookie("email", $email, time() + (86400 * 30), "/"); // 86400 = 1 day
	//setcookie("pass", md5($passwords), time() + (86400 * 30), "/"); // 86400 = 1 day

    //redirect("http://www.svbook.com/CryptoNotes/CryptoNotes.php?email=".$email."&pass=".md5($passwords));
	//redirect("/CryptoNotes/CryptoNotes.php?email=".$email."&pass=".md5($passwords));
	redirect("/cryptonotes/CryptoNotes.php");
	//header("Location: /CryptoNotes/CryptoNotes.php");
	
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
