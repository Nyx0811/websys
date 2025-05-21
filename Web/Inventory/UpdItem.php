<?php
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

// Extract values from the incoming data, using empty checks as necessary
$invID = $data['invID'] ?? '';
$invName = $data['invName'] ?? '';
$invDescription = $data['invDescription'] ?? '';
$invCategory = $data['invCategory'] ?? '';
$invDosage = $data['invDosage'] ?? '';
$itemQuantity = $data['itemQuantity'] ?? '';
$invSupplyDate = $data['invSupplyDate'] ?? '';
$invExpiryDate = $data['invExpiryDate'] ?? '';

// Prepare the UPDATE SQL statement
try {
    $sql = "UPDATE inventory 
            SET invName = :invName, 
                invDescription = :invDescription, 
                invCategory = :invCategory, 
                invDosage = :invDosage, 
                itemQuantity = :itemQuantity, 
                invSupplyDate = :invSupplyDate, 
                invExpiryDate = :invExpiryDate
            WHERE invID = :invID";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':invID' => $invID,
        ':invName' => $invName,
        ':invDescription' => $invDescription,
        ':invCategory' => $invCategory,
        ':invDosage' => $invDosage,
        ':itemQuantity' => $itemQuantity,
        ':invSupplyDate' => $invSupplyDate,
        ':invExpiryDate' => $invExpiryDate
    ]);

    // Success response
    echo json_encode(['status' => 'success', 'message' => 'Inventory updated successfully.']);
} catch (PDOException $e) {
    // Error response
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
