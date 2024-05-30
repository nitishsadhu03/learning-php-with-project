<style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        a {
            text-decoration: none;
        }
        body {
            background-color: white;
            font-family: tahoma;
        }
        header a {
            color: white;
            font-size: 22px
        }
        header div {
            padding: 15px 20px;
        }
        header {
            background-color: black;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        footer {
            padding: 20px;
            text-align: center;
            background-color: #FF4433;
            color: white;
            font-size: 16px;
            width: 100%;
        }
        input {
            margin: 20px 0px;
            width: 300px;
            padding: 10px 8px;
            border-radius: 5px;
            border-width: 1px;
            font-size: 15px;
        }
        button {
            margin: 10px 0px;
            width: 300px;
            padding: 10px 5px;
            border-radius: 5px;
            background-color: #FF4433;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <header>
        <div><a href="index.php">Home</a></div>
        <div><a href="profile.php">Profile</a></div>
        <?php if(empty($_SESSION['info'])):?>
			<div><a href="login.php">Login</a></div>
			<div><a href="signup.php">Signup</a></div>
		<?php else:?>
			<div><a href="logout.php">Logout</a></div>
		<?php endif;?>
    </header>