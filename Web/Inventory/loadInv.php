<?php
// No session_start() here as per your last provided file for loadInv.php;
// If campus filtering needs to default to session's activeCampusID, session_start() would be needed.
// For now, focusing only on the category filter issue.

require '../Config/database.php';

header('Content-Type: application/json');

$category_from_request = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$campus_filter = $_GET['campus'] ?? ''; // Campus filter from dropdown

// **MODIFICATION: Convert received category to lowercase for DB comparison**
$category_for_query = '';
if (!empty($category_from_request)) {
    $category_for_query = strtolower($category_from_request);
}

$sql = "SELECT i.*, c.campusName AS invCampus 
        FROM inventory i 
        JOIN campus c ON i.campusID = c.campusID 
        WHERE i.invStatus = 'active'"; // Only select 'active' items

$params = [];

if (!empty($category_for_query)) {
    // Now using the lowercased category for comparison
    $sql .= " AND i.invCategory = :category";
    $params[':category'] = $category_for_query;
}

if (!empty($search)) {
    $sql .= " AND (i.invID LIKE :search OR i.invName LIKE :search)";
    $params[':search'] = "%" . $search . "%";
}

if (!empty($campus_filter) && strtolower($campus_filter) !== 'all') { // Ensure 'all' doesn't try to filter by campus_id = 'all'
    $sql .= " AND i.campusID = :campus";
    $params[':campus'] = $campus_filter;
}

$sql .= " ORDER BY i.invID DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($inventory);

} catch (PDOException $e) {
    error_log("Error in loadInv.php: " . $e->getMessage());
    // Provide a more generic error to the client for security, but log the detail.
    echo json_encode(["error" => "Could not fetch inventory.", "details_debug" => $e->getMessage()]); // Kept details for your debugging
}
?>