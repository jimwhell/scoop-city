<?php 
	include '../components/connect.php';
	if(isset($_COOKIE['seller_id'])){
      	$seller_id = $_COOKIE['seller_id'];
	   }else{
	      $seller_id = '';
	      header('location:login.php');
	   }
?>
<style>
	<?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- box icon cdn link  -->
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   
	<title>Admin - user account page</title>
</head>
<body>
	<div class="main-container">
		<?php include '../components/admin_header.php'; ?>
		<section class="accounts">
			<div class="heading">
				<h1>registered users</h1>
				<img src="../image/separator-img.png" width="100">
			</div>
			<div class="box-container">
				<?php 
					$select_users = $conn->prepare("SELECT * FROM `users`");
					$select_users->execute();
					if ($select_users->rowCount() > 0) {
						while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
							$user_id = $fetch_users['id'];

				?>
				<div class="box">
    				<img src="../uploaded_files/<?= $fetch_users['image']; ?>">
    				<p>user id : <span><?= $user_id ?></span></p>
    				<p>user name : <span><?= $fetch_users['name']; ?></span></p>
    				<p>user email : <span><?= $fetch_users['email']; ?></span></p>
    				<p>created at: <span><?= date('F j, Y g:i A', strtotime($fetch_users['created_at'])) ?></span></p>
    				<a href="transaction_logs.php?id=<?= $user_id ?>" class="btn">View Transaction Logs</a>
					<a href="user_logs.php?id=<?= $user_id ?>" class="btn">Authentication Logs</a>
				</div>
				<?php 
						}
					}else{
						echo '
							<div class="empty">
								<p>no user registered yet</p>
							</div>
						';
					}
				?>
			</div>
		</section>
		
	</div>
	
	<!-- sweetalert cdn link  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	<!-- custom js link  -->
	<script type="text/javascript" src="script.js"></script>

	<?php include '../components/alert.php'; ?>
</body>
</html>