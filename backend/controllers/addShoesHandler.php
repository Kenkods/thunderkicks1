<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/CardsController.php';

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/thunderkicks1/public/uploads/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $required = ['shoeName', 'shoeBrand', 'shoeCategory', 'shoePrice'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    if (!isset($_FILES['shoeImage']) || $_FILES['shoeImage']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $_FILES['shoeImage']['error']);
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['shoeImage']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF allowed.');
    }

    $extension = pathinfo($_FILES['shoeImage']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('shoe_') . '.' . $extension;
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['shoeImage']['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    $conn = getConnection();
    $controller = new CardsController($conn);

    $shoeData = [
        'name' => $_POST['shoeName'],
        'brand' => $_POST['shoeBrand'],
        'category' => $_POST['shoeCategory'],
        'type' => $_POST['shoeType'] ?? 'Basketball', // Default type
        'price' => (float)$_POST['shoePrice'],
        'imagePath' => '/thunderkicks1/public/uploads/' . $filename,
        'sizes' => $_POST['sizes'] ?? [8 => 10, 9 => 10, 10 => 10] // Default sizes
    ];

    $result = $controller->addShoe($shoeData);

    if ($result['success']) {
        $_SESSION['success'] = 'Shoe added successfully!';
        header('Location: /thunderkicks1/Thunderkick-advdb/public/AdminDashboard?success=1');
        exit;
    } else {
        throw new Exception($result['message']);
    }
} catch (Exception $e) {

    if (isset($filename) && file_exists($uploadDir . $filename)) {
        unlink($uploadDir . $filename);
    }

    header('Location: /thunderkicks1/Thunderkick-advdb/pages/AdminDashboard.php?error=' . urlencode($e->getMessage()));
}
exit;
