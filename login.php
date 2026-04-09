<?php
include("config.php");

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare("SELECT id,password,role FROM clients WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {$errorMessage = "Invalid email or password.";
    } else {

        $stmt->bind_result($id,$hashedPassword,$role);
        $stmt->fetch();

        if (password_verify($password,$hashedPassword)) {

            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;

            header("Location: profile.php");
            exit;
        } else {
            $errorMessage = "Invalid email or password.";
        }
    }

    $stmt->close();
}
?>

<h2>Login</h2>
<?php if(!empty($errorMessage)) echo "<p style='color:red;'>$errorMessage</p>"; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>
<a href="signup.php">Don't have an account? Sign Up</a>
