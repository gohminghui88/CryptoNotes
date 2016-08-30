<html>
<head>
	<title>My CryptoNotes:: Login</title>
</head>

<body>

<?php
$email = "";
$passwords = "";
?>


<form name="myForm" action="ValidateUser.php" method="get" onsubmit="return validateForm()">

Email: <input type="text" name="email" value=""><br />
Passwords: <input type="password" name="pass" value=""><br />
<a href="/cryptonotes/RegisterUser.php">Register</a><br />
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
	
	return true;
}
</script>