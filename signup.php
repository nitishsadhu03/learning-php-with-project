<?php
    require "functions.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$username = addslashes($_POST['username']);
		$email = addslashes($_POST['email']);
		$password = addslashes($_POST['password']);
		$date = date('Y-m-d H:i:s');

		$query = "insert into users (username,email,password,date) values ('$username','$email','$password','$date')";

		$result = mysqli_query($con,$query);

		header("Location: login.php");
		die;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>
    <?php require "header.php";?>
    <div style="display: flex; align-items: center; justify-content: center; height: 612px; background-color: #FF4433">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0px; background-color: black; padding: 30px; border-radius: 20px; height: 500px; width: 450px">
            <h1 style="color: white; padding-bottom: 20px;">Sign Up</h1>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required><br>
            <input type="email" name="email" placeholder="Enter Email" required><br>
            <input type="text" name="password" placeholder="Enter Password" required><br>
            <button>Sign Up</button>
        </form>
    </div>
    </div>
    <?php require "footer.php";?>
</body>
</html>