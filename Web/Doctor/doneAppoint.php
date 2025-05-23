<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../Config/database.php'; // Ensure this sets up $conn as a PDO instance
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prescribed Consultations</title>
  <link rel="stylesheet" href="../Stylesheet/consultGrant.css">
  <script src="https://kit.fontawesome.com/503ea13a85.js" crossorigin="anonymous"></script>
  <style>
    .view-btn {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 5px 12px;
      font-size: 14px;
      border-radius: 5px;
      cursor: pointer;
    }
    .view-btn:hover {
      background-color: #45a049;
    }
#searchInput {
  margin-bottom: 15px;
  padding: 8px;
  width: 300px;
  font-size: 16px;
  margin-left: 0;
}

.main-content h2:first-of-type {
  margin-left: 0; /* or any value you want */
  font-weight: bold;
  color: #333;
}


.prescribed, .navigation {
  margin-bottom: 15px;
  margin-left: 0;
}
    /* Modal styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }
    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 60%;
      max-width: 700px;
      position: relative;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover {
      color: black;
    }

    /* Container aligns items horizontally */
.report-controls {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
  padding: 0.5rem 0;
}

/* Label spacing */
.report-controls label {
  font-weight: 600;
  color: #333;
}

/* Styled dropdown */
.report-select {
  padding: 0.4rem 0.6rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.95rem;
  background: #fff;
  cursor: pointer;
}

/* Primary button */
.report-btn {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  background: #007BFF;
  color: #fff;
  border: none;
  padding: 0.5rem 1rem;
  font-size: 0.95rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.report-btn:hover {
  background: #0056b3;
}

.report-btn i {
  font-size: 1rem;
}

</style>
</head>
<body>
  <div class="sidebar">
    <a href="viewAppointments.php"><i class="fa-solid fa-house fa-3x"></i></a>
    <a href="consultGrant.php"><i class="fa-solid fa-clipboard fa-3x"></i></a>
    <a href="#"><i class="fa-solid fa-book-medical fa-3x"></i></i></a>
  </div>
<div class="navigation">
  <nav>
      <a href="docDashboard.php">Dashboard</a>
      <a href="#" class="active">Admission</a>
      <a href="../Inventory/INVDASH.html">Inventory</a>
    </nav>
    </div>
      <div class="main-content">
        <br>
        <h2>Prescribed Consultations | <?= date('F d, Y') ?></h2>
        <input type="text" id="searchInput" placeholder="Search by name..." style="margin-bottom: 15px; padding: 8px; width: 300px; font-size: 16px;">
    <div style="margin-bottom:1rem; display:flex; align-items:center;">
      <label for="reportSpan" style="margin-right:0.5rem;">Print for:</label>
      <select id="reportSpan" style="padding:4px;">
        <option value="day">Today</option>
        <option value="week">This Week</option>
        <option value="month">This Month</option>
      </select>
      <button id="printReportBtn" style="margin-left:1rem; padding:6px 12px;">Print Report</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>School No.</th>
          <th>Name</th>
          <th>Position</th>
          <th>Department</th>
          <th>Consult Date</th>
          <th>Consult Type</th>
          <th>Mode</th>
          <th>Campus</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        try {
          $sql = "SELECT 
            a.appID,
            u.schoolID AS school_no,
            CONCAT(u.fname, ' ', u.lname) AS name,
            r.roleName AS position,
            dept.deptName AS department,
            a.consultDate AS consult_date,
            a.consultType AS consult_type,
            a.mode,
            c.campusName AS campus
          FROM appointments a
          JOIN userInfo u ON a.UID = u.UID
          JOIN campus c ON a.campID = c.campusID
          JOIN departments dept ON u.deptID = dept.deptID
          JOIN userroles ur ON u.UID = ur.UID
          JOIN roles r ON ur.roleID = r.roleID
          WHERE a.status = 'Prescribed'
          ORDER BY a.consultDate DESC;";

          $stmt = $conn->prepare($sql);
          $stmt->execute();

          if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['school_no']) . "</td>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . ucfirst(htmlspecialchars($row['position'])) . "</td>";
              echo "<td>" . htmlspecialchars($row['department']) . "</td>";
              echo "<td>" . htmlspecialchars($row['consult_date']) . "</td>";
              echo "<td>" . htmlspecialchars($row['consult_type']) . "</td>";
              echo "<td>" . htmlspecialchars($row['mode']) . "</td>";
              echo "<td>" . htmlspecialchars($row['campus']) . "</td>";
              echo "<td><button class='view-btn' data-id='" . $row['appID'] . "'>View</button></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='9' style='text-align:center;'>No prescribed consultations found</td></tr>";
          }
        } catch (PDOException $e) {
          echo "<tr><td colspan='9' style='color:red;'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div id="summaryModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="modalDetails">Loading...</div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.view-btn').forEach(button => {
      button.addEventListener('click', function () {
        const appID = this.getAttribute('data-id');
        fetch('getConsultDetails.php?id=' + appID)
          .then(response => response.text())
          .then(data => {
            document.getElementById('modalDetails').innerHTML = data;
            document.getElementById('summaryModal').style.display = 'block';
        });
      });
    });
    document.getElementById('printReportBtn').addEventListener('click', () => {
      const span = document.getElementById('reportSpan').value;
      // Open report.php in a new window/tab
      window.open(`report.php?span=${encodeURIComponent(span)}`, '_blank');
    });

    document.querySelector('.close').onclick = function () {
      document.getElementById('summaryModal').style.display = 'none';
    };

    window.onclick = function (event) {
      if (event.target == document.getElementById('summaryModal')) {
        document.getElementById('summaryModal').style.display = 'none';
      }      
    };

    document.getElementById('searchInput').addEventListener('keyup', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll("table tbody tr");

  rows.forEach(row => {
    const nameCell = row.cells[1]; // Name is in the 2nd column (index 1)
    if (nameCell) {
      const name = nameCell.textContent.toLowerCase();
      row.style.display = name.includes(filter) ? "" : "none";
    }
  });
});    
  </script>

  <i class="fa-regular fa-user" id="profile"></i>
</body>
</html>
