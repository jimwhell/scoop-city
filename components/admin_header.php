<header>
	<div class="logo">
		<img src="../image/logoSC2.png" width="100">
	</div>
	<div class="right">
		<div class="bx bxs-user" id="user-btn"></div>
		<div class="toggle-btn"><i class='bx bx-menu' ></i></div>
	</div>
	<div class="profile-detail">
		<?php 
			$select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id=?");
			$select_profile->execute([$seller_id]);
			if ($select_profile->rowCount() > 0) {
				$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
			
		?>
		<div class="profile">
			<img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
			<p><?= $fetch_profile['name']; ?></p>
		</div>
		<div class="flex-btn">
			<a href="profile.php" class="btn">profile</a>
			<a href="../components/admin_logout.php" onclick="return confirm('logout from this website')" class="btn">logout</a>
		</div>
		<?php } ?>
	</div>
</header>
<div class="side-container">
	<div class="sidebar">
		<?php 
			$select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id=?");
			$select_profile->execute([$seller_id]);
			if ($select_profile->rowCount() > 0) {
				$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
			
		?>
		<div class="profile">
			<img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
			<p><?= $fetch_profile['name']; ?></p>
		</div>
		<?php } ?>
		<h5>menu</h5>
		<div class="navbar">
			<ul>
				<li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>dashboard</a></li>
				<li><a href="user_logs.php"><i class="bx bxs-user-detail"></i>user logs</a></li>
				<li><a href="user_accounts.php"><i class="bx bxs-user-detail"></i>user transaction logs</a></li>
				<li><a href="add_product.php"><i class="bx bxs-shopping-bags"></i>add products</a></li>
				<li><a href="view_posts.php"><i class="bx bxs-food-menu"></i>view products</a></li>
				
				<li><a href="../components/admin_logout.php" onclick="return confirm('logout from this website')"><i class="bx bx-log-out"></i>logout</a></li>
			</ul>
		</div>
		<h5>find us</h5>
		<div class="social-links">
			<a href="https://www.facebook.com/christructure" target="_blank"><i class="bx bxl-facebook"></i></a>
			<a href="https://www.instagram.com/christructure" target="_blank"><i class="bx bxl-instagram-alt"></i></a>
			<a href="https://www.twitter.com/christructure" target="_blank"><i class="bx bxl-twitter"></i></a>
			<!-- <i class="bx bxl-linkedin"></i>
			<i class="bx bxl-pinterest-alt"></i> -->
		</div>
	</div>
</div>