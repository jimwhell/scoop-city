
<header class="header">

   <section class="flex">

      <a href="home.php" class="logo"><img src="image/logoSC2.png" width="130px"> </a>
      <nav class="navbar">
         <a href="home.php"><span>home</span></a>
         <a href="about-us.php"><span>about us</span></a>
         <a href="menu.php"><span>shop</span></a>
         <a href="order.php"><span>order</span></a>
         <a href="contact.php"><span>contact us</span></a>
      </nav>
      
     

      <div class="icons">
         <div id="menu-btn" class="bx bx-list-plus"></div>
         <div id="search-btn" class="bx bx-search-alt-2"></div>
         <?php 
            $count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id=?");
            $count_wishlist_item->execute([$user_id]);
            $total_Wishlist_items = $count_wishlist_item->rowCount();
         ?>
         <a href="wishlist.php" class="cart-btn"><i class="bx bx-heart"></i><sup><?= $total_Wishlist_items; ?></sup></a>
         <?php 
            $count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
            $count_cart_item->execute([$user_id]);
            $total_cart_items = $count_cart_item->rowCount();
         ?>
         <a href="cart.php" class="cart-btn"><i class="bx bx-cart"></i><sup><?= $total_cart_items; ?></sup></a>
         <div id="user-btn" class="bx bxs-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3 style="margin-bottom: 1rem"><?= $fetch_profile['name']; ?></h3>
         <div class="flex-btn">
            <a href="profile.php" class="btn">view profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="btn">logout</a>
         </div>
         
         <?php
            }else{
         ?>
         <h3 style="margin-bottom: 1rem">please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="btn">login</a>
            <a href="register.php" class="btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>student</span>
         <a href="profile.php" class="btn">view profile</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about us</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
   </nav>

</div>

 side bar section ends -->