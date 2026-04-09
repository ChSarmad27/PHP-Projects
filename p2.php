<?php
include("config.php");
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Pagination variables
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container my-5">
    <h2>Dashboard</h2>
    <p>Welcome, <strong><?php echo $user_role == 'admin' ? "Admin" : "User"; ?></strong></p>

<?php
if ($user_role == 'admin') {

    echo "<a href='client_form.php' class='btn btn-success mb-3'>Create New User</a>";

    // Get total number of users
    $total_stmt = $connection->prepare("SELECT COUNT(*) AS total FROM clients");
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $total_stmt->close();

    // Fetch users for current page
    $stmt = $connection->prepare("SELECT id,name,email,phone,address,city,role FROM clients ORDER BY id ASC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead class='table-dark'><tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>Role</th>
            <th>Actions</th>
          </tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['city']}</td>
                <td>{$row['role']}</td>
                <td>
                    <a href='client_form.php?id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='delete.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?');\">Delete</a>
                </td>
              </tr>";
    }
    echo "</tbody></table></div>";

    // Pagination links
    echo "<nav><ul class='pagination justify-content-center'>";
    if($page > 1){
        echo "<li class='page-item'><a class='page-link' href='profile.php?page=".($page-1)."'>Previous</a></li>";
    }

    for($i=1; $i<=$total_pages; $i++){
        $active = ($i == $page) ? "active" : "";
        echo "<li class='page-item $active'><a class='page-link' href='profile.php?page=$i'>$i</a></li>";
    }

    if($page < $total_pages){
        echo "<li class='page-item'><a class='page-link' href='profile.php?page=".($page+1)."'>Next</a></li>";
    }
    echo "</ul></nav>";

} else {
    //user sees only their data
    $stmt = $connection->prepare("SELECT id,name,email,phone,address,city FROM clients WHERE id=?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo "<h4>Your Profile</h4>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead class='table-dark'><tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
          </tr></thead><tbody>";
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['address']}</td>
            <td>{$row['city']}</td>
          </tr>";
    echo "</tbody></table></div>";
}
?>

<a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</div>
</body>
</html>
