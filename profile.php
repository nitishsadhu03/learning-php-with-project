<?php 

	require "functions.php";

    check_login();

	if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'post_delete')
	{
		//delete your post
		$id = $_GET['id'] ?? 0;
		$user_id = $_SESSION['info']['id'];

		$query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
		$result = mysqli_query($con,$query);
		if(mysqli_num_rows($result) > 0){

			$row = mysqli_fetch_assoc($result);
			if(file_exists($row['image'])){
				unlink($row['image']);
			}

		}

		$query = "delete from posts where id = '$id' && user_id = '$user_id' limit 1";
		$result = mysqli_query($con,$query);

		header("Location: profile.php");
		die;

	}
	elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == "post_edit")
	{
		//post edit
		$id = $_GET['id'] ?? 0;
		$user_id = $_SESSION['info']['id'];

		$image_added = false;
		if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg"){
			//file was uploaded
			$folder = "uploads/";
			if(!file_exists($folder))
			{
				mkdir($folder,0777,true);
			}

			$image = $folder . $_FILES['image']['name'];
			move_uploaded_file($_FILES['image']['tmp_name'], $image);

				$query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
				$result = mysqli_query($con,$query);
				if(mysqli_num_rows($result) > 0){

					$row = mysqli_fetch_assoc($result);
					if(file_exists($row['image'])){
						unlink($row['image']);
					}

				}

			$image_added = true;
		}

		$post = addslashes($_POST['post']);

		if($image_added == true){
			$query = "update posts set post = '$post',image = '$image' where id = '$id' && user_id = '$user_id' limit 1";
		}else{
			$query = "update posts set post = '$post' where id = '$id' && user_id = '$user_id' limit 1";
		}

		$result = mysqli_query($con,$query);
 
		header("Location: profile.php");
		die;
	}

    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'delete')
	{
		//delete your profile
		$id = $_SESSION['info']['id'];
		$query = "delete from users where id = '$id' limit 1";
		$result = mysqli_query($con,$query);

		if(file_exists($_SESSION['info']['image'])){
			unlink($_SESSION['info']['image']);
		}

		$query = "delete from posts where user_id = '$id'";
		$result = mysqli_query($con,$query);

		header("Location: logout.php");
		die;

	}

    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username']))
	{
        #profile edit
        $image_added = false;
		if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg"){
			//file was uploaded
			$folder = "uploads/";
			if(!file_exists($folder))
			{
				mkdir($folder,0777,true);
			}

			$image = $folder . $_FILES['image']['name'];
			move_uploaded_file($_FILES['image']['tmp_name'], $image);

			if(file_exists($_SESSION['info']['image'])){
				unlink($_SESSION['info']['image']);
			}

			$image_added = true;
		}

		$username = addslashes($_POST['username']);
		$email = addslashes($_POST['email']);
		$password = addslashes($_POST['password']);
		$id = $_SESSION['info']['id'];

		if($image_added == true){
			$query = "update users set username = '$username',email = '$email',password = '$password',image = '$image' where id = '$id' limit 1";
		}else{
			$query = "update users set username = '$username',email = '$email',password = '$password' where id = '$id' limit 1";
		}

		$result = mysqli_query($con,$query);

		$query = "select * from users where id = '$id' limit 1";
		$result = mysqli_query($con,$query);

		if(mysqli_num_rows($result) > 0){

			$_SESSION['info'] = mysqli_fetch_assoc($result);
		}

		header("Location: profile.php");
		die;
	}
    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post']))
	{
		//adding post
		$image = "";
		if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg"){
			//file was uploaded
			$folder = "uploads/";
			if(!file_exists($folder))
			{
				mkdir($folder,0777,true);
			}

			$image = $folder . $_FILES['image']['name'];
			move_uploaded_file($_FILES['image']['tmp_name'], $image);
 
		}

		$post = addslashes($_POST['post']);
		$user_id = $_SESSION['info']['id'];
		$date = date('Y-m-d H:i:s');

		$query = "insert into posts (user_id,post,image,date) values ('$user_id','$post','$image','$date')";

		$result = mysqli_query($con,$query);
 
		header("Location: profile.php");
		die;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <?php require "header.php";?>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: auto; background-color: #E5E4E2">
            
				<?php if(!empty($_GET['action']) && $_GET['action'] == 'post_delete' && !empty($_GET['id'])):?>
					<?php 
					$id = (int)$_GET['id'];
					$query = "select * from posts where id = '$id' limit 1";
					$result = mysqli_query($con,$query);
				?>
				<?php if(mysqli_num_rows($result) > 0):?>
					<?php $row = mysqli_fetch_assoc($result);?>
					<form method="post" enctype="multipart/form-data" style="display:flex; flex-direction: column; align-items: center; justify-content: center; background-color: #E5E4E2; padding-top: 20px">
                		<h3 style="margin-bottom: 15px;">Are you sure you want to delete this post?</h3>
						<img src="<?=$row['image']?>" style="width:100%;height:200px;object-fit: cover;"><br>
                		<div style="width: 500px"><?=$row['post']?></div>
						<input type="hidden" name="action" value="post_delete">
                		<button style="background-color: black; width: 500px; margin-top: 20px;">Delete</button>
						<a href="profile.php"><button type="button" style="background-color: black; width: 500px">Cancel</button></a>
            		</form>
				<?php endif;?>

				<?php elseif(!empty($_GET['action']) && $_GET['action'] == 'post_edit' && !empty($_GET['id'])):?>
					<?php 
					$id = (int)$_GET['id'];
					$query = "select * from posts where id = '$id' limit 1";
					$result = mysqli_query($con,$query);
				?>
				<?php if(mysqli_num_rows($result) > 0):?>
					<?php $row = mysqli_fetch_assoc($result);?>
					<form method="post" enctype="multipart/form-data" style="display:flex; flex-direction: column; align-items: center; justify-content: center; background-color: #E5E4E2; padding-top: 20px">
                		<h3 style="margin-bottom: 15px;">Edit post</h3>
						<img src="<?=$row['image']?>" style="width:100%;height:200px;object-fit: cover;"><br>
                		<div>Insert image: <input type="file" name="image"></div><br>
                		<textarea name="post" rows="8" style="width: 500px; padding: 10px;"><?=$row['post']?></textarea>
						<input type="hidden" name="action" value="post_edit">
                		<button style="background-color: black; width: 500px">Save</button>
						<a href="profile.php"><button type="button" style="background-color: black; width: 500px">Cancel</button></a>
            		</form>
				<?php endif;?>

                <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
					<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0px; background-color: white; padding: 20px; border-radius: 20px; height: auto; width: 400px">
                    <h1 style="color: white; padding-bottom: 20px;">Edit profile</h1>
                    <form method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <img src="<?php echo $_SESSION['info']['image']?>" style="width: 100px; height: 100px; object-fit: cover;">
                        <input type="file" name="image">
                        <input value="<?php echo $_SESSION['info']['username']?>" type="text" name="username" placeholder="Enter Username" required><br>
                        <input value="<?php echo $_SESSION['info']['email']?>" type="email" name="email" placeholder="Enter Email" required><br>
                        <input value="<?php echo $_SESSION['info']['password']?>" type="text" name="password" placeholder="Enter Password" required><br>
                        <button>Save</button>
                        <a href="profile.php"><button type="button">Cancel</button></a>
                    </form>
				</div>
				
                    <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'delete'):?>
						<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0px; background-color: white; padding: 20px; border-radius: 20px; height: auto; width: 400px">
                    <h4 style="color: white; padding-bottom: 20px;">Are you sure you want to delete profile?</h4>
                    <form method="post" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <img src="<?php echo $_SESSION['info']['image']?>" style="width: 100px; height: 100px; object-fit: cover;">
                        <div><?php echo $_SESSION['info']['username']?></div><br>
                        <div><?php echo $_SESSION['info']['email']?></div><br>
                        <input type="hidden" name="action" value="delete">
                        <button>Delete</button>
                        <a href="profile.php"><button type="button">Cancel</button></a>
                    </form>
					</div>

                <?php else:?>
					<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 20px 0px; background-color: white; padding: 20px; border-radius: 20px; height: auto; width: 400px">
                    <h1 style="color: black; padding-bottom: 20px;">User Profile</h1>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: black; font-size: 20px; margin-bottom: 20px; text-align: center" >
                    <div>
                        <p><img src="<?php echo $_SESSION['info']['image']?>" style="width: 150px; height: 150px; object-fit: cover;"></p>
                    </div>
                    <div>
                        <p>
                            <?php echo $_SESSION['info']['username']?>
                        </p>
                    </div>
                    <div>
                        <p>
                            <?php echo $_SESSION['info']['email']?>
                        </p>
                    </div>
                    <a href="profile.php?action=edit"><button style="width: 150px">Edit profile</button></a>
                    <a href="profile.php?action=delete"><button style="width: 150px">Delete profile</button></a>
                </div>
				</div>
            
            <hr style="width: 100%;">
            <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction: column; align-items: center; justify-content: center; background-color: #E5E4E2; padding-top: 20px">
                <h3 style="margin-bottom: 15px;">Create a post</h3>
                <div>Insert image: <input type="file" name="image"></div><br>
                <textarea name="post" rows="8" style="width: 500px; padding: 10px;"></textarea>
                <button style="background-color: black; width: 500px">Post</button>
            </form>
			<hr style="width: 100%;">
			<posts style="margin-top: 20px;">
				<?php
					$id = $_SESSION['info']['id'];
					$query = "select * from posts where user_id = '$id' order by id desc limit 10";

					$result = mysqli_query($con,$query);
				?>
				<?php if(mysqli_num_rows($result) > 0):?>
					<?php while ($row = mysqli_fetch_assoc($result)):?>
						<?php
							$user_id = $row['user_id'];
							$query = "select username,image from users where id = '$user_id' limit 1";
							$result2 = mysqli_query($con,$query);

							$user_row = mysqli_fetch_assoc($result2);	
						?>
						<div style="background-color:white;display: flex;border:solid thin #aaa;border-radius: 10px;margin-bottom: 10px;margin-top: 10px; padding: 10px">
							<div style="flex: 1; text-align: center; margin-right:20px;">
							<img src="<?=$user_row['image']?>" style="border-radius:50%;width:50px;height:50px;object-fit: cover;">
							<br>
							<?=$user_row['username']?>
							</div>
							<div style="flex: 8;">
								<?php if(file_exists($row['image'])) :?>
									<div style="">
										<img src="<?=$row['image']?>" style="width: 100%; height: 200px; object-fit: cover;">
									</div>
								<?php endif;?>
								<div>
								<div style="color:#888; margin: 5px 0px"><?=date("jS M, Y",strtotime($row['date']))?></div>
								<?php echo nl2br(htmlspecialchars($row['post']))?>
									<br><br>
									<a href="profile.php?action=post_edit&id=<?= $row['id']?>"><button style="width: 75px; font-size: 15px;">Edit</button></a>
                    				<a href="profile.php?action=post_delete&id=<?= $row['id']?>"><button style="width: 75px; font-size: 15px;">Delete</button></a>
								</div>
							</div>
						</div>
					<?php endwhile;?>
				<?php endif;?>
			</posts>
        <?php endif; ?>
    </div>
    <?php require "footer.php";?>
</body>
</html>