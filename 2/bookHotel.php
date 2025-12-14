<?php
session_start();

require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo '<script>alert("Please log in to book a hotel."); window.location.href = "signIn.php";</script>';
    exit();
}

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Hotel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/bookHotel.css">
    <style>
        .user-info {
            background-color: #e8f5e9;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
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
    
    <main class="BookHotel-container">
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($user['name']); ?>! You can now book a hotel.
        </div>
        
        <form action="processHotelBooking.php" method="post">
            <h2>Book Your Hotel</h2>
            
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
            
            <label for="City">City:</label>
            <select id="City" name="City" required>
                <option value="" disabled selected>Select a City</option>
                <option value="Riyadh">Riyadh</option>
                <option value="Jeddah">Jeddah</option>
                <option value="AlUla">AlUla</option>
            </select>
            
            <label for="hotel">Hotel Name:</label>
            <select id="hotel" name="hotel" required>
                <option value="" disabled selected>Select a Hotel</option>
                <option value="Narcissus The Royal Hotel">Narcissus The Royal Hotel</option>
                <option value="ibis Jeddah City Center">ibis Jeddah City Center</option>
                <option value="Cloud 7 Residence AlUla">Cloud 7 Residence AlUla</option>
            </select>
            
            <label for="checkin">Check-In Date:</label>
            <input type="date" id="checkin" name="checkin" required>
            
            <label for="checkout">Check-Out Date:</label>
            <input type="date" id="checkout" name="checkout" required>
            
            <label for="rooms">Number of Rooms:</label>
            <input type="number" id="rooms" name="rooms" min="1" max="5" required value="1">
            
            <label>Room Type:</label>
            <div class="radio-group">
                <label><input type="radio" name="roomType" value="Single" required checked> Single</label>
                <label><input type="radio" name="roomType" value="Double"> Double</label>
                <label><input type="radio" name="roomType" value="Suite"> Suite</label>
            </div>
            
            <label for="guests">Number of Guests:</label>
            <input type="number" id="guests" name="guests" min="1" max="10" required value="1">
            
            <label for="specialRequests">Special Requests:</label>
            <textarea id="specialRequests" name="specialRequests" rows="3" 
                      placeholder="Any special requests or requirements"></textarea>
            
            <button type="submit">Book Now</button>
        </form>
        <div id="result"></div>
    </main>
    
    <footer>
        &copy;2025-26 / IMSIU / CCIS<sup>TM</sup>
    </footer>
    
    <script>
        function checkDate(event) {
            var ChkIn_Date = new Date(document.getElementById("checkin").value);
            var ChkOut_Date = new Date(document.getElementById("checkout").value);
            var today = new Date();
            
            // Reset time part for comparison
            today.setHours(0, 0, 0, 0);
            
            var result = document.getElementById("result");
            result.innerHTML = "";
            
            if (!ChkIn_Date || !ChkOut_Date) {
                result.innerHTML = "<p style='color: red;'>Please select both check-in and check-out dates!</p>";
                event.preventDefault();
                return;
            }
            
            if (ChkOut_Date <= ChkIn_Date) {
                result.innerHTML = "<p style='color: red;'>Check-out date must be after check-in date!</p>";
                event.preventDefault();
            }
            else if (ChkIn_Date < today) {
                result.innerHTML = "<p style='color: red;'>Check-in date cannot be in the past!</p>";
                event.preventDefault();
            }
            else {
                // Calculate number of nights
                var timeDiff = ChkOut_Date.getTime() - ChkIn_Date.getTime();
                var nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (nights > 30) {
                    result.innerHTML = "<p style='color: orange;'>Note: Booking is for " + nights + " nights. For stays longer than 30 days, please contact the hotel directly.</p>";
                } else {
                    result.innerHTML = "<p style='color: green;'>Booking for " + nights + " night(s).</p>";
                }
            }
        }
        
        document.querySelector("form").addEventListener("submit", checkDate);
        
        // Set minimum date for check-in to today
        window.onload = function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("checkin").setAttribute('min', today);
            document.getElementById("checkin").value = today;
            
            // Set check-out to tomorrow by default
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById("checkout").value = tomorrow.toISOString().split('T')[0];
            document.getElementById("checkout").setAttribute('min', tomorrow.toISOString().split('T')[0]);
            
            // Update check-out min date when check-in changes
            document.getElementById("checkin").addEventListener('change', function() {
                var checkinDate = new Date(this.value);
                var nextDay = new Date(checkinDate);
                nextDay.setDate(checkinDate.getDate() + 1);
                document.getElementById("checkout").setAttribute('min', nextDay.toISOString().split('T')[0]);
                
                // If current check-out is before new minimum, reset it
                var currentCheckout = new Date(document.getElementById("checkout").value);
                if (currentCheckout <= checkinDate) {
                    document.getElementById("checkout").value = nextDay.toISOString().split('T')[0];
                }
            });
        };
    </script>
</body>
</html>