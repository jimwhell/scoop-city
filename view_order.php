<?php 
	include 'components/connect.php';
	require_once './components/log_transaction.php';

	if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
      header('location:login.php');
   }

	if (isset($_GET['get_id'])) {
		$get_id = $_GET['get_id'];
	}else{
		$get_id = '';
		header("location:order.php");
	}

	if (isset($_POST['cancle'])) {
		$update_order = $conn->prepare("UPDATE `orders` SET status=? WHERE id=?");
		$update_order->execute(['canceled', $get_id]);
		header('location:order.php');
	}
	$page_visited = $_SERVER['REQUEST_URI'];
   logActivity($user_id, 'READ', $page_visited, 'User viewed their order detail.');


	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- box icon cdn link  -->
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
	<link rel="stylesheet" href="css/user_style.css">
	<title>Scoop City - View Order</title>
	<link rel="icon" type="image/x-icon" href="image/favicon.png">
</head>
<body>
	<?php include 'components/user_header.php'; ?>
	
	<!-- <div class="banner">
        <div class="detail">
            <h1>my order</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
            <span><a href="home.html">home</a><i class='bx bx-right-arrow-alt'></i>my order</span>
        </div>
    </div> -->
	<div class="order-detail">
		<div class="heading">
			<h1>order detail</h1>
			<!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum deserunt minus veniam tenetur</p>  -->
			<img src="image/separator-img.png" alt="">          
		</div>
		<div class="box-container">
			<?php 
				$grand_total = 0;
				$select_order = $conn->prepare("SELECT * FROM `orders` WHERE id=? LIMIT 1");
				$select_order->execute([$get_id]);
				if ($select_order->rowCount() > 0) {
					while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
						$select_product = $conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
						$select_product->execute([$fetch_order['product_id']]);
						if ($select_product->rowCount() > 0) {
							while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
								$sub_total = ($fetch_order['price']*$fetch_order['qty']);
								$grand_total+= $sub_total;
							
			?>
			<div class="box">
				<div class="col">
				<h3 class="name"><?= $fetch_product['name']; ?></h3>
				<p class="date"><i class='bx bxs-calendar-alt'></i><?= $fetch_order['date']; ?></p>
					<img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image">
					<p class="price"><?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p>
				
					<p class="grand-total">total amount payable : <span>$<?= $grand_total; ?>/-</span></p>
				</div>
				<div class="col">
					<p class="title">billing address</p>
					<p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['name']; ?></p>
					<p class="user"><i class="bi bi-phone"></i><?= $fetch_order['number']; ?></p>
					<p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['email']; ?></p>
					<p class="user"><i class="bi bi-pin-map-fill"></i><?= $fetch_order['address']; ?></p>
					<p class="title">status</p>
					<p class="status" style="color:<?php if($fetch_order['status']=='delivered'){echo "green";}elseif($fetch_order['status']=='canceled'){echo "red";}else{echo "orange";} ?>"><?= $fetch_order['status']; ?></p>
					<?php if ($fetch_order['status']=='canceled') { ?>
						<a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">order again</a>
					<?php }else{ ?>
						<form action="" method="post">
							<button type="submit" name="cancle" class="btn" onclick="return confirm('do you want to cancle this order');">cancel</button>
						</form>
					<?php } ?>		
				</div>
			</div>
			<?php 
							}
						}
					}
				}else{
						echo '<p class="empty">no order takes placed yet!</p>';
				}
			?>
		</div>
	</div>
	<?php include 'components/footer.php'; ?>
	
	<!-- sweetalert cdn link  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	<!-- custom js link  -->
	<script type="text/javascript" src="js/script.js"></script>

	<?php include 'components/alert.php'; ?>
</body>
</html>