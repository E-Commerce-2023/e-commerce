<?php
//连接数据库
$conn = new mysqli('localhost', 'root', 'password', 'database');

//注册
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //检查用户名是否存在
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response["code"] = 404;
        $response["message"] = "用户已存在";
        echo json_encode($response);
        exit;
    }

    //插入新用户
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $response["code"] = 200;
    $response["message"] = "注册成功";
    echo json_encode($response);
}

//登录
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //检查用户名和密码
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["code"] = 200;
        $response["message"] = "登录成功";
        echo json_encode($response);
    } else {
        $response["code"] = 404;
        $response["message"] = "用户名或密码错误";
        echo json_encode($response);
    }
}






