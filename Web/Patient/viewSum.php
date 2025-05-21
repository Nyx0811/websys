<?php
require '../Config/database.php';  
//$patientID = $_SESSION['patientID'];
$patientID = 1;
$query = "SELECT cr.consultationDate AS dateTime, cr.consultationType, cr.campus, cs.consultID 
          FROM consultReq cr
          JOIN consultationSummary cs ON cr.consultID = cs.consultID
          WHERE cr.patientID = :patientID
          ORDER BY cr.consultationDate DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);
$stmt->execute();
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Consultation History</title>
  <link rel="stylesheet" href="../Stylesheet/summ.css"/>
  <script src="https://kit.fontawesome.com/503ea13a85.js" crossorigin="anonymous"></script>
</head>

<body>
<div class="sidebar">
    <a href="reqConsult.php"><i class="fa-solid fa-notes-medical fa-3x" title="Request Consultation"></i></a>
    <a href="viewSum.php"><i class="fa-solid fa-clock-rotate-left fa-3x" title="History"></i></a>
</div>

<div class="container">    
  <main class="content">
    <nav>
      <a href="patientHome.php">Home</a>
      <a href="#" class="active">Consultation</a>
    </nav>
    <br>
    <h1><strong>History</strong></h1>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Consultation Type</th>
          <th>Campus</th>
          <th>Summary</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($consultations as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['dateTime']) ?></td>
            <td><?= htmlspecialchars($row['consultationType']) ?></td>
            <td><?= htmlspecialchars($row['campus']) ?></td>
            <td><a href="#" class="view-summary" data-id="<?= $row['consultID'] ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</div>

<i class="fa-regular fa-user fa-2xl" id="profile"></i>

<!-- Modal -->
<div id="summaryModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="summaryDetails">
      <!-- Loaded details will appear here -->
    </div>
  </div>
</div>
<script src="../JScripts/index.js"></script>
</body>
</html>

<?php
$conn = null;
?>
