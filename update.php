<?php
/**
 * Update Product Handler
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
    $product->id = $_POST['id'] ?? '';
    $product->name = $_POST['name'] ?? '';
    $product->price = $_POST['price'] ?? '';
    $product->category = $_POST['category'] ?? '';

    // Validate
    $errors = $product->validate();

    if(empty($errors)) {
        // Update product
        if($product->update()) {
            $_SESSION['message'] = 'Product updated successfully!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Unable to update product. Please try again.';
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
