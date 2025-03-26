<?php
	require_once './components/log_transaction.php';
	error_reporting(0);
	if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
      header('location:login.php');
   }

   $page_visited = $_SERVER['REQUEST_URI'];
   logActivity($user_id, 'READ', $page_visited, 'User viewed their cart.');



	//update cart product quantity
	if (isset($_POST['update_cart'])) {
		$cart_id = $_POST['cart_id'];
		$cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
		$qty = $_POST['qty'];
		$qty = filter_var($qty, FILTER_SANITIZE_STRING);

		$update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id=?");
		$update_qty->execute([$qty, $cart_id]);
		logActivity($user_id, 'UPDATE', $page_visited, 'User updated their cart.');
		$success_msg[] = 'cart quantity updated successfully!';
	}
	
	//delete product from cart
	if (isset($_POST['delete_item'])) {
		$cart_id = $_POST['cart_id'];
		$cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

		$verify_delete_item = $conn->prepare("SELECT * FROM `cart` WHERE id=?");
		$verify_delete_item->execute([$cart_id]);

		if ($verify_delete_item->rowCount() > 0) {
			$delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
			$delete_cart_id->execute([$cart_id]);
		logActivity($user_id, 'DELETE', $page_visited, 'User deleted item from cart.');

			$success_msg[] = 'cart item delete successfully!';
		}else{
			$warning_msg[] = 'cart item already deleted';
		}
	}

	//empty cart 

	if (isset($_POST['empty_cart'])) {
		$verify_empty_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
		$verify_empty_item->execute([$user_id]);

		if ($verify_empty_item->rowCount() > 0) {
			$delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id=?");
			$delete_cart_id->execute([$user_id]);
			$success_msg[] = 'empty cart successfully';
		}else{
			$warning_msg[] = 'your cart is already empty';
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
	<!-- box icon cdn link  -->
   <link rel="stylesheet" href="css/user_style.css">
	<title>Scoop City - Cart</title>
	<link rel="icon" type="image/x-icon" href="image/favicon.png">
</head>
<body>
	<?php include 'components/user_header.php'; ?>
	
	<div class="banner-shop">
        <div class="detail">
            <h1>cart</h1>
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
            <span><a href="home.html">home</a><i class='bx bx-right-arrow-alt'></i>cart</span> -->
        </div>
    </div>
	<section class="products">
		<div class="heading">
			<h1>products added in cart</h1>
			<img src="image/separator-img.png" alt="" class="image">
		</div>
		<div class="box-container">
			<?php 
				$grand_total = 0;
				$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
				$select_cart->execute([$user_id]);
				if ($select_cart->rowCount() > 0) {
					while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
						$select_products = $conn->prepare("SELECT * FROM `products` WHERE id=?");
						$select_products->execute([$fetch_cart['product_id']]);
						if ($select_products->rowCount() > 0) {
							$fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
						
			?>
			<form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo 'disabled';}; ?>">
				<input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
				<img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
				<?php if ($fetch_products['stock'] > 9) { ?>
		         <span class="stock" style="color: green;"><i class="fas fa-check" style="margin-right: .5rem;"></i>In Stock</span>
			      <?php }elseif($fetch_products['stock'] == 0){ ?>
			         <span class="stock" style="color: red;"><i class="fas fa-times" style="margin-right: .5rem;"></i>Out Of Stock</span>
			      <?php }else{ ?>
			         <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?>left</span>
			      <?php } ?>
				<div class="content">
					<img src="image/shape-19.png" alt="" class="shap">
					<h3 class="name"><?= $fetch_products['name']; ?></h3>
					<div class="flex-btn">
						<p class="price">price $<?= $fetch_products['price']; ?>/-</p>
						<input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty">
						<button type="submit" name="update_cart" class="bx bxs-edit fa-edit"></button>
					</div>
					<div class="flex-btn">
						<p class="sub-total">sub total : <span>$<?= $sub_total = ($fetch_cart['qty']*$fetch_cart['price']); ?></span></p>
						<button type="submit" name="delete_item" class="btn" onclick="return confirm('delete this item');">delete</button>
					</div>
				</div>
			</form>
			<?php 
						$grand_total+=$sub_total;
						}else{
							echo'
								<div class="empty">
									<p>no products was found!</p>
								</div>
							';
						}
					}
				}else{
					echo'
						<div class="empty">
							<p>no products added yet!</p>
						</div>
					';
				}
			?>
		</div>
		<?php 
			if ($grand_total != 0) {
				
		?>
		<div class="cart-total">
			<p>total amount payable : <span>$ <?= $grand_total; ?>/-</span></p>
			<div class="button">
				<form action="" method="post">
					<button type="submit" name="empty_cart" class="btn" onclick="return confirm('are you sure to empty your cart');">empty cart</button>
				</form>
				<a href="checkout.php" class="btn">proceed to checkout</a>
			</div>
		</div>
		<?php }?>
	</section>


	<?php include 'components/footer.php'; ?>
	<?php include 'components/dark.php'; ?>
	<!-- sweetalert cdn link  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	<!-- custom js link  -->
	<script type="text/javascript" src="script.js"></script>

	<?php include 'components/alert.php'; ?>
</body>
</html>