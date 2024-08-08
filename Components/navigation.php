<?php
// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header>
   <div class="header">
      <div class="container">
         <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
               <div class="full">
                  <div class="center-desk">
                     <div class="logo"> 
                        <a href="index.php"><img src="images/Logo_cras.JPG" alt="logo"/></a> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-9 col-sm-9">
               <div class="menu-area">
                  <div class="limit-box">
                     <nav class="main-menu">
                        <ul class="menu-area-main">
                           <li class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>"> <a href="index.php">Home</a> </li>
                           <li class="<?php echo $currentPage == 'batticaloa.php' ? 'active' : ''; ?>"> <a href="batticaloa.php">Batticaloa</a> </li>
                           <li class="<?php echo $currentPage == 'about.php' ? 'active' : ''; ?>"> <a href="about.php">Recording</a> </li>
                           <li class="<?php echo $currentPage == 'advisory.php' ? 'active' : ''; ?>"> <a href="advisory.php">Advising</a> </li>
                           <li class="<?php echo $currentPage == 'signup.php' ? 'active' : ''; ?> mean-last"> <a href="signup.php">Signup</a> </li>
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
   </div>
</header>
