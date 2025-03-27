<?php

include 'components/connect.php';
require_once './components/log_transaction.php';


   if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
   }
   include 'components/add_wishlist.php';
   include 'components/add_cart.php';

   $page_visited = $_SERVER['REQUEST_URI'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Scoop City - Search</title>
   <link rel="icon" type="image/x-icon" href="image/favicon.png">

   <!-- box icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/user_style.css">

    <style>

.shop-search .search-form {
  width: 30rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  gap: 2rem;
  padding: 1rem 2rem;
  background-color: var(--pink-opacity);
}


.shop-search .search-form input {
  width: 100%;
  background: none;
  font-size: 1.1rem;
  box-shadow: none;
}

.shop-search .search-form.active {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
  }


.shop-search .search-form button {
  font-size: 1.1rem;
  color: var(--main-color);
  cursor: pointer;
  background: none;
  box-shadow: none;
}

.shop-search .search-form {
  width: 30rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  gap: 2rem;
  padding: 1rem 2rem;
  background-color: var(--pink-opacity);
}

.shop-search .search-form input::placeholder {
  color: white;
  opacity: 1; /* Firefox */
}

.shop-search .search-form button:hover {
  color: var(--main-color);
}

	</style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->
<!-- <div class="banner">
        <div class="detail">
            <h1>contact us</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
            <span><a href="home.html">home</a><i class='bx bx-right-arrow-alt'></i>contact us</span>
        </div>
    </div> -->

<section class="products">

<div class="shop-search">
		<form action="search_product.php" method="post" class="search-form">
         	<input type="text" name="search_product" placeholder="search product..." required maxlength="100">
         <button type="submit" class="bx bx-search-alt-2" name="search_product_btn"></button>
    </form>
	</div>

   <div class="heading" >
          <h1>search result</h1>
          <img src="image/separator-img.png" alt="">
   </div>

   <div class="box-container">

      <?php
         if(isset($_POST['search_product']) or isset($_POST['search_product_btn'])){
         $search_products = $_POST['search_product'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_products}%' AND status = ?");
         $select_products->execute(['active']);
         logActivity($user_id, 'READ', $page_visited, 'User searched for a product.');
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
               $product_id = $fetch_products['id'];

               
      ?>
      <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo 'disabled';}; ?>">
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
            <div class="button">
               <div><h3 class="name"><?= $fetch_products['name']; ?></h3></div>
               <div>
                  <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                  <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                  <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show"></a>
               </div>
            </div>
            <p class="price">price $<?= $fetch_products['price']; ?></p>
            <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
            <div class="flex-btn">
               <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">buy now</a>
               <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
            </div>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no courses found!</p>';
      }
      }else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>

   </div>

</section>

<!-- product section ends -->

<?php include 'components/footer.php'; ?>
   
   <!-- sweetalert cdn link  -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <!-- custom js link  -->
   <script type="text/javascript" src="js/script.js"></script>

   <?php include 'components/alert.php'; ?>
   
</body>
</html>