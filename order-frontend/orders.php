<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Orders</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php' ?>
    <div class="content-wrapper">

      <?php
      $arr = array(
        ["title" => "Home", "url" => "./"],
        ["title" => "Orders", "url" => "#"],
      );
      pagePath('Orders', $arr);
      ?>

      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">List of Orders</h3>
                </div>

                <div class="card-body">
                  <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Database connection
                      include_once 'api/config/database.php';

                      // Instantiate the database and order object
                      $database = new Database();
                      $db = $database->getConnection();

                      // Fetch orders from the database
                      $query = "SELECT * FROM orders";
                      $stmt = $db->prepare($query);
                      $stmt->execute();

                      // Fetch each order and display it in the table
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['items'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>" . $row['updated_at'] . "</td>";
                        echo "<td>
                                <a href='edit_order.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Edit</a>
                                <a href='delete_order.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
                        echo "</tr>";
                      }
                      ?>
                    </tbody>
                  </table><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Orders</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php' ?>
    <div class="content-wrapper">

      <?php
      $arr = array(
        ["title" => "Home", "url" => "./"],
        ["title" => "Orders", "url" => "#"],
      );
      pagePath('Orders', $arr);
      ?>

      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">List of Orders</h3>
                </div>

                <div class="card-body">
                  <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Database connection
                      include_once 'api/config/database.php';

                      // Instantiate the database and order object
                      $database = new Database();
                      $db = $database->getConnection();

                      // Fetch orders from the database
                      $query = "SELECT * FROM orders";
                      $stmt = $db->prepare($query);
                      $stmt->execute();

                      // Fetch each order and display it in the table
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['items'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>" . $row['updated_at'] . "</td>";
                        echo "<td>
                                <a href='edit_order.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Edit</a>
                                <a href='delete_order.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
                        echo "</tr>";
                      }
                      ?>
                    </tbody>
                  </table>
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
