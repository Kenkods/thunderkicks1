<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Correct require paths
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/CardsController.php';

// Set upload directory (pointing to public/uploads)
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/thunderkicks1/public/uploads/';

// Create upload directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required = ['shoeName', 'shoeBrand', 'shoeCategory', 'shoePrice'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Validate file upload
    if (!isset($_FILES['shoeImage']) || $_FILES['shoeImage']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $_FILES['shoeImage']['error']);
    }

    // Validate image type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['shoeImage']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF allowed.');
    }

    // Generate unique filename
    $extension = pathinfo($_FILES['shoeImage']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('shoe_') . '.' . $extension;
    $targetPath = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($_FILES['shoeImage']['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Initialize controller
    $conn = getConnection();
    $controller = new CardsController($conn);

    // Prepare data
    $shoeData = [
        'name' => $_POST['shoeName'],
        'brand' => $_POST['shoeBrand'],
        'category' => $_POST['shoeCategory'],
        'type' => $_POST['shoeType'] ?? 'Basketball', // Default type
        'price' => (float)$_POST['shoePrice'],
        'imagePath' => '/thunderkicks1/public/uploads/' . $filename,
        'sizes' => $_POST['sizes'] ?? [8 => 10, 9 => 10, 10 => 10] // Default sizes
    ];

    // Add shoe to database
    $result = $controller->addShoe($shoeData);

    if ($result['success']) {
        header('Location: /thunderkicks1/Thunderkick-advdb/pages/dashboard/AdminDashboard.php?success=1');
    } else {
        throw new Exception($result['message']);
    }
} catch (Exception $e) {
    // Clean up if file was uploaded but something else failed
    if (isset($filename) && file_exists($uploadDir . $filename)) {
        unlink($uploadDir . $filename);
    }

    header('Location: /thunderkicks1/Thunderkick-advdb/pages/AdminDashboard.php?error=' . urlencode($e->getMessage()));
}
exit;
