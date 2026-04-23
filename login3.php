<?php
$step = "register";
$message = "";

// STEP 1: REGISTER → RECEIVE OTP FROM JS
if(isset($_POST['sendotp'])) {

    //  FORM PROCESSING
    $u = $_POST['username'];
    $p = $_POST['password'];
    $otp = $_POST['otp_hidden'];

    //  REGULAR EXPRESSION (PHP)
    if(!preg_match("/^[A-Za-z]+$/", $u)) {
        $message = "Username must contain only letters (PHP regex)";
    }
    else if($u != "" && $p != "") {

        //  STRING COMPARISON
        if($p == "1234") {
            $message = "Weak password (string comparison)";
        }

        //  CHARACTER PROCESSING
        $u = strtoupper($u); // convert to uppercase

        // COOKIES
        setcookie("otp", $otp, time()+300);
        setcookie("user", $u, time()+300);

        $message = "OTP Sent (Demo): $otp";
        $step = "otp";
    } else {
        $message = "Fill all fields";
    }
}

// STEP 2: VERIFY OTP
if(isset($_POST['verify'])) {

    //  FORM PROCESSING
    $userotp = $_POST['otp'];

    //  STRING COMPARISON
    if($userotp == $_COOKIE['otp']) {
        $message = "Registration Successful";
        $step = "done";
    } else {
        $message = "Wrong OTP";
        $step = "otp";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
body {
    background-color: cornsilk;
    font-family: Arial;
    text-align: center;
}
form {
    display: inline-block;
    padding: 20px;
    border: 2px solid black;
    background-color: white;
}
input {
    padding: 8px;
}
button, input[type="submit"] {
    padding: 10px;
    background-color: orange;
}
button:hover, input[type="submit"]:hover {
    background-color: green;
}
h2 {
    color: red;
}
</style>

<script>
function checkReg() {

    var u = document.getElementById("u").value;
    var p = document.getElementById("p").value;

    var reg = /^[A-Za-z]+$/;

    if(u=="" || p=="") {
        alert("Fill all fields");
        return false;
    }

    if(!reg.test(u)) {
        alert("Username only letters (JS regex)");
        return false;
    }

    // CHARACTER PROCESSING (JS)
    var firstChar = u.charAt(0);
    console.log("First character: " + firstChar);

    // Math.random()
    var otp = Math.floor(1000 + Math.random() * 9000);
    document.getElementById("otp_hidden").value = otp;

    return true;
}
</script>

</head>

<body>

<h2>Register Page</h2>
<p><?php echo $message; ?></p>

<?php if($step == "register") { ?>

<form method="post" onsubmit="return checkReg()">

Username:
<input type="text" name="username" id="u"><br><br>

Password:
<input type="password" name="password" id="p"><br><br>

<input type="hidden" name="otp_hidden" id="otp_hidden">

<input type="submit" name="sendotp" value="Register">

</form>

<?php } ?>

<?php if($step == "otp") { ?>

<form method="post">

Enter OTP:
<input type="text" name="otp"><br><br>

<input type="submit" name="verify" value="Verify OTP">

</form>

<?php } ?>

<?php if($step == "done") { ?>

<h3>Registration Completed</h3>

<!-- DYNAMIC CONTENT -->
<p>Welcome <?php echo $_COOKIE['user']; ?></p>

<a href="menu.html">Go to menu</a>

<?php } ?>

</body>
</html>