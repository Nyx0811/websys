<?php
// UpdItem.php (Minimal changes to your original for campusID)

include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$invID = $data['invID'] ?? '';
$invName = $data['invName'] ?? '';
$invDescription = $data['invDescription'] ?? '';
$invCategory = $data['invCategory'] ?? '';
$invDosage = $data['invDosage'] ?? '';
$itemQuantity = $data['itemQuantity'] ?? '';
$invSupplyDate = $data['invSupplyDate'] ?? '';
$invExpiryDate = $data['invExpiryDate'] ?? NULL;

// Get campusID from the input.
$campusID = $data['campusID'] ?? ''; 

try {
    // Modified SQL: Added campusID to the SET clause
    $sql = "UPDATE inventory 
            SET 
                invName = :invName, 
                invDescription = :invDescription, 
                invCategory = :invCategory, 
                invDosage = :invDosage, 
                itemQuantity = :itemQuantity, 
                invSupplyDate = :invSupplyDate, 
                invExpiryDate = :invExpiryDate,
                campusID = :campusID  -- Added
            WHERE 
                invID = :invID";
    
    $stmt = $conn->prepare($sql);
    
    // Modified parameters: Added :campusID
    $stmt->execute([
        ':invID' => $invID,
        ':invName' => $invName,
        ':invDescription' => $invDescription,
        ':invCategory' => $invCategory,
        ':invDosage' => $invDosage,
        ':itemQuantity' => $itemQuantity,
        ':invSupplyDate' => $invSupplyDate,
        ':invExpiryDate' => $invExpiryDate,
        ':campusID' => $campusID    // Added
    ]);

    // Kept your original success message
    echo json_encode(['status' => 'success', 'message' => 'Inventory updated successfully.']);

} catch (PDOException $e) {
    // Kept your original error message structure
    error_log("Error in UpdItem.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>