<?php
// sample GET: load_category.php?category=Adidas&view_all=1

require 'CardsModel.php';
require 'YourController.php';

$conn = new mysqli('localhost', 'root', '', 'your_db');

$category_name = $_GET['category'] ?? '';
$view_all = isset($_GET['view_all']);
$limit = $view_all ? null : 10;
$offset = 0;

$controller = new YourController($conn);
$data = $controller->showCategory($category_name, $limit, $offset);

header('Content-Type: application/json');
echo json_encode($data);
?>