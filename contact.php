<?php 
	include 'components/connect.php';
	if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
      
   }

	//send message

	if (isset($_POST['send_message'])) {
		if ($user_id != '') {
			$id = unique_id();
			$name = $_POST['name'];
			$name = filter_var($name, FILTER_SANITIZE_STRING);

			$email = $_POST['email'];
			$email = filter_var($email, FILTER_SANITIZE_STRING);

			$subject = $_POST['subject'];
			$subject = filter_var($subject, FILTER_SANITIZE_STRING);

			$message = $_POST['message'];
			$message = filter_var($message, FILTER_SANITIZE_STRING);

			$verify_message = $conn->prepare("SELECT * FROM `message` WHERE user_id=? AND name = ? AND email = ? AND subject = ? AND message = ?");
			$verify_message->execute([$user_id, $name, $email, $subject, $message]);

			if ($verify_message->rowCount() > 0) {
				$warning_msg[] = 'message already exist';
			}else{
				$insert_message = $conn->prepare("INSERT INTO `message`(id,user_id,name,email,subject,message) VALUES(?,?,?,?,?,?)");
				$insert_message->execute([$id, $user_id, $name, $email, $subject, $message]);
				$success_msg[] = 'comment inserted successfully';

			}


		}else{
			$warning_msg[] = 'please login first';
		}
	}
	

	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- box icon cdn link  -->
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   <link rel="stylesheet" href="css/user_style.css">
	<title>Scoop City - contact us</title>
	<link rel="icon" type="image/x-icon" href="image/favicon.png">
</head>
<body>
	<?php include 'components/user_header.php'; ?>
	
	<div class="banner-contact">
        <div class="detail">
            <h1>contact us</h1>
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
            <span><a href="home.html">home</a><i class='bx bx-right-arrow-alt'></i>contact us</span> -->
        </div>
    </div>
	<div class="services">
		<div class="heading">
            <h1>our services</h1>
            <!-- <p style="text-align: center;">Just A Few Click To Make The Reservation Online For Saving Your Time And Money</p> -->
            <img src="image/separator-img.png" alt="">
        </div>
		<div class="box-container">
			<div class="box">
				<img src="image/0.png">
				<div>
					<h1>Free shipping fast</h1>
					Experience the convenience of lightning-fast and free shipping on all your Scoop City orders, ensuring your sweet treats arrive in no time!
				</div>
			</div>
			<div class="box">
				<img src="image/1.png">
				<div>
					<h1>money back & guarantee</h1>
					Rest assured with our money-back guarantee, offering you peace of mind and confidence with every purchase at Scoop City.
				</div>
			</div>
			<div class="box">
				<img src="image/2.png">
				<div>
					<h1>online support 24/7</h1>
					Get instant assistance and support anytime, anywhere with our dedicated online support team available 24/7 to address all your inquiries and concerns.
				</div>
			</div>
		</div>
	</div>
	<div class="contact">
		<div class="form-container">
		<h1>Drop Us A Line</h1>
            <p style="text-align: center;"></p>
            <img src="image/separator-img.png" alt="">
			<form action="" method="post" class="register">
				
				<div class="input-field" >
					<label>name <sup>*</sup></label>
					<input type="text" name="name" required placeholder="Enter Your Name" style="  border: 2px solid var(--pink-opacity);">
				</div>
				<div class="input-field">
					<label>email <sup>*</sup></label>
					<input type="email" name="email" required placeholder="Enter Your Email" style="  border: 2px solid var(--pink-opacity);">
				</div>
				<div class="input-field">
					<label>subject <sup>*</sup></label>
					<input type="text" name="subject" required placeholder="reason" style="  border: 2px solid var(--pink-opacity);">
				</div><div class="input-field">
					<label>comment <sup>*</sup></label>
					<textarea name="message" cols="30" rows="10" required placeholder="add any comment you think necessary" style="  border: 2px solid var(--pink-opacity);"></textarea>
				</div>
				<input type="submit" name="send_message" value="send message" class="buttonAll" style=" border: 2px solid var(--pink-opacity);">
			</form>
		</div>
	</div>
	<div class="address">
		<div class="heading">
            <h1>our contact details</h1>
            <p style="text-align: center;">Reach out to Scoop City using the contact details below—we're here to scoop up some sweet solutions!</p>
            <img src="image/separator-img.png" alt="">
        </div>
		<div class="box-container">
			<div class="box">
				<i class="bx bxs-map-alt"></i>
				<div>
					<h4>address</h4>
					<p>Holy Angel Avenue 2009 Angeles Central Luzon</p>
				</div>
			</div>
			<div class="box">
				<i class="bx bxs-phone-incoming"></i>
				<div>
					<h4>phone number</h4>
					<p>(045) 887 5748</p>
				</div>
			</div>
			<div class="box">
				<i class="bx bxs-envelope"></i>
				<div>
					<h4>email</h4>
					<p>scoopcity@gmail.com</p>
				</div>
			</div>
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