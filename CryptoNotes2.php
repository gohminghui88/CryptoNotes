<html>
<head>
	<title>My CryptoNotes</title>
</head>

<body>

<?php
error_reporting(E_ALL ^ E_WARNING); 

session_start();

$email = "";
$passwords = "";
if (isset($_SESSION['email'])) {$email = $_SESSION['email']; }
if (isset($_SESSION['pass'])) { $passwords = $_SESSION['pass']; }

$title = $_GET['title'];
$notes = "";

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

//$email = $_GET['email'];

$sql = "SELECT * FROM notes WHERE Email = '".$email."'";

$result = $conn->query($sql);

$sql = "SELECT * FROM notes WHERE Email = '".$email."' AND Title = '".$title."'";
$curNoteResult = $conn->query($sql);

$conn->close();

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

<table border="1">

<tr>
<td><h3>Existing Notes</h3></td>
<td><h3>Add New Notes</h3></td>
<td><a href="/cryptonotes/logout.php">Log out</a></td>
</tr>

<tr>
	<td>
		<a href="/cryptonotes/CryptoNotes.php">Add New</a><br /><br />
		<?php
				if ($result->num_rows > 0) {
					
					while($row = $result->fetch_assoc()) {
						echo "<a href='/cryptonotes/CryptoNotes2.php?title=" . $row["Title"]."'>" . $row["Title"] . "</a><br /> ";
					}
				} 
				
				else {
					echo "No Notes";
				}

		?>
	</td>
	<td>
	
		<?php
				if ($curNoteResult->num_rows > 0) {
					
					while($row = $curNoteResult->fetch_assoc()) {
						$notes = decrypt($row["Content"], $passwords);
						$date = $row["Date"];
					}
				} 
		?>
		
		
		<form name="myForm" action="UpdateNotes.php" method="get" onsubmit="return validateForm()">
			
			Date Created: <?php echo $date; ?><br />
			Title: <input type="text" name="title2" value="<?php echo $title;?>"><br />
			<textarea name="notes2" rows="50" cols="80"><?php echo $notes;?></textarea><br />
			
			<input type="hidden" name="title" value=<?php echo $title; ?> >
			
		
			<a href="/cryptonotes/deleteNotes.php?email=<?php echo $email;?>&title=<?php echo $title;?>&pass=<?php echo $passwords;?>">Delete</a>
			<input type="submit" value="Update"> <br />
			
		</form>
	</td>
	<td></td>
</tr>
</body>

</html>

<script>
	function validateForm() {
    var title = document.forms["myForm"]["title"].value;
    if (title == null || title == "") {
        alert("Title must be filled out");
        return false;
    }
	
	var notes = document.forms["myForm"]["notes"].value;
    if (notes == null || notes == "") {
        alert("Notes must be filled out");
        return false;
    }
	
	return true;
}
</script>