<?php
// Start the session
session_start();

// Check if the user is logged in, and if not, redirect to login page
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Database connection
include_once 'api/config/database.php';

// Instantiate the database and user object
$database = new Database();
$db = $database->getConnection();

// Fetch all users from the database
$query = "SELECT * FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>User List</title>
  <?php include 'includes/css.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
      <?php
      $arr = array(
        ["title" => "Home", "url" => "./"],
        ["title" => "User List", "url" => "#"],
      );
      pagePath('User List', $arr);
      ?>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">User List</h3>
                </div>

                <div class="card-body">
                  <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Fetch each user and display it in the table
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm edit-user-btn' 
                                        data-id='" . $row['id'] . "' 
                                        data-name='" . $row['name'] . "' 
                                        data-email='" . $row['email'] . "'>
                                    Edit
                                </button>
                                <button class='btn btn-danger btn-sm delete-user-btn' 
                                        data-id='" . $row['id'] . "'>
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

  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editUserForm" method="POST" action="update_user.php">
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-user-id">
            <div class="form-group">
              <label for="edit-user-name">Name</label>
              <input type="text" class="form-control" name="name" id="edit-user-name" required>
            </div>
            <div class="form-group">
              <label for="edit-user-email">Email</label>
              <input type="email" class="form-control" name="email" id="edit-user-email" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete User Modal -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="deleteUserForm" method="POST" action="delete_user.php">
          <div class="modal-body">
            <input type="hidden" name="id" id="delete-user-id">
            <p>Are you sure you want to delete this user?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Handle Edit User button click
    document.querySelectorAll('.edit-user-btn').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        const userEmail = this.getAttribute('data-email');

        // Populate the modal fields
        document.getElementById('edit-user-id').value = userId;
        document.getElementById('edit-user-name').value = userName;
        document.getElementById('edit-user-email').value = userEmail;

        // Show modal
        $('#editUserModal').modal('show');
      });
    });

    // Handle Delete User button click
    document.querySelectorAll('.delete-user-btn').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.getAttribute('data-id');

        // Populate the hidden field
        document.getElementById('delete-user-id').value = userId;

        // Show modal
        $('#deleteUserModal').modal('show');
      });
    });
  </script>
</body>
</html>
