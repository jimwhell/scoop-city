<?php  	
include '../components/connect.php'; 	
if(isset($_COOKIE['seller_id'])){       	
    $seller_id = $_COOKIE['seller_id']; 	   
}else{ 	      
    $seller_id = ''; 	      
    header('location:login.php'); 	   
} 

// Handle filtering
$filter_query = "SELECT * FROM `analytics`";
$filter_conditions = [];
$filter_params = [];

if(isset($_GET['browser']) && !empty($_GET['browser'])) {
    $filter_conditions[] = "browser = ?";
    $filter_params[] = $_GET['browser'];
}

if(isset($_GET['operating_system']) && !empty($_GET['operating_system'])) {
    $filter_conditions[] = "operating_system = ?";
    $filter_params[] = $_GET['operating_system'];
}

if(isset($_GET['device_type']) && !empty($_GET['device_type'])) {
    $filter_conditions[] = "device_type = ?";
    $filter_params[] = $_GET['device_type'];
}

if(isset($_GET['country']) && !empty($_GET['country'])) {
    $filter_conditions[] = "country = ?";
    $filter_params[] = $_GET['country'];
}

if(!empty($filter_conditions)) {
    $filter_query .= " WHERE " . implode(" AND ", $filter_conditions);
}

$select_analytics = $conn->prepare($filter_query);
$select_analytics->execute($filter_params);
?>
<style>
	<?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- box icon cdn link  -->    
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin - Analytics Dashboard</title>
    <style>
        <?php include '../css/admin_style.css'; ?>
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
        }
        .filter-container select, .filter-container input {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .analytics-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }        
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        
        <section class="analytics">
            <div class="heading">
                <h1>Site Analytics</h1>
                <img src="../image/separator-img.png" width="100">
            </div>

            <!-- Filtering Form -->
            <form method="GET" class="filter-container">
                <select name="browser">
                    <option value="">All Browsers</option>
                    <option value="Chrome">Chrome</option>
                    <option value="Firefox">Firefox</option>
                    <option value="Safari">Safari</option>
                </select>

                <select name="operating_system">
                    <option value="">All OS</option>
                    <option value="Windows 10">Windows 10</option>
                    <option value="MacOS">MacOS</option>
                    <option value="Linux">Linux</option>
                </select>

                <select name="device_type">
                    <option value="">All Devices</option>
                    <option value="Desktop">Desktop</option>
                    <option value="Mobile">Mobile</option>
                    <option value="Tablet">Tablet</option>
                </select>

                <select name="country">
                    <option value="">All Countries</option>
                    <option value="United States">United States</option>
                    <option value="Canada">Canada</option>
                    <option value="UK">UK</option>
                </select>

                <button type="submit" class="btn">Apply Filters</button>
                <a href="?" class="btn">Reset</a>
            </form>

            <div class="analytics-grid">
                <!-- Data Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Browser</th>
                                <th>OS</th>
                                <th>Device</th>
                                <th>IP Address</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($select_analytics->rowCount() > 0) { 
                                while($fetch_analytics = $select_analytics->fetch(PDO::FETCH_ASSOC)){ 
                            ?>
                            <tr>
                                <td><?= $fetch_analytics['id'] ?></td>
                                <td><?= $fetch_analytics['browser'] ?></td>
                                <td><?= $fetch_analytics['operating_system'] ?></td>
                                <td><?= $fetch_analytics['device_type'] ?></td>
                                <td><?= $fetch_analytics['ip_address'] ?></td>
                                <td><?= $fetch_analytics['city'] ?></td>
                                <td><?= $fetch_analytics['country'] ?></td>
                                <td><?= $fetch_analytics['created_at'] ?></td>
                            </tr>
                            <?php 
                                } 
                            } else { 
                                echo '<tr><td colspan="8" style="text-align:center;">No analytics data found</td></tr>'; 
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Visualization Containers -->
                <div class="chart-container">
                    <h3>Device Type Distribution</h3>
                    <canvas id="deviceChart"></canvas>
                    
                    <h3>Browser Usage</h3>
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Device Type Chart
        <?php
        // Aggregate device type data
        $device_query = $conn->prepare("SELECT device_type, COUNT(*) as count FROM `analytics` GROUP BY device_type");
        $device_query->execute();
        $device_data = $device_query->fetchAll(PDO::FETCH_ASSOC);
        ?>
        var deviceCtx = document.getElementById('deviceChart').getContext('2d');
        var deviceChart = new Chart(deviceCtx, {
            type: 'pie',
            data: {
                labels: [<?php echo implode(',', array_map(function($row) { return '"'.$row['device_type'].'"'; }, $device_data)); ?>],
                datasets: [{
                    data: [<?php echo implode(',', array_map(function($row) { return $row['count']; }, $device_data)); ?>],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        // Browser Usage Chart
        <?php
        // Aggregate browser data
        $browser_query = $conn->prepare("SELECT browser, COUNT(*) as count FROM `analytics` GROUP BY browser");
        $browser_query->execute();
        $browser_data = $browser_query->fetchAll(PDO::FETCH_ASSOC);
        ?>
        var browserCtx = document.getElementById('browserChart').getContext('2d');
        var browserChart = new Chart(browserCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', array_map(function($row) { return '"'.$row['browser'].'"'; }, $browser_data)); ?>],
                datasets: [{
                    data: [<?php echo implode(',', array_map(function($row) { return $row['count']; }, $browser_data)); ?>],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            }
        });
    </script>

    <!-- sweetalert cdn link  --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>  
    <!-- custom js link  --> 
    <script type="text/javascript" src="script.js"></script>  
    <?php include '../components/alert.php'; ?> 
</body>
</html>