<?php
include("config.php");
requireAdmin();


$id = "";
$name = "";
$email = "";
$password = "";
$phone = "";
$address = "";
$city = "";
$role = "user";
$errorMessage = "";


//LOAD EXISTING USER
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        $stmt = $connection->prepare("SELECT * FROM clients WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            header("Location: profile.php");
            exit;
        }

        $name    = $row['name'];
        $email   = $row['email'];
        $phone   = $row['phone'];
        $address = $row['address'];
        $city    = $row['city'];
        $role    = $row['role'];

        $stmt->close();
    }
}


//FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id      = $_POST['id'] ?? "";
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $password= $_POST['password'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];
    $city    = $_POST['city'];
    $role    = $_POST['role'];

    do {

        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city)) {
            $errorMessage = "All fields are required.";
            break;
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);
        }

        //UPDATE
        if (!empty($id)) {

            if (!empty($password)) {
                $stmt = $connection->prepare(
                    "UPDATE clients SET name=?,email=?,password=?,phone=?,address=?,city=?,role=? WHERE id=?"
                );

                $stmt->bind_param("sssssssi",
                    $name,$email,$hashedPassword,$phone,$address,$city,$role,$id
                );
            } else {
                $stmt = $connection->prepare(
                    "UPDATE clients SET name=?,email=?,phone=?,address=?,city=?,role=? WHERE id=?"
                );

                $stmt->bind_param("ssssssi",
                    $name,$email,$phone,$address,$city,$role,$id
                );
            }

        }
        //INSERT data
        else {

            if (empty($password)) {
                $errorMessage = "Password required for new user.";
                break;
            }

            $stmt = $connection->prepare(
                "INSERT INTO clients (name,email,password,phone,address,city,role)
                 VALUES (?,?,?,?,?,?,?)"
            );

            $stmt->bind_param("sssssss",
                $name,$email,$hashedPassword,$phone,$address,$city,$role
            );
        }

        try {
            $stmt->execute();
            } catch (mysqli_sql_exception $e) {
            $errorMessage = "Email already exists.";
            break;
        }

        $stmt->close();
        header("Location: profile.php");
        exit;

    } while(false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo empty($id) ? "Create User" : "Edit User"; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container my-5">
    <h2><?php echo empty($id) ? "Create New User" : "Edit User"; ?></h2>

    <?php if(!empty($errorMessage)): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <strong><?php echo $errorMessage; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password <?php echo empty($id) ? "(required)" : "(leave blank to keep unchanged)"; ?></label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo $address; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <select class="form-select" name="city" required>
                <option value="">Select City</option>
                <?php
                $cities = ["Lahore","Faisalabad","Islamabad","Karachi","Multan"];
                foreach($cities as $c) {
                    $selected = ($city == $c) ? "selected" : "";
                    echo "<option value='$c' $selected>$c</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" required>
                <option value="user" <?php if($role=="user") echo "selected"; ?>>User</option>
                <option value="admin" <?php if($role=="admin") echo "selected"; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo empty($id) ? "Create" : "Update"; ?></button>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
