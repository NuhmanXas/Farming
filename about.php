<?php 
$title = "CRAS Home";
include 'Components/header.php'; 
include 'Components/navigation.php'; 
include 'Components/loader.php'; 
?>


   <!-- main content -->
   <div class="brand_color">
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="titlepage">
                  <h2>Record your Farming Journey</h2>
               </div>
            </div>
         </div>
      </div>
   </div>
   
   

   <div class="feature">
      <img src="C:/Users/dell/Desktop/CRAS_NEW/lighten-1.0.0/images/analysis.webp" width="1200px"><br><br>
      <h1>Introduction to CRAS's Recording Area</h1>
      <p>The Crop Recording and Advising System (CRAS) is designed to empower farmers by providing a comprehensive platform for detailed record-keeping and analysis. The recording area of CRAS is an essential component that facilitates the meticulous documentation of various farming activities, helping farmers maintain accurate and organized records of their agricultural practices.</p>
      
      <h2>Benefits of Using CRAS's Recording Area</h2>
      <div class="benefits">
          <ul>
              <li><strong>Enhanced Tracking</strong>: Maintain detailed records to track farming activities more effectively. Identify patterns, understand the impact of different practices, and make data-driven decisions for future farming cycles.</li>
              <li><strong>Future Planning</strong>: Use recorded data as a valuable reference for future farming endeavors. Analyze past records to develop better strategies, optimize resource usage, and improve crop yields.</li>
              <li><strong>Improved Study and Analysis</strong>: Facilitate thorough study and analysis of farming activities. Evaluate the success of various practices, experiment with new techniques, and continuously refine the approach to achieve better results.</li>
          </ul>
      </div>

      <h2>Key Features of CRAS Recording Area</h2>
      <div class="card mb-3">
         <img src="C:/Users/dell/Desktop/CRAS_NEW/lighten-1.0.0/images/farming_practise.jpg" class="card-img-top" alt="...">
         <div class="card-body">
           <h5 class="card-title">Recording your farming practices during cropping</h5>
           <p class="card-text">Document all farming tasks such as planting, irrigation, pest control, and harvesting. Keeping track of these tasks allows farmers to analyze routines, identify areas for improvement, and ensure timely and efficient completion of necessary activities</p>
           <a href="task.html" class="btn btn-primary btn-lg"> Record your farming practices </a>
         </div>
       </div>
       
       <div class="card">
         <img src="C:/Users/dell/Desktop/CRAS_NEW/lighten-1.0.0/images/harvesting.jpg" class="card-img-bottom" alt="...">
         <div class="card-body">
           <h5 class="card-title">Record your yield</h5>
           <p class="card-text">Get a holistic view of the farming journey by integrating data from various activities, including weather conditions, crop growth stages, yield outcomes, and challenges faced. Comprehensive documentation aids in informed decision-making, improving crop management practices, and enhancing overall productivity</p>
          
            <a href="yield.html" class="btn btn-primary btn-lg">Record the yield of cropping</a>
         </div>
       </div>
     
  <?php 
include 'Components/footer.php'; 
include 'Components/scripts.php'; 
?>