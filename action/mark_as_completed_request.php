<?php
include_once "../classes/DBConnection.php";

$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null; // Retrieve barangay_id
// Access the database connection

if ($request_id === null) {
    $response = ['success' => false, 'message' => 'Error: Missing request_id'];
    echo json_encode($response);
    exit;
}

$dbConnection = new DBConnection();
$conn2 = $dbConnection->conn2;

$updateRequestQuery = "UPDATE requests SET request_status = 'COMPLETED' WHERE id = ?";
$stmt = $conn2->prepare($updateRequestQuery);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$updateInventoryQuery = "UPDATE inventory SET isArchive = false WHERE request_id = ?";
$stmt2 = $conn2->prepare($updateInventoryQuery);
$stmt2->bind_param("i", $request_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($$result2->num_rows > 0) {
    $response = ['status' => 'success', 'message' => 'Inventory updated sucess'];
    echo json_encode($response);
    exit();
} else {
    $response = ['status' => 'error', 'message' => 'Failed to update inventory'];
    echo json_encode($response);
    exit();
}
?>