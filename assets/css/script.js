/**
 * Generates the formatted Employee ID based on Monik Group standards.
 * Format: [CompanyShortName] + 000 + [UserInput]
 */
function genID() {
    // Get values from the dropdown and number input
    const companyCode = document.getElementById('comp').value;
    const userNumber = document.getElementById('num').value;
    const displayField = document.getElementById('finalID');

    // Logic: Only update if both fields have values
    if (companyCode && userNumber) {
        // Concatenate prefix, triple zero, and user input
        displayField.value = companyCode + "000" + userNumber;
    } else {
        // Clear the field if inputs are empty
        displayField.value = "";
    }
}

/**
 * AJAX function to check ticket status without page reload
 */
function searchTicket() {
    const tid = document.getElementById('tidInput').value;
    const resultArea = document.getElementById('resultArea');

    if (!tid) {
        resultArea.innerHTML = '<div class="alert alert-warning">Please enter a Ticket ID.</div>';
        return;
    }

    // Show loading spinner
    resultArea.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    // Fetch from the AJAX handler
    fetch(`../ajax/ajax_status.php?tid=${tid}`)
        .then(response => response.text())
        .then(data => {
            resultArea.innerHTML = data;
        })
        .catch(error => {
            resultArea.innerHTML = '<div class="alert alert-danger">Error fetching data.</div>';
        });
}