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
	<title>Admin - Dashboard</title>
</head>
<body>
	<div class="main-container">
		<?php include '../components/admin_header.php'; ?>
		<section class="dashboard">
			<div class="heading">
				<h1>dashboard</h1>
				<img src="../image/separator-img.png" width="100">
			</div>
			<div class="box-container">
				<div class="box">
					<h3>Welcome!</h3>
					<p><?= $fetch_profile['name']; ?></p>
					<a href="update.php" class="btn">update profile</a>
				</div>
				<!-- <div class="box">
					<?php 
						$select_message = $conn->prepare("SELECT * FROM `message`");
						$select_message->execute();
						$number_of_msg = $select_message->rowCount();
					?>
					<h3><?= $number_of_msg; ?></h3>
					<p>unread messages</p>
					<a href="admin_message.php" class="btn">see messages</a>
				</div> -->
				<div class="box">
					<?php 
						$select_post = $conn->prepare("SELECT * FROM `products` WHERE seller_id=?");
						$select_post->execute([$seller_id]);
						$number_of_post = $select_post->rowCount();
					?>
					<h3>Site visitors analytics</h3>
					<p></p>
					<a href="visitor_analytics.php" class="btn">View</a>
				</div>
				<div class="box">
					<?php 
						$select_post = $conn->prepare("SELECT * FROM `products` WHERE seller_id=?");
						$select_post->execute([$seller_id]);
						$number_of_post = $select_post->rowCount();
					?>
					<h3><?= $number_of_post; ?></h3>
					<p>products added</p>
					<a href="add_product.php" class="btn">add new products</a>
				</div>
				<div class="box">
					<?php 
						$select_active_post = $conn->prepare("SELECT * FROM `products` WHERE seller_id=? AND status =?");
						$select_active_post->execute([$seller_id,'active']);
						$number_of_active_post = $select_active_post->rowCount();
					?>
					<h3><?= $number_of_active_post ?></h3>
					<p>total active products</p>
					<a href="view_posts.php" class="btn">see products</a>
				</div>
				<div class="box">
					<?php 
						$select_deactive_post = $conn->prepare("SELECT * FROM `products` WHERE seller_id=? AND status =?");
						$select_deactive_post->execute([$seller_id, 'deactive']);
						$number_of_deactive_post = $select_deactive_post->rowCount();
					?>
					<h3><?= $number_of_deactive_post ?></h3>
					<p>total deactive products</p>
					<a href="view_posts.php" class="btn">see products</a>
				</div>
				<div class="box">
					<?php 
						$select_users = $conn->prepare("SELECT * FROM `users`");
						$select_users->execute();
						$number_of_users = $select_users->rowCount();
					?>
					<h3><?= $number_of_users; ?></h3>
					<p>user accounts</p>
					<a href="user_accounts.php" class="btn">see users</a>
				</div>
				<div class="box">
					<?php 
						$select_admin = $conn->prepare("SELECT * FROM `sellers`");
						$select_admin->execute();
						$number_of_admin = $select_admin->rowCount();
					?>
					<h3><?= $number_of_admin; ?></h3>
					<p>admin account</p>
					<a href="user_accounts.php" class="btn">see admin</a>
				</div>
				
				<div class="box">
					<?php 
						$select_canceled_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status=?");
						$select_canceled_order->execute([$seller_id,'canceled']);
						$total_canceled_order = $select_canceled_order->rowCount();
					?>
					<h3><?= $total_canceled_order; ?></h3>
					<p>total canceled orders</p>
					<a href="admin_order.php" class="btn">canceled order</a>
				</div>
				<div class="box">
					<?php 
						$select_confirm_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id=? AND status=?");
						$select_confirm_order->execute([$seller_id,'in progress']);
						$total_confirm_order = $select_confirm_order->rowCount();
					?>
					<h3><?= $total_confirm_order; ?></h3>
					<p>total confirm orders</p>
					<a href="admin_order.php" class="btn">confirm order</a>
				</div>
				<div class="box">
					<?php 
						$select_total_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id=?");
						$select_total_order->execute([$seller_id]);
						$total_total_order = $select_total_order->rowCount();
					?>
					<h3><?= $total_total_order; ?></h3>
					<p>total orders</p>
					<a href="admin_order.php" class="btn">all orders</a>
				</div>
				
			</div>
		</section>
	</div>
	
	
	<script type="text/javascript" src="script.js"></script>
</body>
</html>