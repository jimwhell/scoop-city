<?php 
	include '../components/connect.php';
	if(isset($_COOKIE['seller_id'])){
      	$seller_id = $_COOKIE['seller_id'];
	} else {
	    $seller_id = '';
	    header('location:login.php');
	}

    $user_id = isset($_GET['id']) ? $_GET['id'] : '';

	if(empty($user_id)){
		header('location:users.php'); // Redirect if no user ID is provided 
		exit();
	}
?>
<style>
	<?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Box Icons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

	<title>Admin - User Transaction Logs</title>

	<style>
		.logs-container {
			width: 90%;
			margin: auto;
			background: #f68a54;
			padding: 9rem;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			border-radius: 10px;
		}
		.logs-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}
		.logs-table th, .logs-table td {
			padding: 10px;
			border: 1px solid #ddd;
			text-align: left;
		}
		.logs-table th {
            background: #f2f2f2;
			color: #000;
		}
		.empty {
			text-align: center;
			color: #888;
			padding: 20px;
		}
	</style>
</head>
<body>
	<div class="main-container">
		<?php include '../components/admin_header.php'; ?>

		<section class="logs-container">
			<div class="heading">
				<h1>User Transaction Logs</h1>
				<img src="../image/separator-img.png" width="100">
			</div>

			<table class="logs-table">
				<tr>
					<th>ID</th>
					<th>User ID</th>
					<th>Action</th>
					<th>Page Visited</th>
					<th>Details</th>
					<th>Timestamp</th>
				</tr>
				<?php 
					$select_logs = $conn->prepare("SELECT * FROM `transaction_logs` WHERE `user_id` = ? ORDER BY `timestamp` DESC");
					$select_logs->execute([$user_id]);
					
					if ($select_logs->rowCount() > 0) {
						while ($log = $select_logs->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td><?= $log['id']; ?></td>
					<td><?= $log['user_id']; ?></td>
					<td><?= $log['action']; ?></td>
					<td><?= $log['page_visited']; ?></td>
					<td><?= $log['details']; ?></td>
					<td><?= date('F, j, Y g:i A', strtotime($log['timestamp']))  ?></td>
				</tr>
				<?php 
						}
					} else {
						echo '<tr><td colspan="6" class="empty">No transactions found.</td></tr>';
					}
				?>
			</table>
		</section>
	</div>

	<!-- SweetAlert CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

	<!-- Custom JS -->
	<script type="text/javascript" src="script.js"></script>

	<?php include '../components/alert.php'; ?>
</body>
</html>
