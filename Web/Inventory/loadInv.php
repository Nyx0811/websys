<?php
include '../Config/database.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$campus = $_GET['campus'] ?? ''; 

// selects Items in the inventory which are active
$sql = "SELECT invID, invName, invDescription, invCategory, invDosage, itemQuantity, invSupplyDate, invExpiryDate, invCampus 
        FROM inventory 
        WHERE invStatus = 'active'";

$params = [];

if (!empty($category)) {
    $sql .= " AND invCategory = :category";
    $params[':category'] = $category;
}

if (!empty($search)) {
    // Search by ID or Name
    $sql .= " AND (invID LIKE :search_id OR invName LIKE :search_name)"; // Use different placeholders for clarity
    $params[':search_id'] = $search; // Exact match for ID if user types full ID
    $params[':search_name'] = "%$search%";
}

if (!empty($campus)) {
    $sql .= " AND invCampus = :campus";
    $params[':campus'] = $campus;
}

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($inventory);

} catch (PDOException $e) {
    error_log("Load Inventory Error (loadInv.php): " . $e->getMessage());
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
?>
