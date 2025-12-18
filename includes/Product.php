<?php
/**
 * Product Model
 * Handles all CRUD operations for products
 */

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $price;
    public $category;
    public $created_at;
    public $updated_at;

    /**
     * Constructor with database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Read all products
     * @return PDOStatement
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read all products with sorting
     * @param string $sortColumn Column to sort by
     * @param string $sortOrder ASC or DESC
     * @return PDOStatement
     */
    public function readWithSort($sortColumn = 'id', $sortOrder = 'ASC') {
        // Whitelist of allowed columns for sorting
        $allowedColumns = ['id', 'name', 'price', 'category', 'created_at'];
        
        // Validate sort column
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
        
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY " . $sortColumn . " " . $sortOrder;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read single product
     * @return bool
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->price = $row['price'];
            $this->category = $row['category'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    /**
     * Create new product
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, price, category) 
                  VALUES (:name, :price, :category)";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Update product
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, price = :price, category = :category 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Delete product
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Get category statistics for visualization
     * @return array
     */
    public function getCategoryStats() {
        $query = "SELECT category, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  GROUP BY category 
                  ORDER BY count DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Validate product data
     * @return array Validation errors
     */
    public function validate() {
        $errors = [];

        if(empty($this->name)) {
            $errors[] = "Product name is required.";
        } elseif(strlen($this->name) > 100) {
            $errors[] = "Product name must be less than 100 characters.";
        }

        if(empty($this->price)) {
            $errors[] = "Price is required.";
        } elseif(!is_numeric($this->price) || $this->price <= 0) {
            $errors[] = "Price must be a positive number.";
        }

        if(empty($this->category)) {
            $errors[] = "Category is required.";
        } elseif(strlen($this->category) > 50) {
            $errors[] = "Category must be less than 50 characters.";
        }

        return $errors;
    }
}
?>
