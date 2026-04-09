<?php
include("config.php");

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $city     = $_POST['city'];

    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($city)) {
        $errorMessage = "All fields are required.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $connection->prepare(
            "INSERT INTO clients (name,email,password,phone,address,city,role)
             VALUES (?,?,?,?,?,?,?)"
        );

        $role = "user";
        $stmt->bind_param("sssssss",
            $name,$email,$hashedPassword,$phone,$address,$city,$role
        );

        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $errorMessage = "Database error.";
        }

        $stmt->close();
    }
}
?>

<h2>Sign Up</h2>
<?php if(!empty($errorMessage)) echo "<p style='color:red;'>$errorMessage</p>"; ?>
<form method="post">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <input type="text" name="address" placeholder="Address" required><br>
    <select name="city" required>
        <option value="">Select City</option>
        <option value="Lahore">Lahore</option>
        <option value="Faisalabad">Faisalabad</option>
        <option value="Islamabad">Islamabad</option>
        <option value="Karachi">Karachi</option>
        <option value="Multan">Multan</option>
    </select><br><br>
    <button type="submit">Sign Up</button>
</form>
<a href="login.php">Already have an account? Login</a>
