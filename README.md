# crud-app

# Product Management System
### PHP CRUD Application with Data Visualization

> **Coding Challenge Submission for DigitalEdu**


**Project Overview**

A comprehensive Product Management System built with PHP, MySQL, and modern web technologies. This application demonstrates full CRUD (Create, Read, Update, Delete) operations with an elegant user interface and real-time data visualization.

##  Key Features

### Core Functionality
-  **Complete CRUD Operations** - Full product lifecycle management
-  **Data Visualization** - Interactive Chart.js bar charts showing category statistics
-  **Responsive Design** - Mobile-first approach using Bootstrap 5
-  **Modal-based Forms** - Clean UX with Bootstrap modals for all operations
-  **Real-time Validation** - Both client-side and server-side validation
-  **Sortable Columns** - Click any column header to sort data ascending/descending
-  **Session Management** - Flash messages for user feedback

### Security Features
-  **SQL Injection Protection** - PDO with prepared statements
-  **XSS Prevention** - HTML escaping and input sanitization
-  **Input Validation** - Comprehensive server-side validation
-  **Secure Database Connection** - PDO error handling

### User Experience
-  **Modern Gradient UI** - Beautiful purple gradient theme
-  **Smooth Animations** - Hover effects and transitions
-  **Bootstrap Icons** - Professional iconography throughout
-  **Auto-dismissing Alerts** - 5-second timeout on messages
-  **Keyboard Shortcuts** - Ctrl/Cmd + N to add new product


##  Technology Stack

| Technology | Purpose |
|------------|---------|
| **PHP 8.1** | Backend logic and server-side processing |
| **MySQL 10.4.32** | Database management |
| **Bootstrap 5.3** | Responsive UI framework |
| **Chart.js** | Data visualization library |
| **Bootstrap Icons** | Icon library |
| **PDO** | Database abstraction layer |


##  Project Structure

```
crud-app/
├── config/
│   └── database.php          # Database configuration and connection
├── includes/
│   ├── Product.php           # Product model with CRUD methods
│   └── functions.php         # Helper functions
├── assets/
│   ├── css/
│   │   └── style.css         # Custom styling
│   └── js/
│       └── script.js         # JavaScript functionality
├── index.php                 # Main application page
├── create.php                # Create product handler
├── update.php                # Update product handler
├── delete.php                # Delete product handler
└── README.md                 # This file
```


##  Installation & Setup

### Prerequisites
- PHP 8.1
- MySQL 10.4.32or higher
- Apache web server
- phpMyAdmin 

### Step 1: Database Setup

1. Create a new MySQL database:
```
CREATE DATABASE crud_app;
```

2. Select the database and create the products table:
```
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

3. Insert sample data (optional):
```
INSERT INTO products (name, price, category) VALUES
('Laptop', 899.99, 'Electronics'),
('Mouse', 25.50, 'Electronics'),
('Desk Chair', 199.99, 'Furniture'),
('Coffee Table', 149.99, 'Furniture'),
('Notebook', 5.99, 'Stationery'),
('Pen Set', 12.99, 'Stationery');
```

### Step 2: Configure Database Connection

Edit `config/database.php` and update with your credentials:

```
private $host = 'localhost';
private $db_name = 'crud_app';
private $username = 'root';
private $password = '';
```

### Step 3: Deploy Files

1. Copy all project files to your web server directory:
   - **XAMPP**: `/opt/lampp/htdocs/crud-app/`

2. Ensure proper file permissions :
```
chmod -R 755 crud-app/
```

### Step 4: Access Application

Open your browser and navigate to:
```
http://localhost/crud-app/
```


##  Usage Guide

### Adding a Product
1. Click the **"Add New Product"** button
2. Fill in the product details:
   - Product Name (max 100 characters)
   - Price (positive decimal number)
   - Category (max 50 characters)
3. Click **"Create Product"**

### Editing a Product
1. Click the **"Edit"** button next to any product
2. Modify the product details in the modal
3. Click **"Update Product"**

### Deleting a Product
1. Click the **"Delete"** button next to any product
2. Confirm deletion in the modal
3. Click **"Delete"** to confirm

### Sorting Products
- Click any column header (ID, Name, Price, Category, Created) to sort
- Click again to toggle between ascending and descending order
- Sort indicators (↑/↓) show current sort direction

### Viewing Statistics
- Scroll down to see the **"Products by Category"** bar chart
- Chart automatically updates when products are added/deleted



##  Features Demonstration

### CRUD Operations
```
CREATE → Add new products via modal form
READ   → View all products in sortable table
UPDATE → Edit existing products inline
DELETE → Remove products with confirmation
```

### Validation Examples

**Valid Input:**
- Name: "Gaming Laptop"
- Price: 1299.99
- Category: "Electronics"

**Invalid Input (will show errors):**
- Empty name
- Negative price
- Price with more than 2 decimal places
- Category longer than 50 characters

### Security Measures

**SQL Injection Prevention:**
```php
// Using PDO prepared statements
$stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $this->id);
```

**XSS Prevention:**
```php
// Sanitizing output
echo htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8');
```


##  Database Schema

### Products Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique product identifier |
| `name` | VARCHAR(100) | NOT NULL | Product name |
| `price` | DECIMAL(10,2) | NOT NULL | Product price |
| `category` | VARCHAR(50) | NOT NULL | Product category |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last update timestamp |



##  UI/UX Highlights

### Color Palette
- **Primary Gradient**: `#667eea` → `#764ba2`
- **Secondary Colors**: Bootstrap default palette
- **Charts**: Multi-color gradient scheme

