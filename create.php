<?php
/**
 * Create Product Handler
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

    // Set product properties
    $product->name = $_POST['name'] ?? '';
    $product->price = $_POST['price'] ?? '';
    $product->category = $_POST['category'] ?? '';

    // Validate
    $errors = $product->validate();

    if(empty($errors)) {
        // Create product
        if($product->create()) {
            $_SESSION['message'] = 'Product created successfully!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Unable to create product. Please try again.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION['message'] = 'Invalid request method.';
    $_SESSION['message_type'] = 'danger';
}

redirect('index.php');
?>
