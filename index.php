<?php 

	require "functions.php";

    check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
</head>
<body>
    <?php require "header.php";?>
    <div style="max-width: 600px;margin: auto;">
    <h3 style="text-align: center; margin-top: 10px;">Timeline</h3>
    <hr style="width: 100%">
				<?php
					$query = "select * from posts order by id desc limit 10";

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
								</div>
							</div>
						</div>
					<?php endwhile;?>
				<?php endif;?>
			</div>
    <?php require "footer.php";?>
</body>
</html>