<?php
require '../Config/database.php';  

if (isset($_GET['id'])) {
    $consultID = (int) $_GET['id'];

    // Fetch consultation details
    $query = "SELECT cr.consultationDate, cr.consultationType, cr.campus, cs.summary, cs.diagnosis, cs.recommendations
          FROM consultReq cr
          JOIN consultationSummary cs ON cr.consultID = cs.consultID
          WHERE cr.consultID = :consultID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':consultID', $consultID, PDO::PARAM_INT);
    $stmt->execute();
    $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($consultation) {
        echo "<h2>Consultation Details</h2>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($consultation['consultationDate']) . "</p>";
        echo "<p><strong>Type:</strong> " . htmlspecialchars($consultation['consultationType']) . "</p>";
        echo "<p><strong>Campus:</strong> " . htmlspecialchars($consultation['campus']) . "</p>";
        echo "<p><strong>Remarks:</strong> " . (!empty($consultation['remarks']) ? htmlspecialchars($consultation['remarks']) : 'No remarks.') . "</p>";

        echo "<hr>";

        echo "<h2>Summary</h2>";
        echo "<p>" . htmlspecialchars($consultation['summary']) . "</p>";

        echo "<h2>Diagnosis</h2>";
        echo "<p>" . htmlspecialchars($consultation['diagnosis']) . "</p>";

        echo "<h2>Recommendations</h2>";
        echo "<p>" . htmlspecialchars($consultation['recommendations']) . "</p>";

        echo "<hr>";

        // Fetch medications from consultationMedications
        $medQuery = "SELECT medicationName, dosage, frequency, duration
                     FROM consultationMedications
                     WHERE consultID = :consultID";
        $medStmt = $conn->prepare($medQuery);
        $medStmt->bindParam(':consultID', $consultID, PDO::PARAM_INT);
        $medStmt->execute();
        $medications = $medStmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Medications</h2>";
        if (!empty($medications)) {
            echo "<ul>";
            foreach ($medications as $med) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($med['medicationName']) . "</strong><br>";
                echo "Dosage: " . htmlspecialchars($med['dosage']) . "<br>";
                echo "Frequency: " . htmlspecialchars($med['frequency']) . "<br>";
                echo "Duration: " . htmlspecialchars($med['duration']) . "<br><br>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No medications prescribed.</p>";
        }
    } else {
        echo "<p>Summary not found.</p>";
    }
} else {
    echo "<p>No consultation selected.</p>";
}

$conn = null;
?>
