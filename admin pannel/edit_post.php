<?php 
	include '../components/connect.php';
	if(isset($_COOKIE['seller_id'])){
      	$seller_id = $_COOKIE['seller_id'];
	   }else{
	      $seller_id = '';
	      header('location:login.php');
	   }


	if (isset($_POST['save'])) {
		$post_id = $_POST['post_id'];
		$title = $_POST['title'];
		$title = filter_var($title, FILTER_SANITIZE_STRING);
		$price = $_POST['price'];
		$price = filter_var($price, FILTER_SANITIZE_STRING);
		$content = $_POST['content'];
		$content = filter_var($content, FILTER_SANITIZE_STRING);
		$stock = $_POST['stock'];
		$stock = filter_var($stock, FILTER_SANITIZE_STRING);
		$status = $_POST['status'];
		$status = filter_var($status, FILTER_SANITIZE_STRING);

		$update_post = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, stock = ?, status=? WHERE id=?");
		$update_post->execute([$title, $price, $content, $stock, $status, $post_id]);

		$success_msg[] = 'product updated!';

		$old_image = $_POST['old_image'];
		$image = $_FILES['image']['name'];
		$image_size = $_FILES['image']['size'];
		$image_tmp_name = $_FILES['image']['tmp_name'];
		$image_folder = '../uploaded_files/'.$image;

		$select_image = $conn->prepare("SELECT * FROM `products` WHERE image=? AND seller_id=?");
		$select_image->execute([$image, $seller_id]);

		if (!empty($image)) {
			if ($image_size > 2000000) {
				$warning_msg[] = 'image size is too large';
			}elseif($select_image->rowCount() > 0 AND $image != ''){
				$warning_msg[] = 'please rename your image';
			}else{
				$update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id=?");
				$update_image->execute([$image, $post_id]);
				move_uploaded_file($image_tmp_name, $image_folder);
				if ($old_image != $image AND $old_image != '') {
					unlink('../uploaded_files/'.$old_image);
				}
				$success_msg[] = 'image updated!';
			}
		}
	}
	
	//delete product

	if (isset($_POST['delete_post'])) {
		$post_id = $_POST['post_id'];
		$post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
		$delet_image = $conn->prepare("SELECT * FROM `products` WHERE id=?");
		$delet_image->execute([$post_id]);
		$fetch_delete_image = $delet_image->fetch(PDO::FETCH_ASSOC);
		if ($fetch_delete_image['image'] != '') {
			unlink('../uploaded_img/'.$fetch_delete_image['image']);
		}
		$delete_post = $conn->prepare("DELETE FROM `products` WHERE id=?");
		$delete_post->execute([$post_id]);
		$success_msg[] = 'product deleted successfully!';
	}

	//delete image

	if (isset($_POST['delete_image'])) {
		$empty_image = '';
		$post_id = $_POST['post_id'];
		$post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
		$delet_image = $conn->prepare("SELECT * FROM `products` WHERE id=?");
		$delet_image->execute([$post_id]);
		$fetch_delete_image = $delet_image->fetch(PDO::FETCH_ASSOC);
		if ($fetch_delete_image['image'] != '') {
			unlink('../uploaded_img/'.$fetch_delete_image['image']);
		}
		$unset_image = $conn->prepare("UPDATE `products` SET image=? WHERE id=?");
		$unset_image->execute([$empty_image, $post_id]);
		$success_msg[] = 'image deleted successfully!';
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
	<title>Admin - edit product page</title>
</head>
<body>
	<div class="main-container">
		
		<?php include '../components/admin_header.php'; ?>
		<section class="post-editor">
			<div class="heading">
				<h1>edit product</h1>
				<img src="../image/separator-img.png" width="100">
			</div>
			<div class="box-container">
				<?php 
					$post_id = $_GET['id'];
					$select_post = $conn->prepare("SELECT * FROM `products` WHERE id=?");
					$select_post->execute([$post_id]);
					if ($select_post->rowCount() > 0) {
						while($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)){


				?>
				<div class="form-container">
					<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="old_image" value="<?= $fetch_post['image']; ?>">
						<input type="hidden" name="post_id" value="<?= $fetch_post['id']; ?>">
						<div class="input-field">
							<label>post status <sup>*</sup></label>
							<select name="status" class="box">
								<option value="<?= $fetch_post['status']; ?>" selected><?= $fetch_post['status']; ?></option>
								<option value="active">active</option>
								<option value="deactive">deactive</option>
							</select>
						</div>
						<div class="input-field">
							<label>product name <sup>*</sup></label>
							<input type="text" name="title" value="<?= $fetch_post['name']; ?>" class="box">
						</div>
						<div class="input-field">
							<label>product price <sup>*</sup></label>
							<input type="price" name="price" value="<?= $fetch_post['price']; ?>" class="box">
						</div>
						<div class="input-field">
							<label>product detail <sup>*</sup></label>
							<textarea name="content" class="box"><?= $fetch_post['product_detail']; ?></textarea>
						</div>

						<div class="input-field">
							<p>total stock <span>*</span></p>
	         				<input type="number" name="stock" required maxlength="10" placeholder="<?= $fetch_post['stock']; ?>" min="0" max="9999999999" class="box">
						</div>
						<div class="input-field">
							 <label>product image <sup>*</sup></label>
							 <input type="file" name="image" accept="image/*">
							 <?php if($fetch_post['image'] != ''){ ?>
							 	<img src="../uploaded_files/<?= $fetch_post['image']; ?>" class="image">
							 	<div class="flex-btn">
							 		<input type="submit" name="delete_image" class="btn" value="delete image">
							 		<a href="view_posts.php" class="btn" style="width:49%; text-align: center; height: 3rem; margin-top: .7rem;">go back</a>
							 	</div>
							<?php } ?>
						</div>
						<div class="flex-btn">
							<input type="submit" name="save" value="save post" class="btn">
							<input type="submit" name="delete_post" value="delete post" class="btn">
						</div>
					</form>
				</div>
				<?php 
						}
					}else{
						echo '
							<div class="empty">
								<p>no product added yet! </p>
							</div>
						';
					
				?>
				<br><br>
				<div class="flex-btn">
					<a href="view_posts.php" class="btn">view product</a>
					<a href="add_posts.php" class="btn">add product</a>
				</div>
				<?php } ?>
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