<html>
<head>
	<title>My CryptoNotes</title>
</head>

<body>

<?php
error_reporting(E_ALL ^ E_WARNING); 

//$_SESSION['login_email']= $email;
//$_SESSION['login_pass']= md5($passwords);
session_start();

$email = "";
$passwords = "";
if (isset($_SESSION['email'])) {$email = $_SESSION['email']; }
if (isset($_SESSION['pass'])) { $passwords = $_SESSION['pass']; }


$title = "";
$notes = "";
//$email = $_GET['email'];
//$passwords = $_GET['pass'];

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

$conn->close();

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
		<form name="myForm" action="InsertNotes.php" method="get" onsubmit="return validateForm()">

		Title: <input type="text" name="title" value="<?php echo $title;?>"><br />
		<textarea name="notes" rows="50" cols="80"><?php echo $notes;?></textarea><br />
		
		<input type="submit">
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



