<html>
<head>
	<title>My CryptoNotes:: Register Users</title>
</head>

<body>

<?php
$email = "";
$passwords = "";
?>


<form name="myForm" action="InsertUser.php" method="get" onsubmit="return validateForm()">

Email: <input type="text" name="email" value=""><br />
Passwords: <input type="password" name="pass" value=""><br />
Passwords 2: <input type="password" name="pass2" value=""><br />

<input type="submit">
</form>

</body>

</html>

<script>
	function validateForm() {
    var email = document.forms["myForm"]["email"].value;
    if (email == null || email == "") {
        alert("Email must be filled out");
        return false;
    }
	
	var pass1 = document.forms["myForm"]["pass"].value;
    if (pass1 == null || pass1 == "") {
        alert("Passwords must be filled out");
        return false;
    }
	
	var pass2 = document.forms["myForm"]["pass2"].value;
    if (pass2 == null || pass2 == "") {
        alert("Passwords 2 must be filled out");
        return false;
    }
	
	if (pass1 != pass2) {
        alert("Both Passwords must be same");
        return false;
    }
	
	return true;
}
</script>