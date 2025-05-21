<?php
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$invName = $data['invName'] ?? '';
$invDescription = $data['invDescription'] ?? '';
$invCategory = $data['invCategory'] ?? '';
$invDosage = $data['invDosage'] ?? '';
$itemQuantity = $data['itemQuantity'] ?? '';
$invSupplyDate = $data['invSupplyDate'] ?? '';
$invExpiryDate = $data['invExpiryDate'] ?? '';

try {
    $sql = "INSERT INTO inventory (invName, invDescription, invCategory, invDosage, itemQuantity, invSupplyDate, invExpiryDate)
            VALUES (:invName, :invDescription, :invCategory, :invDosage, :itemQuantity, :invSupplyDate, :invExpiryDate)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':invName' => $invName,
        ':invDescription' => $invDescription,
        ':invCategory' => $invCategory,
        ':invDosage' => $invDosage,
        ':itemQuantity' => $itemQuantity,
        ':invSupplyDate' => $invSupplyDate,
        ':invExpiryDate' => $invExpiryDate
    ]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>