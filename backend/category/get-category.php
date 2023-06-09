<?php

//get category on page
// Connect to database

$host= "localhost";
$username = 'root';
$password = '';
$dbName = "ass2";

try {
$conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    $response["code"] = 404;
    $response["message"] = "Failed to connect to database";
    echo json_encode($response);
    exit;
}

// defalut 1 for page and 10 for limit
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

// Validate parameters
if ($page <= 0 || $limit <= 0) {
$response['code'] = 404;
$response['message'] = "Invalid parameters";
echo json_encode($response);
    exit;
}

//  total number of categories
$stmt = $conn->query('SELECT COUNT(*) FROM tb_category');
$total = $stmt->fetchColumn();

// offset and limit for search
$offset = ($page - 1) * $limit;
$stmt = $conn->prepare('SELECT id, name, (SELECT COUNT(*) FROM tb_item WHERE category_id = tb_category.id) AS productNumber FROM tb_category LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

   
$categories = $stmt->fetchAll();
$data['list'] = $categories;
$data['total'] = $total;
$response['data'] = $data;
$response['code'] = 200;
echo json_encode($response);

