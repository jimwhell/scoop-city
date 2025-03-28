<?php   
include '../components/connect.php';  

if(isset($_COOKIE['seller_id'])){          
    $seller_id = $_COOKIE['seller_id'];  
} else {          
    $seller_id = '';          
    header('location:login.php');          
    exit();  
}

$user_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($user_id)) {
    header('location:dashboard.php');
}

error_reporting(E_ALL); 
ini_set('display_errors', 1);  

try {     
    // Fetch logs with user names by joining user_logs with users table
    $query = "SELECT 
    ul.id, 
    ul.user_id, 
    u.name AS user_name, 
    ul.action, 
    ul.timestamp 
  FROM user_logs ul
  LEFT JOIN users u ON ul.user_id = u.id
  WHERE ul.user_id = :user_id
  ORDER BY ul.timestamp DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);  
} catch(PDOException $e) {     
    error_log($e->getMessage());     
    die("Error fetching logs: " . $e->getMessage()); 
} 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>     
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
                            <th>User Name</th>                             
                            <th>Action</th>                             
                            <th>Timestamp</th>                         
                        </tr>                     
                    </thead>                     
                    <tbody>                         
                        <?php foreach($logs as $log): ?>                         
                        <tr>                             
                            <td><?= htmlspecialchars($log['id']); ?></td>                             
                            <td><?= htmlspecialchars($log['user_id']); ?></td>                             
                            <td><?= htmlspecialchars($log['user_name'] ?? 'Unknown'); ?></td>                             
                            <td class="<?= $log['action'] == 'login' ? 'login-action' : 'logout-action'; ?>">                                 
                                <?= ucfirst(htmlspecialchars($log['action'])); ?>                             
                            </td>                             
                            <td><?= date("F, j, Y, g:i A", strtotime(($log['timestamp']))); ?></td>                         
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