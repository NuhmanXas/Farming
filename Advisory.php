<?php 
$title = "CRAS Home";
include 'Components/header.php'; 
include 'Components/navigation.php'; 
include 'Components/loader.php'; 
?>
       
      <main>
        <section id="crop-selector">
          <h2>Select Your Crop</h2>
          <select id="crop-type">
            <option value="">Select Crop</option>
            <option value="wheat">Wheat</option>
            <option value="corn">Corn</option>
            <option value="rice">Rice</option>
            <!-- Add more crop options here -->
          </select>
        </section>
    
        <section id="crop-info" style="display: none;">
          <h2>Crop Information</h2>
          <p id="crop-description">Crop description will appear here.</p>
          <p><strong>Recommended Cropping Practices:</strong></p>
          <ul id="crop-practices"></ul>
          <p><strong>Estimated Cost:</strong></p>
          <p id="cost-estimate"></p>
        </section>
    </main>

   
  <?php 
include 'Components/footer.php'; 
include 'Components/scripts.php'; 
?>