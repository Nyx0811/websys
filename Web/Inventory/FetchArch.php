<?php
include '../config/database.php'; // Ensure this path is correct

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Start SQL query, always filtering for archived items
$sql = "SELECT invID, invName, invDescription, invCategory, invDosage, itemQuantity, invSupplyDate, invExpiryDate, invCampus, archivedTimestamp 
        FROM inventory 
        WHERE invStatus = 'archived'"; // <-- CHANGED THIS CONDITION

$params = [];

if (!empty($category)) {
    $sql .= " AND invCategory = :category";
    $params[':category'] = $category;
}

if (!empty($search)) {
    // Search by ID or Name
    $sql .= " AND (invID LIKE :search_id OR invName LIKE :search_name)";
    $params[':search_id'] = $search;
    $params[':search_name'] = "%$search%";
}

// Optionally, order by when they were archived
$sql .= " ORDER BY archivedTimestamp DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $archivedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($archivedItems);

} catch (PDOException $e) {
    error_log("Fetch Archived Error (FetchArch.php): " . $e->getMessage());
    echo json_encode(['error' => 'Database error while fetching archived items.', 'details' => $e->getMessage()]);
}
?>
