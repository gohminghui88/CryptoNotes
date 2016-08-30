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

$title = preg_replace("/[^a-zA-Z]+/", "", $_GET['title']);
$date = date("Y/m/d");
$notes = $_GET['notes'];

//Validate User first
$sql = "SELECT * FROM users WHERE Email = '".$email."' AND PASSWORDS = '".$passwords."'";
$result = $conn->query($sql);

//If success, insert notes
if ($result->num_rows > 0) {
	$sql = "INSERT INTO notes(Title, Date, Email, Content) VALUES ('".$title."', '".$date."', '".$email."', '".encrypt($notes, $passwords)."')";

	if ($conn->query($sql) === TRUE) {
		//echo "New note created successfully";
		//echo '<script language="javascript">';
		//echo 'alert("New Notes Created. ")';
		//echo '</script>';
	
		//redirect("/CryptoNotes/CryptoNotes.php?email=".$email."&pass=".$passwords);
		redirect("/cryptonotes/CryptoNotes.php");
		
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
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


//Reference: http://php.net/manual/en/book.mcrypt.php
//AES Encryption/Decryption
function encrypt($decrypted, $pass, $salt='!askdghadksjh!!') { 
	// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
	$key = hash('SHA256', $salt . $pass, true);
	// Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
	srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
	
	if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
	
	// Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
	$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
	
	// We're done!
	return $iv_base64 . $encrypted;
} 

function decrypt($encrypted, $pass, $salt='!askdghadksjh!!') {
	// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
	$key = hash('SHA256', $salt . $pass, true);
	// Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
	$iv = base64_decode(substr($encrypted, 0, 22) . '==');
	// Remove $iv from $encrypted.
	$encrypted = substr($encrypted, 22);
	// Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
	// Retrieve $hash which is the last 32 characters of $decrypted.
	$hash = substr($decrypted, -32);
	// Remove the last 32 characters from $decrypted.
	$decrypted = substr($decrypted, 0, -32);
	// Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
	if (md5($decrypted) != $hash) return false;
	// Yay!
	return $decrypted;
}

?>
