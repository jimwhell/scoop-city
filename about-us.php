<?php

   include 'components/connect.php';
   require_once './components/log_transaction.php';
   include './components/log_analytics.php'; 


   if(isset($_COOKIE['user_id'])){
      $user_id = $_COOKIE['user_id'];
   }else{
      $user_id = '';
      
   }

   $page_visited = $_SERVER['REQUEST_URI'];
	logActivity($user_id, 'READ', $page_visited, 'User viewed about us page.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/user_style.css">
    <title>Scoop City - About Us</title>
    <link rel="icon" type="image/x-icon" href="image/favicon.png">
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <div class="banner-about">
        
        <div class="detail">
            
            <h1>&nbsp;&nbsp;&nbsp;about us</h1>
        </div>
    </div>

    <div class="story">
        <div class="heading">
            <h1>Our Story</h1>
            <img src="image/separator-img.png" alt="">
        </div>
            <div class="desktop">
                <p>Back in their college days at Holy Angel University, Gab, Ragie, Lance, and Jessica dreamed of bringing joy to their <br>community through their shared love of ice cream. Despite their limited resources and busy schedules,<br> they embarked on a journey to turn their dream into reality, pooling their savings and creativity to open<br> "Scoop City" from scratch. Through late nights of planning, countless taste tests, and unwavering<br> determination, they transformed their humble beginnings into a beloved local institution,<br> leaving a sweet legacy that continues to thrive to this day.</p>
                <a href="menu.php"><button class="buttonAll">our services</button></a>
             </div>

             <div class="mobile"> 
                <p>In their college days at Holy Angel University, Gab, Ragie, Lance,<br> and Jessica shared a dream: to spread joy in their community<br> through ice cream. Despite limited resources and busy schedules,<br> they pooled their savings and creativity to open "Scoop City" from scratch.<br> Through late nights of planning, taste tests, and determination,<br> they turned their humble beginnings into a beloved<br> local institution, leaving a sweet, thriving legacy.</p>
            </div>
        
    </div>
    <!-- -----------------about-us----------------- -->
    <!-- <div class="container-about-bg"> -->
    <div class="container">
        <div class="box-container">
            <div class="img-box">
                <img src="image/icecrush.png" alt="">
        </div>
            <div class="box">
                <div class="heading">
                    <h1>Taking Ice Cream To New Heights</h1>
                    <img src="image/separator-img.png" alt="">
                </div>
                <p>Scoop City have a different range of ice cream products, catering to a variety of tastes and preferences. Whether you crave classic flavors or adventurous combinations, there's a scoop to satisfy your sweet tooth. Additionally, Scoop City offers dairy-free alternatives, ensuring that everyone can indulge in their delicious treats. From scoops in cups to cones, each product is crafted with care and creativity to provide a memorable dessert experience.</p>
                <div class="desktop">
                <a href="#team"><button class="buttonAll">learn more</button></a>
                </div>
            </div>
        </div>
<!-- </div> -->
        
    </div>
    <!-- -----------------team----------------- -->
    <div class="team" id="team">
        <div class="heading">
            <span>our team</span>
            <h1>Quality & Passion with our Services!</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/jg.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>John Gabriel Perez</h2>
                    <p>UI/UX Designer</p>
                </div>
            </div>
            <div class="box">
                <img src="image/lens.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Isaiah Talens</h2>
                    <p>Ui/Ux Designer</p>
                </div>
            </div>
            <div class="box">
                <img src="image/jess.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Jessica Hanschke</h2>
                    <p>Documentation</p>
                </div>
            </div>
            <div class="box">
                <img src="image/cabs.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Gian Santos</h2>
                    <p>Documentation</p>
                </div>
            </div>
            <div class="box">
                <img src="image/raggie.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Ragie Almachar</h2>
                    <p>Back End Developer</p>
                </div>
            </div>
            <div class="box">
                <img src="image/jimmy.jpg" alt="" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Jimwell Manalo</h2>
                    <p>Front End Developer</p>
                </div>
            </div>
    
   
        </div>
    </div>
    <!-- mission -->
    <div class="standers">
        <div class="detail">
            <div class="heading">
                <h1>Our Mission</h1>
                <img src="image/separator-img.png" alt="">
            </div>
            
            <div class="desktop">
                <p>Our mission is to delight our customers with delicious, handcrafted ice cream while making a sense of community. </p>
                <p>We are committed to using high-quality ingredients, supporting local suppliers, and promoting sustainability in our practices.</p>
                <p>Through every scoop, we aim to create moments of joy and connection for our customers.</p>
            </div>

            <div class="mobile">
                <p>Our mission is to delight our customers with delicious,<br> handcrafted ice cream while making a sense of community. </p>
                <p>We are committed to using high-quality ingredients,<br> supporting local suppliers, and<br> promoting sustainability in our practices.</p>
                <p>Through every scoop, we aim to create moments of<br> joy and connection for our customers.</p>
            </div>  
        </div>
    </div>

    <!-- -----------------testimonial----------------- -->
    <div class="desktop">
    <div class="testimonial">
        <div class="heading">
            <h1>testimonial</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="testimonial-container">
            <div class="slide-row" id="slide">
              <div class="slide-col">
                    <div class="user-text">
                        <p>Scoop City exceeded all my expectations with their incredible flavors and friendly service. The Scoopy Supreme was an absolute delight, bursting with rich chocolate brownie and caramel swirls. It's now my favorite spot for indulging in creamy, delicious treats.</p>
                        <h2>Benedict</h2>
                        <p>Student</p>
                    </div>
                    <div class="user-img">
                        <img src="image/JBS.jpg" alt="avatar">
                    </div>
                </div>
    
                <div class="slide-col">
                    <div class="user-text">
                        <p>Scoop City is a hidden gem! Their innovative flavors like Mango Tango and Matcha Madness are a must-try for any ice cream lover. The cozy atmosphere and friendly staff make every visit a delight.</p>
                        <h2>Phoenix</h2>
                        <p>Student</p>
                    </div>
                    <div class="user-img">
                        <img src="image/lens.jpg" alt="avatar">
                    </div>
                </div>
    
                <div class="slide-col">
                    <div class="user-text">
                        <p>As a lactose-intolerant, finding Scoop City was a dream come true! Their dairy-free options are some of the best I've ever tasted, especially the Coconut Bliss and Almond Joy flavors. I'll definitely be coming back for more scoops of dairy-free happiness!</p>
                        <h2>Carl</h2>
                        <p>Student</p>
                    </div>
                    <div class="user-img">
                        <img src="image/Carl.jpg" alt="avatar">
                    </div>
                </div>
              
               <div class="slide-col">
                    <div class="user-text">
                        <p>Scoop Cityis a true family favorite! The kids love the playful flavors like Bubblegum Blast and Cotton Candy Carnival, while the adults can't get enough of the options like Midnight Mocha and Pistachio. It's the perfect place for a sweet treat that everyone can enjoy together.</p>
                        <h2>Achaia</h2>
                        <p>Graphic Designer</p>
                    </div>
                    <div class="user-img">
                        <img src="image/akeya.jpg" alt="avatar">
                    </div>
                </div>

            </div>
        </div>
        
        <div class="indicator">
            <span class="btn1 active"></span>
            <span class="btn1"></span>
            <span class="btn1"></span>
            <span class="btn1"></span>
        </div>
    </div>
    </div>
   
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>