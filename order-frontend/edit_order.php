<?php
// Database connection
include_once 'api/config/database.php';

if (isset($_GET['id'])) {
  $order_id = $_GET['id'];
} else {
  die('Order ID not provided');
}

// Instantiate the database and order object
$database = new Database();
$db = $database->getConnection();

// Fetch the order details from the database
$query = "SELECT * FROM orders WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $order_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  die('Order not found');
}

// Update order when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $items = $_POST['items'];
  $total = $_POST['total'];
  $status = $_POST['status'];

  $update_query = "UPDATE orders SET items = :items, total = :total, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
  $update_stmt = $db->prepare($update_query);
  $update_stmt->bindParam(':items', $items);
  $update_stmt->bindParam(':total', $total);
  $update_stmt->bindParam(':status', $status);
  $update_stmt->bindParam(':id', $order_id);

  if ($update_stmt->execute()) {
    header("Location: orders.php"); // Redirect to the orders list
    exit;
  } else {
    echo "Error updating order.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Edit Order</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php' ?>
    <div class="content-wrapper">

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Edit Order</h3>
                </div>

                <div class="card-body">
                  <form method="POST">
                    <div class="form-group">
                      <label for="items">Items</label>
                      <textarea class="form-control" id="items" name="items" rows="4" required><?php echo $order['items']; ?></textarea>
                    </div>

                    <div class="form-group">
                      <label for="total">Total</label>
                      <input type="number" class="form-control" id="total" name="total" value="<?php echo $order['total']; ?>" required step="0.01">
                    </div>

                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control" id="status" name="status">
                        <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="completed" <?php if ($order['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                        <option value="cancelled" <?php if ($order['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                      </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include 'includes/footer.php'; ?>
  </div>

  <?php include 'includes/js.php' ?>
</body>

</html>
