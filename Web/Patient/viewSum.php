<?php
require '../Config/database.php';  
//$patientID = $_SESSION['patientID'];
$patientID = 3;

$query = "SELECT DISTINCT a.consultDate AS dateTime, a.consultType, c.campusName, a.appID
    FROM Appointments a
    JOIN campus c ON a.campID = c.campusID
    JOIN consultationMedication cm ON a.appID = cm.appID
    JOIN userInfo u ON a.UID = u.UID
    JOIN consultSummary cs ON a.appID = cs.appID
    WHERE a.UID = :UID AND a.status = 'Prescribed'
    ORDER BY a.consultDate DESC";


$stmt = $conn->prepare($query);
$stmt->bindParam(':UID', $patientID, PDO::PARAM_INT);
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
  <style>
    h2{
        margin-left: 60px;
        margin-top: 10px;
        margin-bottom: 10px;
    }
  </style>
</head>

<body>
<div class="sidebar">
    <a href="reqConsult.php"><i class="fa-solid fa-notes-medical fa-3x" title="Request Consultation"></i></a>
    <a href="viewSum.php"><i class="fa-solid fa-clock-rotate-left fa-3x" title="History"></i></a>
    <a href="upcoming.php"><i class="fa-regular fa-calendar-xmark fa-3x" title="Appointments"></i></a>
</div>

<div class="container">    
  <main class="content">
    <nav>
      <a href="patientHome.php">Home</a>
      <a href="#" class="active">Consultation</a>
    </nav>
    <br>
    <h2>History</h2>
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
            <td><?= htmlspecialchars($row['consultType']) ?></td>
            <td><?= htmlspecialchars($row['campusName']) ?></td>
            <td><a href="#" class="view-summary" data-id="<?= $row['appID'] ?>">View</a></td>
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
<script src="../JScripts/patient.js"></script>
</body>
</html>

<?php
$conn = null;
?>
