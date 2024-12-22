<?php
// Start the session
session_start();

// Check if the admin is logged in, and if not, redirect to login page
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Database connection
include_once 'api/config/database.php';

// Instantiate the database object
$database = new Database();
$db = $database->getConnection();

// Initialize $stmt
$stmt = null;

try {
    // Fetch notifications from the database
    $query = "SELECT * FROM notifications ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // For debugging purposes
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Admin Notifications</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
      <?php
      $arr = array(
        ["title" => "Home", "url" => "./"],
        ["title" => "Notifications", "url" => "#"],
      );
      pagePath('New Notifications', $arr);
      ?>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Notifications</h3>
                </div>

                <div class="card-body">
                  <table id="notifications-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Entity ID</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($stmt) {
                          // Fetch each notification and display it in the table
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                              echo "<tr>";
                              echo "<td>" . $row['id'] . "</td>";
                              echo "<td>" . $row['type'] . "</td>";
                              echo "<td>" . $row['entity_id'] . "</td>";
                              echo "<td>" . $row['message'] . "</td>";
                              echo "<td>" . ucfirst($row['status']) . "</td>"; // Capitalize status
                              echo "<td>" . $row['created_at'] . "</td>";
                              echo "<td>";
                              if ($row['status'] === 'unread') {
                                  // Display the button only for unread notifications
                                  echo "<button class='btn btn-success btn-sm mark-as-read-btn' data-id='" . $row['id'] . "'>
                                          Mark as Read
                                        </button>";
                              }
                              echo "</td>";
                              echo "</tr>";
                          }
                      } else {
                          echo "<tr><td colspan='7'>No notifications found.</td></tr>";
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

  <?php include 'includes/js.php'; ?>

  <script>
    // Handle "Mark as Read" button click
    document.querySelectorAll('.mark-as-read-btn').forEach(button => {
      button.addEventListener('click', function () {
        const notificationId = this.getAttribute('data-id');

        // Send an AJAX request to mark the notification as read
        fetch('mark_notification_as_read.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Notification marked as read.');
            location.reload(); // Refresh the page to update the list
          } else {
            alert('Failed to mark the notification as read.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while processing your request.');
        });
      });
    });

    $(document).ready(function () {
      $('#notifications-table').DataTable(); // Initialize DataTables for sorting, searching, etc.
    });
  </script>
</body>
</html>
