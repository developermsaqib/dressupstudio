<?php
include 'connection.php';

// Check if form was submitted with POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['categoryName'] ?? '';
    
    // Handle file upload
    $imagePath = null;
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($_FILES['categoryImage']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO categories (name, image) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $imagePath);
    $success = $stmt->execute();
    
    if ($success) {
        // Display success message and redirect
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Success</title>
            <meta http-equiv="refresh" content="2;url=admin_dashboard.php" />
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f8f9fa; }
                .success { color: #28a745; font-size: 24px; margin-bottom: 20px; }
                .redirect { color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="success">✅ Category added successfully!</div>
        </body>
        </html>';
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>