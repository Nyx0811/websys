<?php
require '../Config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Appointments</title>
  <link rel="stylesheet" href="../Stylesheet/doctorForm.css" />
  <script src="https://kit.fontawesome.com/503ea13a85.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="sidebar">
    <a href="#"><i class="fa-solid fa-house fa-3x"></i></a>
    <a href="#"><i class="fa-solid fa-stethoscope fa-3x"></i></a>
    <a href="#"><i class="fa-solid fa-box fa-3x"></i></a>
  </div>

  <div class="main">
    <nav>
      <a href="docDashboard.php">Dashboard</a>
      <a href="#" class="active">Admission</a>
      <a href="../Inventory/INVDASH.html">Inventory</a>
    </nav>
    <br>
    <div class="appointments-container">
      <h2>My Patients | <?php echo date('F d, Y'); ?></h2>
      <table class="appointments-table">
        <thead>
          <tr>
            <th>School No.</th>
            <th>Name</th>
            <th>Position</th>
            <th>Department</th>
            <th>Date of Appointment</th>
            <th>Visit Status</th>
            <th>Type of Visit</th>
            <th>Doctor</th>
            <th>Status</th>
            <th>Link</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          try {
            $query = "SELECT 
                        a.id,
                        p.campusID as school_no,
                        CONCAT(p.firstName, ' ', COALESCE(p.lastName, '')) as patient_name,
                        pos.position_name,
                        dept.name as department,
                        DATE_FORMAT(a.appointment_date, '%Y-%m-%d %H:%i:%s') as appointment_date,
                        CASE 
                          WHEN a.status = 'Scheduled' THEN 'Follow-Up'
                          WHEN a.status = 'Completed' THEN 'New Patient'
                        END as visit_status,
                        a.type_of_admission,
                        CONCAT(d.first_name, ' ', d.last_name) as doctor_name,
                        a.status,
                        a.link
                      FROM appointments a
                      JOIN patientsInfo p ON a.patient_id = p.patientID
                      JOIN patient_departments dept ON a.dept_id = dept.id
                      JOIN doctors d ON a.doctor_id = d.id
                      JOIN patient_positions pos ON dept.position_id = pos.id
                      ORDER BY a.appointment_date DESC";

            $stmt = $conn->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['school_no']) . "</td>
                      <td>" . htmlspecialchars($row['patient_name']) . "</td>
                      <td>" . htmlspecialchars($row['position_name']) . "</td>
                      <td>" . htmlspecialchars($row['department']) . "</td>
                      <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                      <td>" . htmlspecialchars($row['visit_status']) . "</td>
                      <td>" . htmlspecialchars($row['type_of_admission']) . "</td>
                      <td>Doc. " . htmlspecialchars($row['doctor_name']) . "</td>
                      <td>" . htmlspecialchars($row['status']) . "</td>
                      <td>" . (!empty($row['link']) ? "<a href='{$row['link']}' target='_blank'>Join</a>" : "") . "</td>
                      <td><button class='prescribe-btn' data-patient-name='" . htmlspecialchars($row['patient_name']) . "' data-appointment-id='" . $row['id'] . "'>Prescribe</button></td>
                    </tr>";
            }
          } catch (PDOException $e) {
            echo "<tr><td colspan='11'>Error: " . $e->getMessage() . "</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="prescriptionModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <div id="modalBody">Loading...</div>
    </div>
  </div>

  <script src="../JScripts/modalLoader.js"></script>
</body>
</html>
