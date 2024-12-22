
<?php
// Model for menu items (interacts with DB)
require_once __DIR__ . '/../config/database.php';

class Menu {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function getAllMenus() {
        $stmt = $this->conn->prepare("SELECT * FROM menu_items");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuById($menuId) {
        $stmt = $this->conn->prepare("SELECT * FROM menu_items WHERE id = :id");
        $stmt->bindParam(':id', $menuId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMenu($name, $description, $price) {
        $stmt = $this->conn->prepare("INSERT INTO menu_items (name, description, price) VALUES (:name, :description, :price)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }

    public function updateMenu($id, $name, $description, $price) {
        $stmt = $this->conn->prepare("UPDATE menu_items SET name = :name, description = :description, price = :price WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }

    public function deleteMenu($menuId) {
        $stmt = $this->conn->prepare("DELETE FROM menu_items WHERE id = :id");
        $stmt->bindParam(':id', $menuId);
        return $stmt->execute();
    }
}
?>
