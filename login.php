<?php 

	require "functions.php";

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		
		$email = addslashes($_POST['email']);
		$password = addslashes($_POST['password']);

		$query = "select * from users where email = '$email' && password = '$password' limit 1";

		$result = mysqli_query($con,$query);

		if(mysqli_num_rows($result) > 0){

			$row = mysqli_fetch_assoc($result);

			$_SESSION['info'] = $row;
			header("Location: profile.php");
			die;
		}else{
			$error = "wrong email or password";
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php require "header.php";?>
    <div style="display: flex; align-items: center; justify-content: center; height: 612px; background-color: #FF4433">
            <?php 

				if(!empty($error)){
					echo "<div>".$error."</div>";
				}

			?>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0px; background-color: black; padding: 30px; border-radius: 20px; height: 500px; width: 450px">
    <h1 style="color: white; padding-bottom: 20px;">Login</h1>
        <form method="post">
            <input type="email" name="email" placeholder="Enter Email" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button>Login</button>
        </form>
    </div>
    </div>
    <?php require "footer.php";?>
</body>
</html>