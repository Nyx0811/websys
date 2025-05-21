<?php
$appointmentId = $_GET['appointment_id'] ?? '';
$patientName = $_GET['patient_name'] ?? '';
?>
<h3>Prescription for <?php echo htmlspecialchars($patientName); ?></h3>

<form method="POST" action="submitPrescription.php">
  <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointmentId); ?>" />

  <label for="medication">Medication:</label><br>
  <input type="text" id="medication" name="medication" required><br><br>

  <label for="dosage">Dosage:</label><br>
  <input type="text" id="dosage" name="dosage" required><br><br>

  <label for="notes">Notes:</label><br>
  <textarea id="notes" name="notes" rows="4"></textarea><br><br>

  <input type="submit" value="Submit Prescription">
</form>
