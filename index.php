<?php 
$title = "CRAS Home";
include 'Components/WebsiteLayout/header.php'; 
include 'Components/WebsiteLayout/navigation.php'; 
include 'Components/WebsiteLayout/loader.php'; 
?>

<section>
   <img src="./images/02.jpeg">
</section>

<section class="introduction">
   <h2>Welcome to CRAS</h2>
   <p>CRAS helps farmers improve their crop management through smart data recording and personalized advisory services.<br>
      The Crop Recording and Advising System (CRAS) is a comprehensive platform designed to 
      assist farmers in Batticaloa by recording detailed agricultural data and providing tailored advisory services to enhance farming practices and productivity.
   </p>
</section>

<section class="features">
   <h2>Our Features</h2>
   <div class="feature">
      <img src="images/record.png" alt="Data Recording Icon">
      <h3>Data Recording</h3>
      <p>Record detailed information about your crops, soil, and farming practices.</p>
   </div>
   <div class="feature">
      <img src="images/advice-icon-design-free-vector.jpg" alt="Advisory Services Icon">
      <h3>Advisory Services</h3>
      <p>Receive tailored advice based on your farm's data and needs.</p>
   </div>
   <div class="feature">
      <img src="images/report.jpg" alt="Reporting Icon">
      <h3>Reporting</h3>
      <p>Generate reports and visualize your farm's performance over time.</p>
   </div>
</section>

<section class="testimonials">
   <h2>Farmer Stories</h2>
   <div class="testimonial">
      <img src="farmer1.jpg" alt="Farmer Image">
      <p>"CRAS has transformed the way I manage my farm. The advice I receive is invaluable."</p>
      <h4>- Farmer Name</h4>
   </div>
   <div class="testimonial">
      <img src="farmer2.jpg" alt="Farmer Image">
      <p>"The data recording feature helps me keep track of everything efficiently."</p>
      <h4>- Farmer Name</h4>
   </div>
</section>

<section class="news-updates">
   <h2>Latest News</h2>
   <article>
      <h3>CRAS Wins Agricultural Innovation Award</h3>
      <p>CRAS was recently recognized for its innovative approach to agricultural management.</p>
      <a href="news.php">Read more</a>
   </article>
   <article>
      <h3>Upcoming Webinar on Crop Management</h3>
      <p>Join our upcoming webinar to learn more about effective crop management strategies.</p>
      <a href="news.php">Read more</a>
   </article>
</section>

<section class="impact">
   <h2>Our Impact</h2>
   <p>CRAS has helped over 1000 farmers in Batticaloa improve their crop yields and farming practices.<br>
      By recording detailed agricultural data and providing personalized advisory services, CRAS has become an essential tool for farmers looking to optimize their operations and achieve greater success in their farming endeavors.
   </p>
</section>

<?php 
include 'Components/WebsiteLayout/footer.php'; 
include 'Components/WebsiteLayout/scripts.php'; 
?>
