<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .summary-box {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .summary-box:hover {
            transform: translateY(-5px);
        }

        .chart-card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #ffffff;
        }

        .chart-card-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="content p-3">
            <h1 class="text-center my-4">Welcome to the Dashboard</h1>

            <!-- Summary Information Row -->
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="summary-box">
                        <h3>Total Farmers</h3>
                        <h4 id="totalFarmers" class="display-4">Loading...</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-box">
                        <h3>Total Crops</h3>
                        <h4 id="totalCrops" class="display-4">Loading...</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-box">
                        <h3>Total Tasks</h3>
                        <h4 id="totalTasks" class="display-4">Loading...</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-box">
                        <h3>Total Varieties</h3>
                        <h4 id="totalVarieties" class="display-4">Loading...</h4>
                    </div>
                </div>
            </div>

            <!-- Chart Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>Number of Farmers by Farm Size</h4>
                        </div>
                        <div id="farmersChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>Number of Varieties per Crop</h4>
                        </div>
                        <div id="cropsChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Additional Charts Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>Task Distribution Over Time</h4>
                        </div>
                        <div id="tasksChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>Fertilization Methods by Variety</h4>
                        </div>
                        <div id="fertilizationChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawFarmersChart();
            drawCropsChart();
            drawTasksChart();
            drawFertilizationChart();
            fetchSummaryData();
        }

        function drawFarmersChart() {
            var data = google.visualization.arrayToDataTable([
                ['Farm Size', 'Number of Farmers'],
                <?php
                // Fetch farmers data
                $conn = new mysqli('localhost', 'root', '', 'CRAS_DB');
                $query = "SELECT farm_size, COUNT(*) AS num_farmers FROM Farmers GROUP BY farm_size";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "['".$row['farm_size']."', ".$row['num_farmers']."],";
                }
                ?>
            ]);

            var options = {
                pieHole: 0.4,
                colors: ['#007bff', '#6610f2', '#6f42c1', '#e83e8c'],
                chartArea: {width: '90%', height: '80%'},
                legend: {position: 'right', alignment: 'center'}
            };

            var chart = new google.visualization.PieChart(document.getElementById('farmersChart'));
            chart.draw(data, options);
        }

        function drawCropsChart() {
            var data = google.visualization.arrayToDataTable([
                ['Crop Name', 'Number of Varieties'],
                <?php
                // Fetch crops data
                $query = "SELECT C.crop_name, COUNT(V.variety_id) AS num_varieties 
                          FROM Crops C 
                          LEFT JOIN Varieties V ON C.crop_id = V.crop_id 
                          GROUP BY C.crop_name";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "['".$row['crop_name']."', ".$row['num_varieties']."],";
                }
                ?>
            ]);

            var options = {
                colors: ['#28a745'],
                chartArea: {width: '70%', height: '70%'},
                hAxis: {title: 'Crops'},
                vAxis: {title: 'Varieties'},
                bar: {groupWidth: "80%"},
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('cropsChart'));
            chart.draw(data, options);
        }

        function drawTasksChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task Date', 'Number of Tasks'],
                <?php
                // Fetch tasks data
                $query = "SELECT task_date, COUNT(*) AS num_tasks FROM Tasks GROUP BY task_date";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "['".$row['task_date']."', ".$row['num_tasks']."],";
                }
                ?>
            ]);

            var options = {
                colors: ['#fd7e14'],
                chartArea: {width: '70%', height: '70%'},
                hAxis: {title: 'Date'},
                vAxis: {title: 'Number of Tasks'},
                lineWidth: 3
            };

            var chart = new google.visualization.LineChart(document.getElementById('tasksChart'));
            chart.draw(data, options);
        }

        function drawFertilizationChart() {
            var data = google.visualization.arrayToDataTable([
                ['Variety', 'Number of Fertilization Methods'],
                <?php
                // Fetch fertilization data
                $query = "SELECT V.variety_name, COUNT(F.fertilization_id) AS num_methods 
                          FROM Varieties V 
                          LEFT JOIN Fertilization F ON V.variety_id = F.variety_id 
                          GROUP BY V.variety_name";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "['".$row['variety_name']."', ".$row['num_methods']."],";
                }
                ?>
            ]);

            var options = {
                colors: ['#17a2b8'],
                chartArea: {width: '70%', height: '70%'},
                hAxis: {title: 'Varieties'},
                vAxis: {title: 'Fertilization Methods'},
                bar: {groupWidth: "80%"},
            };

            var chart = new google.visualization.BarChart(document.getElementById('fertilizationChart'));
            chart.draw(data, options);
        }

        function fetchSummaryData() {
            <?php
            // Fetch summary data
            $totalFarmers = $conn->query("SELECT COUNT(*) AS total FROM Farmers")->fetch_assoc()['total'];
            $totalCrops = $conn->query("SELECT COUNT(*) AS total FROM Crops")->fetch_assoc()['total'];
            $totalTasks = $conn->query("SELECT COUNT(*) AS total FROM Tasks")->fetch_assoc()['total'];
            $totalVarieties = $conn->query("SELECT COUNT(*) AS total FROM Varieties")->fetch_assoc()['total'];
            ?>
            document.getElementById('totalFarmers').innerText = '<?php echo $totalFarmers; ?>';
            document.getElementById('totalCrops').innerText = '<?php echo $totalCrops; ?>';
            document.getElementById('totalTasks').innerText = '<?php echo $totalTasks; ?>';
            document.getElementById('totalVarieties').innerText = '<?php echo $totalVarieties; ?>';
        }
    </script>
</body>
</html>
