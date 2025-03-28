<?php 
	//adding products in wishlist
	require_once './components/log_transaction.php';
	if (isset($_POST['add_to_wishlist'])) {
		if ($user_id != '') {
		$id = unique_id();
		$product_id = $_POST['product_id'];
		$page_visited = $_SERVER['REQUEST_URI'];

		$varify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
		$varify_wishlist->execute([$user_id, $product_id]);

		$cart_num = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
		$cart_num->execute([$user_id, $product_id]);

		if ($varify_wishlist->rowCount() > 0) {
			$warning_msg[] = 'product already exist in your wishlist';
		}else if ($cart_num->rowCount() > 0) {
			$warning_msg[] = 'product already exist in your cart';
		}else if($user_id != ''){
			$select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
			$select_price->execute([$product_id]);
			$fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

			$insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(id, user_id,product_id,price) VALUES(?,?,?,?)");
			$insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
			logActivity($user_id, 'CREATE', $page_visited, 'User added product to wishlist.');
			$success_msg[] = 'product added to wishlist successfully';
		}
	}else{
			$warning_msg[] = "please login first";
		}
}
?>