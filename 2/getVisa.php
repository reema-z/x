<?php
session_start();

require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: signIn.php");
    exit();
}

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="43200">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Visa</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/getVisa.css">
    <style>
        .welcome-message {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            border-radius: 4px;
        }
        .visa-details {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #2196f3;
        }
    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <a href="mailto:445001472@sm.edu.imamu.sa">Email: 445001472@sm.edu.imamu.sa</a> |
            <a href="tel:+966552616596">Phone: +966 552616596</a> |
            <a href="https://www.linkedin.com/in/reema-alzoman-6b30732a7?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank">LinkedIn</a> |
            <a href="https://github.com/reema-z/web-devolpment-project.git" target="_blank">GitHub</a>
        </div>
    </header>
    
    <?php include 'navbar.php'; ?>
    
    <main class="centered-container">
        <div class="visa-form-box">
            <h2>GET VISA</h2>
            
            <div class="welcome-message">
                Welcome, <?php echo htmlspecialchars($user['name']); ?>! You can now apply for a visa.
            </div>
            
            <form id="visaForm" method="post" action="processVisa.php">
                <label for="visaNationality" style="text-align: left;">Nationality:</label>
                <select id="visaNationality" name="nationality" required>
                    <option value="" disabled selected>Select your Nationality</option>
                    <option value="American">American</option>
                    <option value="Australian">Australian</option>
                    <option value="Brazilian">Brazilian</option>
                    <option value="Canadian">Canadian</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Egyptian">Egyptian</option>
                    <option value="French">French</option>
                    <option value="German">German</option>
                    <option value="Indian">Indian</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Mexican">Mexican</option>
                    <option value="Saudi">Saudi</option>
                    <option value="South African">South African</option>
                    <option value="United Kingdom">United Kingdom</option>
                </select>
                
                <label for="visaType" style="text-align: left;">Visa Type:</label>
                <select id="visaType" name="visa_type" required>
                    <option value="" disabled selected>Select a Visa Type</option>
                    <option value="eVisa">eVisa</option>
                    <option value="tourist-visa">Tourist Visa</option>
                    <option value="business-visa">Business Visa</option>
                </select>
                
                <div id="visaDetailsOutput" class="visa-details">
                    Select a visa type above to see details.
                </div>
                
                <label for="passportNumber">Passport Number:</label>
                <input type="text" id="passportNumber" name="passport_number" required 
                       pattern="[A-Z0-9]{6,12}" title="Enter a valid passport number">
                
                <label for="passportExpiry">Passport Expiry Date:</label>
                <input type="date" id="passportExpiry" name="passport_expiry" required>
                
                <label for="purpose">Purpose of Visit:</label>
                <textarea id="purpose" name="purpose" rows="3" required 
                          placeholder="Briefly describe the purpose of your visit"></textarea>
                
                <button type="submit">Apply for Visa</button>
            </form>
        </div>
    </main>
    
    <footer>
        &copy;2025-26 / IMSIU / CCIS<sup>TM</sup>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var visaTypeSelect = document.getElementById('visaType');
            var detailsOutput = document.getElementById('visaDetailsOutput');
            
            var visaDetails = {
                'eVisa': 'Valid for 90 days, single entry. Application is online and takes approximately 1-3 business days. Processing fee: $120.',
                'tourist-visa': 'Valid for up to 1 year, multiple entry. Allows a stay of up to 90 days per visit. Processing fee: $200.',
                'business-visa': 'Requires an official invitation from a Saudi entity. Valid for specific business activities; duration varies. Processing fee: $300.',
                '': 'Select a visa type above to see details.'
            };
            
            if (visaTypeSelect && detailsOutput) {
                visaTypeSelect.addEventListener('change', function() {
                    var selectedValue = visaTypeSelect.value;
                    detailsOutput.innerHTML = visaDetails[selectedValue] || visaDetails[''];
                });
            }
            
            // Form submission
            var visaForm = document.getElementById('visaForm');
            if (visaForm) {
                visaForm.addEventListener('submit', function(event) {
                    var passportExpiry = new Date(document.getElementById('passportExpiry').value);
                    var today = new Date();
                    
                    if (passportExpiry <= today) {
                        alert('Passport must be valid (expiry date must be in the future).');
                        event.preventDefault();
                        return false;
                    }
                    
                    var visaType = document.getElementById('visaType').value;
                    var nationality = document.getElementById('visaNationality').value;
                    
                    if (confirm('Are you sure you want to apply for a ' + visaType + ' visa?')) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>