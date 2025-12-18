<?php
/**
 * Main Page - Display Products and Chart
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/database.php';
require_once 'includes/Product.php';
require_once 'includes/functions.php';

// Initialize database and product object
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Handle messages from session
$message = '';
if(isset($_SESSION['message'])) {
    $message = displayAlert($_SESSION['message'], $_SESSION['message_type'] ?? 'success');
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Get sorting parameters
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Fetch all products with sorting using the Product method
$stmt = $product->readWithSort($sortColumn, $sortOrder);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get category statistics for chart
$categoryStats = $product->getCategoryStats();

// Function to generate sort URL
function getSortUrl($column, $currentColumn, $currentOrder) {
    $newOrder = 'ASC';
    if ($column === $currentColumn && $currentOrder === 'ASC') {
        $newOrder = 'DESC';
    }
    return '?sort=' . $column . '&order=' . $newOrder;
}

// Function to get sort icon
function getSortIcon($column, $currentColumn, $currentOrder) {
    if ($column !== $currentColumn) {
        return '<i class="bi bi-arrow-down-up"></i>';
    }
    return $currentOrder === 'ASC' ? '<i class="bi bi-arrow-up"></i>' : '<i class="bi bi-arrow-down"></i>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .page-title {
            color: #667eea;
            margin-bottom: 30px;
            font-weight: bold;
            text-align: center;
            font-size: 2.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(71, 99, 255, 0.2);
        }
        .table-container {
            margin-top: 30px;
            overflow-x: auto;
        }
        .table {
            background: white;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            
        }
        .table thead th {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 30px;
        }
        .table thead th:hover {
            background: rgba(71, 99, 255, 0.2);
        }
        .table thead th a {
            
            text-decoration: none;
            display: block;
        }
        .table thead th i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
        }
        .table thead th.sortable:hover {
            opacity: 0.4;
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
        .chart-container {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title"><i class="bi bi-box-seam"></i> Product Management System</h1>
        
        <?php echo $message; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Product List</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Add New Product
            </button>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="sortable">
                            <a href="<?php echo getSortUrl('id', $sortColumn, $sortOrder); ?>">
                                ID
                                <?php echo getSortIcon('id', $sortColumn, $sortOrder); ?>
                            </a>
                        </th>
                        <th class="sortable">
                            <a href="<?php echo getSortUrl('name', $sortColumn, $sortOrder); ?>">
                                Name
                                <?php echo getSortIcon('name', $sortColumn, $sortOrder); ?>
                            </a>
                        </th>
                        <th class="sortable">
                            <a href="<?php echo getSortUrl('price', $sortColumn, $sortOrder); ?>">
                                Price
                                <?php echo getSortIcon('price', $sortColumn, $sortOrder); ?>
                            </a>
                        </th>
                        <th class="sortable">
                            <a href="<?php echo getSortUrl('category', $sortColumn, $sortOrder); ?>">
                                Category
                                <?php echo getSortIcon('category', $sortColumn, $sortOrder); ?>
                            </a>
                        </th>
                        <th class="sortable">
                            <a href="<?php echo getSortUrl('created_at', $sortColumn, $sortOrder); ?>">
                                Created
                                <?php echo getSortIcon('created_at', $sortColumn, $sortOrder); ?>
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($products) > 0): ?>
                        <?php foreach($products as $item): ?>
                        <tr>
                            <td><?php echo escape($item['id']); ?></td>
                            <td><?php echo escape($item['name']); ?></td>
                            <td><?php echo formatPrice($item['price']); ?></td>
                            <td><span class="badge bg-info"><?php echo escape($item['category']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-warning edit-btn" 
                                        data-id="<?php echo $item['id']; ?>"
                                        data-name="<?php echo escape($item['name']); ?>"
                                        data-price="<?php echo $item['price']; ?>"
                                        data-category="<?php echo escape($item['category']); ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" 
                                        data-id="<?php echo $item['id']; ?>"
                                        data-name="<?php echo escape($item['name']); ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No products found. Add your first product!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Visualization Chart -->
        <div class="chart-container">
            <h4 class="mb-3"><i class="bi bi-bar-chart"></i> Products by Category</h4>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="create.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" required maxlength="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="update.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="edit_category" name="category" required maxlength="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="delete.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="delete_id" name="id">
                        <p>Are you sure you want to delete <strong id="delete_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Populate Edit Modal
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    document.getElementById('edit_id').value = this.getAttribute('data-id');
                    document.getElementById('edit_name').value = this.getAttribute('data-name');
                    document.getElementById('edit_price').value = this.getAttribute('data-price');
                    document.getElementById('edit_category').value = this.getAttribute('data-category');
                });
            });

            // Populate Delete Modal
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    document.getElementById('delete_id').value = this.getAttribute('data-id');
                    document.getElementById('delete_name').textContent = this.getAttribute('data-name');
                });
            });

            // Chart.js Visualization
            const categoryData = <?php echo json_encode($categoryStats); ?>;
            
            if (categoryData && categoryData.length > 0) {
                const labels = categoryData.map(item => item.category);
                const data = categoryData.map(item => parseInt(item.count));

                const ctx = document.getElementById('categoryChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Number of Products',
                                data: data,
                                backgroundColor: [
                                    'rgba(102, 126, 234, 0.8)',
                                    'rgba(118, 75, 162, 0.8)',
                                    'rgba(237, 100, 166, 0.8)',
                                    'rgba(255, 154, 158, 0.8)',
                                    'rgba(250, 208, 196, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(102, 126, 234, 1)',
                                    'rgba(118, 75, 162, 1)',
                                    'rgba(237, 100, 166, 1)',
                                    'rgba(255, 154, 158, 1)',
                                    'rgba(250, 208, 196, 1)'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
