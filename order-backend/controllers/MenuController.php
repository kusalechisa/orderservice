
<?php
// Handles API endpoints for menu items (add, edit, remove)
require_once __DIR__ . '/../models/Menu.php';

class MenuController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new Menu();
    }

    public function getAllMenus() {
        $menus = $this->menuModel->getAllMenus();
        return ['status' => 200, 'menus' => $menus];
    }

    public function getMenuById($menuId) {
        $menu = $this->menuModel->getMenuById($menuId);
        if ($menu) {
            return ['status' => 200, 'menu' => $menu];
        }
        return ['status' => 404, 'message' => 'Menu item not found.'];
    }

    public function createMenu($data) {
        if (empty($data['name']) || empty($data['description']) || empty($data['price'])) {
            return ['status' => 400, 'message' => 'All fields are required.'];
        }

        $result = $this->menuModel->createMenu($data['name'], $data['description'], $data['price']);
        if ($result) {
            return ['status' => 201, 'message' => 'Menu item created successfully.'];
        }
        return ['status' => 500, 'message' => 'Failed to create menu item.'];
    }

    public function updateMenu($menuId, $data) {
        if (empty($data['name']) || empty($data['description']) || empty($data['price'])) {
            return ['status' => 400, 'message' => 'All fields are required.'];
        }

        $result = $this->menuModel->updateMenu($menuId, $data['name'], $data['description'], $data['price']);
        if ($result) {
            return ['status' => 200, 'message' => 'Menu item updated successfully.'];
        }
        return ['status' => 500, 'message' => 'Failed to update menu item.'];
    }

    public function deleteMenu($menuId) {
        $result = $this->menuModel->deleteMenu($menuId);
        if ($result) {
            return ['status' => 200, 'message' => 'Menu item deleted successfully.'];
        }
        return ['status' => 500, 'message' => 'Failed to delete menu item.'];
    }
}
?>
