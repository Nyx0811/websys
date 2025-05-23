<?php

include '../config/database.php'; // Your DB connection

header('Content-Type: application/json');

// Get filter values from the URL
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$campus = $_GET['campus'] ?? ''; // This is the campus ID

// SQL to get archived inventory items and their campus names
$sql = "SELECT i.*, c.campusName AS invCampus 
        FROM inventory i 
        JOIN campus c ON i.campusID = c.campusID 
        WHERE i.invStatus = 'archived'"; // Only archived items

$params = [];

if (!empty($category)) {
    $sql .= " AND i.invCategory = :category";
    $params[':category'] = $category;
}

if (!empty($search)) {
    $sql .= " AND (i.invID LIKE :search OR i.invName LIKE :search)";
    $params[':search'] = "%" . $search . "%";
}

if (!empty($campus)) {
    $sql .= " AND i.campusID = :campus"; // Filter by inventory.campusID
    $params[':campus'] = $campus;
}

$sql .= " ORDER BY i.archivedTimestamp DESC"; // Show recently archived first

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $archivedItems = $stmt->fetchAll(PDO::FETCH_ASSOC); // Changed variable name for clarity
    echo json_encode($archivedItems);

} catch (PDOException $e) {
    error_log("Error in FetchArch.php: " . $e->getMessage());
    echo json_encode(["error" => "Could not fetch archived inventory.", "details" => $e->getMessage()]);
}
?>