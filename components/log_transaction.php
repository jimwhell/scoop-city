<?php
require_once './components/connect.php'; 

function logActivity($user_id, $action, $page_visited, $details) {
    global $conn; 

    try {
        $stmt = $conn->prepare("INSERT INTO transaction_logs (user_id, action, page_visited, details) 
                                VALUES (:user_id, :action, :page_visited, :details)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':page_visited', $page_visited, PDO::PARAM_STR);
        $stmt->bindParam(':details', $details, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Audit Log Error: " . $e->getMessage());
    }
}
?>
