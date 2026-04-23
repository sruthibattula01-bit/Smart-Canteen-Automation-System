<?php
include("db.php");
$step = "login";
$message = "";

// STEP 1: LOGIN → GENERATE OTP
if(isset($_POST['sendotp'])) {

    $u = $_POST['username'];
    $p = $_POST['password'];
    $otp = $_POST['otp_hidden'];

    if($u != "" && $p != "") {

        // check in database
        $query = "SELECT * FROM students WHERE username='$u' AND password='$p'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {

            setcookie("otp", $otp, time()+300);
            setcookie("user", $u, time()+300);

            $step = "otp";
            $message = "OTP Sent";
			echo "<script>alert('Your OTP is: $otp');</script>";
        } else {
            $message = "Invalid Login";
        }

    } else {
        $message = "Fill all fields";
    }
}

// STEP 2: VERIFY OTP
if(isset($_POST['verify'])) {

    $userotp = $_POST['otp'];

    if($userotp == $_COOKIE['otp']) {
        $message = "Login Successful";
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
<title>Canteen Login</title>

<style>

/* HEADER */
.header {
    background-color: #1d055c;
    color: white;
    padding: 15px;
    text-align: center;
}

/* NAV BAR */
.nav {
    background-color: #c5dbfa;
    padding: 10px;
    text-align: center;
	color: #1d055c;
}
.nav a {
    color: black;
    margin: 15px;
    text-decoration: none;
}
.nav a:hover {
    color: yellow;
}

/* BODY */
body {
    font-family: Arial;
    margin: 0;
    background-color: white;
    text-align: center;
}

form {
    margin-top: 40px;
    display: inline-block;
    padding: 25px;
    background-color: white;
    border: 2px solid #1d055c;
    border-radius: 10px;
}

input {
    padding: 8px;
    width: 300px;
}

input[type="submit"] {
    background-color: #1d055c;
    border: none;
    padding: 10px;
    color: white;
}

input[type="submit"]:hover {
    background-color: green;
}

/* FOOTER */
.footer {
    margin-top: 50px;
    background-color: #1d055c;
    color: white;
    padding: 15px;
}

</style>

<script>
function checkLogin() {

    var u = document.getElementById("u").value;
    var p = document.getElementById("p").value;

    var reg = /^[A-Za-z]+$/;

    if(u=="" || p=="") {
        alert("Fill all fields");
        return false;
    }

    if(!reg.test(u)) {
        alert("Username only letters");
        return false;
    }

    // Generate OTP
    var otp = Math.floor(1000 + Math.random() * 9000);
    document.getElementById("otp_hidden").value = otp;

    return true;
}
</script>

</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1>Canteen Billing System</h1>
    <p>Welcome to Online Food Ordering</p>
</div>

<!-- NAVIGATION -->
<div class="nav">
    <a href="login.php">Home</a>
    <a href="menu.html">Menu</a>
    <a href="cart.html">Cart</a>
    <a href="billing.html">Billing</a>
    <a href="feedback.html">About Us</a>
</div>

<h3><?php echo $message; ?></h3>

<!-- LOGIN FORM -->
<?php if($step == "login") { ?>

<form method="post" onsubmit="return checkLogin()">

<h2>Login</h2>

Username:<br>
<input type="text" name="username" id="u"><br><br>

Password:<br>
<input type="password" name="password" id="p"><br><br>

<input type="hidden" name="otp_hidden" id="otp_hidden">

<input type="submit" name="sendotp" value="Login">

</form>

<?php } ?>

<!-- OTP FORM -->
<?php if($step == "otp") { ?>

<form method="post">

<h2>Enter OTP</h2>

<input type="text" name="otp"><br><br>

<input type="submit" name="verify" value="Verify OTP">

</form>

<?php } ?>

<!-- SUCCESS -->
<?php if($step == "done") { ?>

<h2>Welcome <?php echo $_COOKIE['user']; ?></h2>

<p>Login Successful ✅</p>

<a href="menu.html">Go to Menu Page</a>

<?php } ?>

<!-- FOOTER -->
<div class="footer">
    <p>© 2026 Canteen Billing System</p>
</div>

</body>
</html>