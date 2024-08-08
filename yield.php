<!DOCTYPE html>
<html lang="en">
<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- site metas -->
   <title>Yield Record</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- style css -->
   <link rel="stylesheet" href="css/style.css">
   <!-- Responsive-->
   <link rel="stylesheet" href="css/responsive.css">
   <!-- favicon -->
   <link rel="icon" href="images/fevicon.png" type="image/gif" />
   <!-- Scrollbar Custom CSS -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <!-- Tweaks for older IEs-->
   <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
   
</head>
<body class="main-layout">

   <!-- loader -->
   <div class="loader_bg">
      <div class="loader"><img src="images/200w.webp" alt="Loading..." /></div>
   </div>
   <!-- end loader -->

   <!-- header -->
   <header>
      <div class="container">
         <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 logo_section">
               <div class="full">
                  <div class="center-desk">
                     <div class="logo">
                        <a href="index.html"><img src="images/Logo_cras.JPG" alt="logo"/></a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-9 col-sm-9">
               <div class="menu-area">
                  <div class="limit-box">
                     <nav class="main-menu">
                        <ul class="menu-area-main">
                           <li class="active"><a href="index.html">Home</a></li>
                           <li><a href="batticaloa.html">Batticaloa</a></li>
                           <li><a href="about.html">Recording</a></li>
                           <li><a href="Advisory.html">Advising</a></li>
                           <li class="mean-last"><a href="Signup.html">Signup</a></li>
                        </ul>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
               <a class="buy" href="#">Login</a>
            </div>
         </div>
      </div>
   </header>
   <!-- end header -->

   

   <!-- main content -->
   <div class="brand_color">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="titlepage">
                  <h2>Summerize Your Cropping Journey</h2>
               </div>
            </div>
         </div>
      </div>
   </div>

   
   <section class="advisory-form-container">
    <form action="submit_advisory.php" method="POST" class="advisory-form">
       <div class="form-group">
          <label for="Establishing-Date">Establishing Date:</label>
          <input type="date" id="Establishing-Date" name="Establishing-Date" required>
       </div>
        <h3>Farmer Information</h3>
        <div class="form-group">
            <label for="cropping-no">Cropping No:</label>
            <input type="text" id="cropping-no" name="cropping_no" required>
        </div>

        <div class="form-group">
            <label for="crop-id">Select Crop Id:</label>
            <select id="crop-id" name="crop_id" required>
                <!-- Options -->
            </select>
        </div>

        <div class="form-group">
            <label for="allocated-area">Allocated Area (acres):</label>
            <input type="text" id="allocated-area" name="allocated_area" required>
        </div>

        <h3>Crop Details</h3>
        <div class="form-group">
            <label for="total-cost">Total Cost (Rs.):</label>
            <input type="text" id="total-cost" name="total_cost" required>
        </div>

        <div class="form-group">
          <label for="Harvesting-Date">Harvesting Date:</label>
          <input type="date" id="Harvesting-Date" name="Harvesting-Date" required>
       </div>

        <div class="form-group">
            <label for="yield">Yield (in kg/ac):</label>
            <input type="text" id="yield" name="yield" required>
        </div>

        <div class="form-group">
            <label for="revenue">Revenue (Rs.):</label>
            <input type="text" id="revenue" name="revenue" required>
        </div>

        <div class="form-group">
            <label for="profit">Profit (Rs.):</label>
            <input type="text" id="profit" name="profit" required>
        </div>

        <div class="form-actions">
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
        </div>
    </form>
</section>

  <footer class="footer">
   <div class="container">
       <div class="footer-content">
           <div class="footer-section about">
               <h2>CRAS</h2>
               <p>Empowering Farmers with Smart Agricultural Solutions</p>
           </div>
           
           <div class="footer-section contact-form">
               <h2>Contact Us</h2>
               <form action="#">
                   <input type="email" name="email" placeholder="Your email address..." required>
                   <textarea name="message" placeholder="Your message..." required></textarea>
                   <button type="submit">Send</button>
               </form>
           </div>
       </div>
       <div class="footer-bottom">
           <p>&copy; 2024 CRAS | Designed by Mayurie Shankar</p>
       </div>
   </div>
</footer>

   

   <!-- Javascript files -->
   <script src="js/jquery.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>
   <script src="js/jquery-3.0.0.min.js"></script>
   <script src="js/plugin.js"></script>
   <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
   <script src="js/custom.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
   <script>
      $(document).ready(function(){
         $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
         });
         
         $(".zoom").hover(function(){
            $(this).addClass('transition');
         }, function(){
            $(this).removeClass('transition');
         });
      });

      document.getElementById('cropForm').addEventListener('submit', function(event) {
         let isValid = true;
         
         // Check if all required fields are filled
         const inputs = document.querySelectorAll('input[required], select[required]');
         inputs.forEach(input => {
            if (!input.value) {
               isValid = false;
               input.style.borderColor = '#ff6b6b'; // Red border for invalid input
            } else {
               input.style.borderColor = '#ddd'; // Reset border color for valid input
            }
         });
         
         if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
            alert('Please fill out all required fields.');
         }
      });
   </script>
</body>
</html>
