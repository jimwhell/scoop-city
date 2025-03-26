<?php
// Ensure this is only included once to prevent multiple logs per page load
if (!defined('ANALYTICS_LOGGED')) {
    define('ANALYTICS_LOGGED', true);

    // Include database connection
    require_once 'components/connect.php'; // Ensure this provides a valid $conn PDO instance

    // Include UserDetails class
    require_once 'user_details.php';

    // Create an instance of UserDetails
    $userDetails = new UserDetails();

    // Prepare data to insert
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = $userDetails->get_browser();
    $operating_system = $userDetails->get_os();
    $device_type = $userDetails->get_device();
    $ip_address = $userDetails->get_ip();
    $location = $userDetails->get_country();

    try {
        // Check if the visitor has already been logged (using IP address)
        $stmt = $conn->prepare("SELECT id FROM analytics WHERE ip_address = :ip LIMIT 1");
        $stmt->bindParam(':ip', $ip_address, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 0) { // Only log if no existing record is found
            $stmt = $conn->prepare("INSERT INTO analytics 
                (user_agent, browser, operating_system, device_type, ip_address, city, country) 
                VALUES (:user_agent, :browser, :os, :device_type, :ip, :city, :country)");

            // Bind parameters using PDO method
            $stmt->bindParam(':user_agent', $user_agent, PDO::PARAM_STR);
            $stmt->bindParam(':browser', $browser, PDO::PARAM_STR);
            $stmt->bindParam(':os', $operating_system, PDO::PARAM_STR);
            $stmt->bindParam(':device_type', $device_type, PDO::PARAM_STR);
            $stmt->bindParam(':ip', $ip_address, PDO::PARAM_STR);
            $stmt->bindParam(':city', $location['city'], PDO::PARAM_STR);
            $stmt->bindParam(':country', $location['country'], PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();
        }
    } catch (PDOException $e) {
        // Log error or handle silently
        error_log("Analytics logging error: " . $e->getMessage());
    }
}
?>
