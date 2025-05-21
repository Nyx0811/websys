document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('summaryModal');
    const closeBtn = document.querySelector('.close');
    const summaryDetails = document.getElementById('summaryDetails');

    // Hide modal on page load if it's open
    modal.style.display = 'none';  // Ensures modal isn't visible by default

    document.querySelectorAll('.view-summary').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const consultID = this.dataset.id;

            // Fetch summary from server
            fetch('getSummary.php?id=' + consultID)
                .then(response => response.text())
                .then(data => {
                    summaryDetails.innerHTML = data;
                    modal.style.display = 'flex';
                });
        });
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(e) {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Optionally, prevent scroll if modal is visible
    window.addEventListener('beforeunload', function() {
        modal.style.display = 'none';  // Hide the modal before leaving the page
    });
});

//Modal Doc
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("prescriptionModal");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.getElementById("closeModal");

  document.querySelectorAll('.prescribe-btn').forEach(button => {
    button.addEventListener('click', () => {
      const patientName = button.getAttribute('data-patient-name');
      const appointmentId = button.getAttribute('data-appointment-id');

      modalBody.innerHTML = 'Loading...';
      modal.style.display = 'block';

      fetch(`prescription.php?appointment_id=${appointmentId}&patient_name=${encodeURIComponent(patientName)}`)
        .then(response => response.text())
        .then(html => {
          modalBody.innerHTML = html;
        })
        .catch(err => {
          modalBody.innerHTML = `<p style="color: red;">Error loading form</p>`;
        });
    });
  });

  closeModal.onclick = () => {
    modal.style.display = "none";
    modalBody.innerHTML = '';
  };

  window.onclick = (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
      modalBody.innerHTML = '';
    }
  };
});

