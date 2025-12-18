<?php
/**
 * Delete Product Handler
 */
session_start();
require_once 'config/database.php';
require_once 'includes/Product.php';
require_once 'includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize database and product
    $database = new Database();
    $db = $database->getConnection();
    $product = new Product($db);

    // Set product ID
    $product->id = $_POST['id'] ?? '';

    if(!empty($product->id)) {
        // Delete product
        if($product->delete()) {
            $_SESSION['message'] = 'Product deleted successfully!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Unable to delete product. Please try again.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Invalid product ID.';
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION['message'] = 'Invalid request method.';
    $_SESSION['message_type'] = 'danger';
}

redirect('index.php');
?>
