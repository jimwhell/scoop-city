<?php  
include '../components/connect.php';  

if(isset($_COOKIE['seller_id'])){     
    $seller_id = $_COOKIE['seller_id']; 
} else {     
    $seller_id = '';     
    header('location:login.php');     
    exit(); 
}  

// Debugging: Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Fetch ALL logs
    $query = "SELECT * FROM user_logs ORDER BY timestamp DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    die("Error fetching logs: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <style>
        <?php include '../css/admin_style.css'; ?>
        .logs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .logs-table th, .logs-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .logs-table th {
            background-color: #f2f2f2;
        }
        .login-action { color: green; }
        .logout-action { color: red; }
    </style>
</head>
<body>
<div class="main-container">
    <?php include '../components/admin_header.php'; ?>
    
    <section class="user-logs">
        <div class="heading">
            <h1>User Login/Logout Logs</h1>
            <img src="../image/separator-img.png" width="100">
        </div>

        <?php if (!empty($logs)): ?>
            <div class="table-container">
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Action</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']); ?></td>
                            <td><?= htmlspecialchars($log['user_id']); ?></td>
                            <td class="<?= $log['action'] == 'login' ? 'login-action' : 'logout-action'; ?>">
                                <?= ucfirst(htmlspecialchars($log['action'])); ?>
                            </td>
                            <td><?= htmlspecialchars($log['timestamp']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No user logs found.</p>
        <?php endif; ?>
    </section>
</div>

<script type="text/javascript" src="script.js"></script>
</body>
</html>