<?php
// end_session.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_id = $_POST['session_id'] ?? 0;
    $generations = $_POST['generations'] ?? 0;
    $population = $_POST['population'] ?? 0;
    
    $stmt = $conn->prepare("UPDATE game_sessions SET end_time = NOW(), generations = ?, max_population = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iiii", $generations, $population, $session_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>