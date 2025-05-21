<?php
session_start();
require '../Config/database.php';
$_SESSION['patientID'] = 1;
if (!isset($_SESSION['patientID'])) {
  die("Unauthorized. Please log in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $patientID = $_SESSION['patientID'];
  $department = $_POST['department'];
  $consultationType = $_POST['consultationType'];
  $campus = $_POST['campus'];
  $mode = $_POST['mode'];
  $consultationDate = $_POST['consultationDate'];
  $reason = $_POST['reason'];

  $stmt = $conn->prepare("INSERT INTO consultReq 
    (patientID, department, consultationType, campus, mode, consultationDate, reason) 
    VALUES 
    (:patientID, :department, :consultationType, :campus, :mode, :consultationDate, :reason)");

  $stmt->execute([
    ':patientID' => $patientID,
    ':department' => $department,
    ':consultationType' => $consultationType,
    ':campus' => $campus,
    ':mode' => $mode,
    ':consultationDate' => $consultationDate,
    ':reason' => $reason
  ]);

  $success = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Consultation</title>
  <link rel="stylesheet" href="../Stylesheet/form.css">
  <script src="https://kit.fontawesome.com/503ea13a85.js" crossorigin="anonymous"></script>
</head>
<body>

  <div class="sidebar">
    <a href="reqConsult.php"><i class="fa-solid fa-notes-medical fa-3x" title="Request Consultation"></i></a>
    <a href="viewSum.php"><i class="fa-solid fa-clock-rotate-left fa-3x" title="History"></i></a>
  </div>

  <div class="main">
    <nav>
      <a href="patientHome.php">Home</a>
      <a href="#" class="active">Consultation</a>
    </nav>

    <div class="form-container">
      <h2>Request Consultation</h2>
      <?php if (!empty($success)): ?>
        <p style="color: green;">Consultation Request Submitted Successfully!</p>
      <?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <input type="text" placeholder="Department" name="department" required>
        </div>

        <div class="form-group">
          <select name="consultationType" required>
            <option value="">Select Consultation Type</option>
            <option value="General">General Consultation</option>
            <option value="Follow-up">Follow-up Consultation</option>
          </select>

          <select name="campus" required>
            <option value="">Select Campus</option>
            <option value="Marciano">Marciano Campus</option>
            <option value="Elida">Elida Campus</option>
          </select>
        </div>

        <div class="radio-group">
          <label><input type="radio" name="mode" value="Personal" required> Personal (Face-to-Face)</label>
          <label><input type="radio" name="mode" value="Online" required> Online Consultation</label>
        </div>

        <div class="form-group">
          <input type="date" name="consultationDate" required>
        </div>

        <div class="form-group">
          <textarea name="reason" placeholder="Reason (For Online Consultation)" required></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit</button>
      </form>
    </div>
  </div>

  <i class="fa-regular fa-user fa-2xl" id="profile"></i>
</body>
</html>
