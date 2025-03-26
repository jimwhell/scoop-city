<?php 
 include 'components/connect.php';
 if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
      header('location:home.php');
   }
	
	if (isset($_POST['place_order'])) {

		$name = $_POST['name'];
		$name = filter_var($name, FILTER_SANITIZE_STRING);
		$number = $_POST['number'];
		$number = filter_var($number, FILTER_SANITIZE_STRING);
		$email = $_POST['email'];
		$email = filter_var($email, FILTER_SANITIZE_STRING);
		$address = $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].', '. $_POST['pincode'];
		$address = filter_var($address, FILTER_SANITIZE_STRING);
		$address_type = $_POST['address_type'];
		$address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
		$method = $_POST['method'];
		$method = filter_var($method, FILTER_SANITIZE_STRING);

	

		$varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
		$varify_cart->execute([$user_id]);

		if (isset($_GET['get_id'])) {
			$get_product = $conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
			$get_product->execute([$_GET['get_id']]);
			if ($get_product->rowCount() > 0) {
				while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
					$seller_id = $fetch_p['seller_id'];
					$insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
			        $insert_order->execute([unique_id(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
			            header('location:order.php');
					
					
				}
			}else{
				$warning_msg[] = 'somthing went wrong';
			}
		}elseif ($varify_cart->rowCount()>0) {
			while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)){
				$s_products=$conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
                $s_products->execute([$f_cart['product_id']]);
                $f_product = $s_products->fetch(PDO::FETCH_ASSOC);

                $seller_id = $f_product['seller_id'];
				$insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
			        $insert_order->execute([unique_id(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);
			            header('location:order.php');
			}
			if ($insert_order) {
				$delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
				$delete_cart_id->execute([$user_id]);
				header('location: order.php');
			}
		}else{
			$warning_msg[] = 'somthing went wrong';
		}

	}
	

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/user_style.css">
	<title>Scoop City - checkout</title>
	<link rel="icon" type="image/x-icon" href="image/favicon.png">
	<link rel="icon" type="image/x-icon" href="image/favicon.png">
</head>
<body>
	<?php include 'components/user_header.php'; ?>
	
		<!-- <div class="banner">
	        <div class="detail">
	            <h1>checkout</h1>
	            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>
	            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
	            <span><a href="home.html">home</a><i class='bx bx-right-arrow-alt'></i>checkout</span>
	        </div>
	    </div> -->
		<section class="checkout">
		<div class="heading">
				
				<h1>checkout summary</h1>
				<img src="image/separator-img.png" alt="">
           	 		</div>
			
                <div class="row">
				
                	<form method="post" class="form">

                		<input type="hidden" name="p_id" value="<?= $get_id; ?>">
                		<h3>billing details</h3>
                		<div class="flex">
                			<div class="box">
                				<div class="input-field">
                					<p>your name <span>*</span></p>
                					<input type="text" name="name" required maxlength="50" placeholder="Enter Your name" class="input">
                				</div>
                				<div class="input-field">
                					<p>your number <span>*</span></p>
                					<input type="number" name="number" required maxlength="10" placeholder="Enter Your number" class="input">
                				</div>
                				<div class="input-field">
                					<p>your email <span>*</span></p>
                					<input type="email" name="email" required maxlength="50" placeholder="Enter Your email" class="input">
                				</div>
                				<div class="input-field">
                					<p>payment method <span>*</span></p>
                					<select name="method" class="input">
                						<option value="cash on delivery">cash on delivery</option>
                						<option value="credit or debit card">credit or debit card</option>
                						<option value="net banking">net banking</option>
                						<option value="UPI or RuPay">UPI or RuPay</option>
                						<option value="paytm">paytm</option>
                					</select>
                				</div>
                				<div class="input-field">
                					<p>address type <span>*</span></p>
                					<select name="address_type" class="input">
                						<option value="home">home</option>
                						<option value="office">office</option>
            
                					</select>
                				</div>
                			</div>
                			<div class="box">
                				<div class="input-field">
                					<p>address line 01 <span>*</span></p>
                					<input type="text" name="flat" required maxlength="50" placeholder="e.g flat & building number" class="input">
                				</div>
                				<div class="input-field">
                					<p>address line 02 <span>*</span></p>
                					<input type="text" name="street" required maxlength="50" placeholder="e.g street name" class="input">
                				</div>
                				<div class="input-field">
                					<p>city name <span>*</span></p>
                					<input type="text" name="city" required maxlength="50" placeholder="Enter your city name" class="input">
                				</div>
                				<div class="input-field">
                					<p>country name <span>*</span></p>
                					<input type="text" name="country" required maxlength="50" placeholder="Enter your city name" class="input">
                				</div>
                				<div class="input-field">
                					<p>pincode <span>*</span></p>
                					<input type="text" name="pincode" required maxlength="6" placeholder="110022" min="0" max="999999" class="input">
                				</div>
                			</div>
                		</div>
                		<button type="submit" name="place_order" class="btn">place order</button>
                	</form>
                	<div class="summary">
                		<h3>my bag</h3>
                		<div class="box-container">
                			<?php 
                				$grand_total=0;
                				if (isset($_GET['get_id'])) {
                					$select_get = $conn->prepare("SELECT * FROM `products` WHERE id=?");
                					$select_get->execute([$_GET['get_id']]);
                					while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                						$sub_total = $fetch_get['price'];
                						$grand_total+=$sub_total;
                					
                			?>
                			<div class="flex">
                				<img src="uploaded_files/<?=$fetch_get['image']; ?>" class="image">
                				<div>
                					<h3 class="name"><?=$fetch_get['name']; ?></h3>
                					<p class="price"><?=$fetch_get['price']; ?>/-</p>
                				</div>
                			</div>
                			<?php 
                					}
                				}else{
                					$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
                					$select_cart->execute([$user_id]);
                					if ($select_cart->rowCount()>0) {
                						while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                							$select_products=$conn->prepare("SELECT * FROM `products` WHERE id=?");
                							$select_products->execute([$fetch_cart['product_id']]);
                							$fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                							$sub_total= ($fetch_cart['qty'] * $fetch_product['price']);
                							$grand_total += $sub_total;
                							
                						
                			?>
                			<div class="flex">
                				<img src="uploaded_files/<?=$fetch_product['image']; ?>">
                				<div>
                					<h3 class="name"><?=$fetch_product['name']; ?></h3>
                					<p class="price"><?=$fetch_product['price']; ?> X <?=$fetch_cart['qty']; ?></p>
                				</div>
                			</div>
                			<?php 
                						}
                					}else{
                						echo '<p class="empty">your cart is empty</p>';
                					}
                				}
                			?>
                		</div>
                		<div class="grand-total"><span>total amount payable: </span>$<?= $grand_total ?>/-</div>
                	</div>
			</div>
		</section>
		<?php include 'components/footer.php'; ?>
    
	<!-- sweetalert cdn link  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	<!-- custom js link  -->
	<script type="text/javascript" src="script.js"></script>

	<?php include 'components/alert.php'; ?>
</body>
</html>