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

// Fetch messages from the database
$query = "SELECT m.id, m.user_id, m.message, m.created_at, 
                 u.name AS user_name, u.email AS user_email 
          FROM messages m
          LEFT JOIN users u ON m.user_id = u.id
          ORDER BY m.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>User Messages</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
      <?php
      $arr = array(
        ["title" => "Home", "url" => "./"],
        ["title" => "User Messages", "url" => "#"],
      );
      pagePath('User Messages', $arr);
      ?>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">User Messages</h3>
                </div>

                <div class="card-body">
                  <table id="messages-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Message ID</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Message</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Fetch each message and display it in the table
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_name'] . "</td>";
                        echo "<td>" . $row['user_email'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>
                                  Delete
                                </button>
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

  <?php include 'includes/js.php'; ?>

  <script>
    // Handle "Delete" button click
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const messageId = this.getAttribute('data-id');

        // Confirm before deleting
        if (confirm('Are you sure you want to delete this message?')) {
          // Send an AJAX request to delete the message
          fetch('delete_messages.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: messageId })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message); // Show success message
              location.reload(); // Refresh the page to update the list
            } else {
              alert(data.message || 'Failed to delete the message.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
          });
        }
      });
    });

    $(document).ready(function () {
      $('#messages-table').DataTable(); // Initialize DataTables for sorting, searching, etc.
    });
  </script>
</body>
</html>
