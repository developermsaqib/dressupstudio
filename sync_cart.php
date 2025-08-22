<?php
session_start();
header('Content-Type: application/json');

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

// Get cart from POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!isset($data['cart']) || !is_array($data['cart'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid cart data']);
    exit;
}

// Merge guest cart into session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

foreach ($data['cart'] as $item) {
    $found = false;
    foreach ($_SESSION['cart'] as &$sessionItem) {
        if ($sessionItem['id'] == $item['id']) {
            $sessionItem['quantity'] += $item['quantity'];
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = $item;
    }
}

// Optionally, clear guest cart in response
echo json_encode(['success' => true]);
exit;
