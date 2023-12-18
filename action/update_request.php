<?php

// update_request.php

// Assuming you have included necessary files and initialized your database connections
include_once "../classes/DBConnection.php";

// Retrieve the data from the AJAX request
$data = isset($_POST['items']) ? $_POST['items'] : [];
$barangay_id = isset($_POST['barangay_id']) ? $_POST['barangay_id'] : null; // Retrieve barangay_id
$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null; // Retrieve barangay_id

// Check if barangay_id is available
if ($barangay_id === null) {
    $response = ['success' => false, 'message' => 'Error: Missing barangay_id'];
    echo json_encode($response);
    exit;
}

if ($request_id === null) {
    $response = ['success' => false, 'message' => 'Error: Missing request_id'];
    echo json_encode($response);
    exit;
}


// Create an instance of DBConnection
$dbConnection = new DBConnection();

// Access the database connection
$conn1 = $dbConnection->conn1;
$conn2 = $dbConnection->conn2;

// Example query to update the request with item IDs and quantities
foreach ($data as $item) {
    $itemId = $item['id'];
    $quantity = $item['qty'];
    $itemName = $item['name'];

    if ($quantity > 0) {
        // check if the item name already exists in $conn2
        $checkQuery = "SELECT * FROM inventory WHERE name = '{$itemName}' AND barangay_id = {$barangay_id}";
        $result = $conn2->query($checkQuery);

        // if it exists, then update its stock/quantity
        // else create another inventory item
        if ($result->num_rows > 0) {
            // Item already exists, update its stock/quantity
            $updateQuery = "UPDATE inventory SET quantity = quantity + {$quantity} WHERE name = '{$itemName}'";
            if (!$conn2->query($updateQuery)) {
                $response = ['success' => false, 'message' => 'Error updating item quantity in inventory: ' . $conn2->error];
                echo json_encode($response);
                exit;
            }
        } else {
            // Item does not exist, create a new inventory item
            $insertQuery = "INSERT INTO inventory (name, quantity, barangay_id) VALUES ('{$itemName}', {$quantity}, {$barangay_id})";
            if (!$conn2->query($insertQuery)) {
                $response = ['success' => false, 'message' => 'Error inserting new item into inventory: ' . $conn2->error];
                echo json_encode($response);
                exit;
            }
        }

        // find the first stock item that is not 0 quantity and greater or equal to $quantity
        $findStockItemQuery = "SELECT * FROM stock_list WHERE item_id = {$itemId} AND quantity > 0 AND quantity >= {$quantity} ORDER BY id ASC LIMIT 1";
        $stockResult = $conn1->query($findStockItemQuery);

        if ($stockResult && $stockRow = $stockResult->fetch_assoc()) {
            // now I want to decrease its quantity - $quantity
            $updatedQuantity = $stockRow['quantity'] - $quantity;
            $updateStockQuery = "UPDATE stock_list SET quantity = {$updatedQuantity} WHERE id = {$stockRow['id']}";
            if (!$conn1->query($updateStockQuery)) {
                $response = ['success' => false, 'message' => 'Error updating stock item quantity: ' . $conn1->error];
                echo json_encode($response);
                exit;
            }
        }

        $updateStockQuery = "UPDATE requests SET request_status = 'ONGOING' WHERE id = {$request_id}";
        $conn2->query($updateStockQuery);
    }

}

// Send a response back to the client
$response = ['success' => true, 'message' => 'Request updated successfully'];
echo json_encode($response);
?>