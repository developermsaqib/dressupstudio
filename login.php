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
$guest_cart_json = $_POST['guest_cart'] ?? '';
$guest_cart = [];
if ($guest_cart_json) {
    $guest_cart = json_decode($guest_cart_json, true);
    if (!is_array($guest_cart)) $guest_cart = [];
}

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

        // --- Merge guest cart from localStorage (if any) ---
        $user_cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (!empty($guest_cart)) {
            foreach ($guest_cart as $guest_item) {
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
        // After login, clear guest cart in browser
        echo "<script>localStorage.removeItem('cart');</script>";
        exit;
    } else {
        echo "<script>alert('❌ Incorrect password'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('❌ No account found with this email'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
