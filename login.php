<?php
// Clear previous session if any
if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}
session_start();
unset($_SESSION['cart']);
include 'connection.php';

// Get form values
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Query user by email
$sql = "SELECT * FROM login WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['firstname'];
        $_SESSION['user_email'] = $user['email'];

        $_SESSION['user_role'] = $user['role'];

        // --- Merge guest session cart with user's cart (if any) ---
        // If you have persistent cart storage in DB, load it here. For now, we use session only.
        $user_cart = []; // Example: fetch from DB if you have persistent cart storage
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // If you have persistent cart storage, merge session cart into DB cart here
            // For now, just keep session cart as the user's cart
            foreach ($_SESSION['cart'] as $guest_item) {
                $found = false;
                foreach ($user_cart as &$user_item) {
                    if ($user_item['id'] == $guest_item['id']) {
                        $user_item['quantity'] += $guest_item['quantity'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $user_cart[] = $guest_item;
                }
            }
            // Save merged cart back to session (or DB)
            $_SESSION['cart'] = $user_cart;
        }
        // --- End cart merge ---

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'owner':
                header("Location: owner_dashboard.php");
                break;
            case 'customer':
            default:
                header("Location: customer_dashboard.php");
                break;
        }
        exit;
    } else {
        echo "<script>alert('❌ Incorrect password'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('❌ No account found with this email'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
