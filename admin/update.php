<?php

   include '../components/connect.php';

   if(isset($_COOKIE['seller_id'])){
      $seller_id = $_COOKIE['seller_id'];
   }else{
      $seller_id = '';
      header('location:login.php');
   }

if(isset($_POST['submit'])){

   $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? LIMIT 1");
   $select_seller->execute([$seller_id]);
   $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_seller['password'];
   $prev_image = $fetch_seller['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `sellers` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $seller_id]);
      $success_msg[] = 'username updated successfully!';
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `sellers` WHERE id = ? AND email = ?");
      $select_email->execute([$seller_id, $email]);
      if($select_email->rowCount() > 0){
         $warning_msg[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `sellers` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $seller_id]);
         $success_msg[] = 'email updated successfully!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $warning_msg[] = 'image size too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `sellers` SET `image` = ? WHERE id = ?");
         $update_image->execute([$rename, $seller_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image != '' AND $prev_image != $rename){
            unlink('../uploaded_files/'.$prev_image);
         }
         $success_msg[] = 'image updated successfully!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $warning_msg[] = 'old password not matched!';
      }elseif($new_pass != $cpass){
         $warning_msg[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `sellers` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $seller_id]);
            $success_msg[] = 'password updated successfully!';
         }else{
            $warning_msg[] = 'please enter a new password!';
         }
      }
   }

}

?>
<style>
   <?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <!-- box icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
<div class="main-container">
<?php include '../components/admin_header.php'; ?>

<!-- register section starts  -->

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <div class="img-box">
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
      </div>
      <h3>update profile</h3>
      <div class="flex">
         <div class="col">
            <p>your name </p>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50"  class="box">
            
            <p>your email </p>
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="20"  class="box">
            <p>update pic :</p>
            <input type="file" name="image" accept="image/*"  class="box">
         </div>
         <div class="col">
            <p>old password :</p>
            <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20"  class="box">
            <p>new password :</p>
            <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20"  class="box">
            <p>confirm password :</p>
            <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20"  class="box">
         </div>
      </div>
      
      <input type="submit" name="submit" value="update now" class="btn">
   </form>

</section>
<div class="main-container">
<!-- registe section ends -->
<!-- sweetalert cdn link  -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <!-- custom js link  -->
   <script type="text/javascript" src="../js/script.js"></script>

   <?php include '../components/alert.php'; ?>
   
</body>
</html>