<?php
include '../config/database.php'; // Ensure this path is correct

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$invID = $data['inventory_no'] ?? null;

if (empty($invID)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing inventory ID.']);
    exit;
}

try {
    // Update the status of the item to 'archived' and set the timestamp
    $sql = "UPDATE inventory 
            SET invStatus = 'archived', 
                archivedTimestamp = NOW() 
            WHERE invID = :invID AND invStatus = 'active'"; // Ensure we only archive active items

    $stmt = $conn->prepare($sql);
    $stmt->execute([':invID' => $invID]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Item archived successfully.']);
    } else {
        // Check if the item was already archived or not found
        $checkSql = "SELECT invStatus FROM inventory WHERE invID = :invID";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([':invID' => $invID]);
        $item = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if ($item['invStatus'] === 'archived') {
                echo json_encode(['status' => 'info', 'message' => 'Item was already archived.']);
            } else {
                 echo json_encode(['status' => 'error', 'message' => 'Failed to archive item. Item might not be active or status is unexpected: ' . $item['invStatus']]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Item not found or could not be archived.']);
        }
    }

} catch (PDOException $e) {
    error_log("Archive Error (moveToArchive.php): " . $e->getMessage()); 
    echo json_encode(['status' => 'error', 'message' => 'Database error during archiving process.']);
}
?>