### Responsive Breakpoints
- **Desktop**: 1200px+
- **Tablet**: 768px - 1199px
- **Mobile**: < 768px

### Animations
- Button hover effects with transform
- Table row hover scaling
- Alert slide-down animation
- Smooth modal transitions


##  Testing Checklist

- [x] Create product with valid data
- [x] Create product with invalid data (validation)
- [x] Read/display all products
- [x] Update existing product
- [x] Delete product with confirmation
- [x] Sort by each column (ascending/descending)
- [x] View category statistics chart
- [x] Responsive design on mobile devices
- [x] SQL injection attempts blocked
- [x] XSS attempts sanitized


##  Troubleshooting

### Database Connection Error
**Problem**: "Connection Error: SQLSTATE[HY000] [1045] Access denied"

**Solution**: 
- Verify database credentials in `config/database.php`
- Ensure MySQL service is running
- Check database name exists

### Blank Page / No Output
**Problem**: Application shows blank page

**Solution**:
- Enable error reporting in `index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
- Check PHP error logs
- Verify all files are properly uploaded

### Chart Not Displaying
**Problem**: Chart.js visualization not showing

**Solution**:
- Check browser console for JavaScript errors
- Ensure CDN links are accessible
- Verify category data exists in database


##  Future Enhancements

Potential improvements for future versions:

- [ ] User authentication and authorization
- [ ] Product image upload
- [ ] Advanced search and filtering
- [ ] Export data to CSV/PDF
- [ ] Pagination for large datasets
- [ ] Product categories management
- [ ] Inventory tracking
- [ ] Multi-language support
- [ ] Dark mode toggle
- [ ] REST API endpoints


##  Code Quality

### Best Practices Implemented
-  Object-Oriented Programming (OOP)
-  Separation of Concerns (MVC-like structure)
-  DRY (Don't Repeat Yourself) principle
-  Secure coding practices
-  Comprehensive commenting
-  Error handling
-  Input validation
-  Code modularity

### Performance Optimizations
- Prepared statements for database queries
- Efficient SQL queries with proper indexing
- Minimal external dependencies
- Optimized CSS and JavaScript
- CDN usage for libraries


##  License

This project is created as a coding challenge submission for **DigitalEdu**. 


##  Author

**Coding Challenge Submission**
- **Challenge**: PHP CRUD Application with Data Visualization
- **Date**: December 2025


##  Support

For any questions or issues regarding this submission:

1. Review this README thoroughly
2. Check the troubleshooting section
3. Verify all setup steps were followed correctly
4. Contact the challenge coordinator if issues persist


##  Acknowledgments

- **Bootstrap Team** - For the excellent UI framework
- **Chart.js** - For the powerful visualization library


<div align="center">

**⭐ Thank you for reviewing this submission! ⭐**

</div>


https://github.com/user-attachments/assets/70ebd543-5668-47bd-b28c-a613f078b19f



