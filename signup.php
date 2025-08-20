
<?php
// Clear previous session if any
if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}
session_start();
unset($_SESSION['cart']);

include 'connection.php';

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$role = $_POST['role'];  // Default: 'customer'

if ($password !== $confirmPassword) {
    die("Passwords do not match.");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO login (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    // ✅ Automatically log the user in after signup
    $user_id = $stmt->insert_id;

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $firstName;
    $_SESSION['user_role'] = $role;
    $_SESSION['user_email'] = $email;

    // ✅ Redirect to dashboard
    if ($role === 'admin') {
        header("Location: admin_dashboard.php");
    } elseif ($role === 'owner') {
        header("Location: owner_dashboard.php");
    } else {
        header("Location: customer_dashboard.php");
    }
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
