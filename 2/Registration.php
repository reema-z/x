<?php
session_start();

require_once 'config.php';

$errors = [];
$success = '';
$formData = [
    'name' => '',
    'id' => '',
    'dob' => '',
    'nationality' => '',
    'mobile' => '',
    'email' => '',
    'semester' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'id' => trim($_POST['id'] ?? ''),
        'dob' => $_POST['dob'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'mobile' => trim($_POST['mobile'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'semester' => $_POST['Semster'] ?? '',
        'password' => $_POST['password'] ?? '',
        'repassword' => $_POST['Repassword'] ?? '',
        'captcha_input' => $_POST['captchaInput'] ?? '',
        'captcha_stored' => $_POST['captcha'] ?? ''
    ];
    
    // Validation
    if (empty($formData['name'])) $errors['name'] = "Name is required";
    elseif (strlen($formData['name']) < 3) $errors['name'] = "Name must be at least 3 characters";
    
    if (empty($formData['id'])) $errors['id'] = "ID is required";
    elseif (!preg_match('/^[0-9]{8,}$/', $formData['id'])) $errors['id'] = "ID must contain 8 or more digits";
    
    if (empty($formData['dob'])) $errors['dob'] = "Date of birth is required";
    else {
        $dob = new DateTime($formData['dob']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 18) $errors['dob'] = "You must be at least 18 years old";
    }
    
    if (empty($formData['nationality'])) $errors['nationality'] = "Nationality is required";
    if (empty($formData['mobile'])) $errors['mobile'] = "Mobile number is required";
    elseif (!preg_match('/^[0-9]{9,}$/', $formData['mobile'])) $errors['mobile'] = "Mobile must contain 9 or more digits";
    
    if (empty($formData['email'])) $errors['email'] = "Email is required";
    elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format";
    
    if (empty($formData['semester'])) $errors['semester'] = "Semester is required";
    elseif (!is_numeric($formData['semester']) || $formData['semester'] < 1 || $formData['semester'] > 8) {
        $errors['semester'] = "Semester must be between 1 and 8";
    }
    
    if (empty($formData['password'])) $errors['password'] = "Password is required";
    elseif (strlen($formData['password']) < 8) $errors['password'] = "Password must be at least 8 characters";
    elseif (!preg_match('/[A-Z]/', $formData['password']) || !preg_match('/[0-9]/', $formData['password'])) {
        $errors['password'] = "Password must contain at least 1 uppercase letter and 1 number";
    }
    
    if ($formData['password'] !== $formData['repassword']) {
        $errors['repassword'] = "Passwords do not match";
    }
    
    if ($formData['captcha_input'] !== $formData['captcha_stored']) {
        $errors['captcha'] = "CAPTCHA incorrect";
    }
    
    // Check if user already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE  id_number = ? OR email = ?");
        $stmt->execute([$formData['id'], $formData['email']]);
        
        if ($stmt->rowCount() > 0) {
            $errors['general'] = "User ID or Email already exists!";
        } else {
            // Insert into database
            $hashed_password = password_hash($formData['password'], PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (name, id_number, dob, nationality, mobile, email, semester, password) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            try {
                $stmt->execute([
                    $formData['name'],
                    $formData['id'],
                    $formData['dob'],
                    $formData['nationality'],
                    $formData['mobile'],
                    $formData['email'],
                    $formData['semester'],
                    $hashed_password
                ]);
                
                // Redirect to sign in page with success message
                header("Location: signIn.php?registered=success");
                exit();
            } catch (PDOException $e) {
                $errors['general'] = "Registration failed: " . $e->getMessage();
            }
        }
    }
}

// Generate CAPTCHA
$captcha = rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/Registeration.css">
    <style>
        .error { color: #d32f2f; font-size: 0.9rem; margin-top: 4px; }
        .form-error { color: #d32f2f; background-color: #ffebee; border: 1px solid #ef9a9a; 
                     padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
        .success { color: #2e7d32; background-color: #e8f5e9; border: 1px solid #a5d6a7; 
                   padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
        .captcha-display {
            font-weight: bold;
            font-size: 1.5em;
            letter-spacing: 5px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
            text-align: center;
            margin: 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
 
    <main class="registration-container">
        <h1>CREATE NEW ACCOUNT</h1>
        
        <?php if (!empty($errors['general'])): ?>
            <div class="form-error"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>
        
        <form id="registrationForm" method="POST" action="">
            <input type="hidden" id="captcha" name="captcha" value="<?php echo $captcha; ?>">
            
            <p id="p1">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required minlength="3" 
                       value="<?php echo htmlspecialchars($formData['name']); ?>">
                <?php if (!empty($errors['name'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p2">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required pattern="[0-9]{8,}" 
                       title="ID must contain 8 or more digits (numbers only)" 
                       value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php if (!empty($errors['id'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['id']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p3">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required value="<?php echo htmlspecialchars($formData['dob']); ?>">
                <?php if (!empty($errors['dob'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['dob']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p4">
                <label for="nationality" style="text-align: left;">Nationality:</label>
                <select id="nationality" name="nationality" required>
                    <option value="" disabled <?php echo empty($formData['nationality']) ? 'selected' : ''; ?>>Select your Nationality</option>
                    <option value="American" <?php echo $formData['nationality'] == 'American' ? 'selected' : ''; ?>>American</option>
                    <option value="Australian" <?php echo $formData['nationality'] == 'Australian' ? 'selected' : ''; ?>>Australian</option>
                    <option value="Brazilian" <?php echo $formData['nationality'] == 'Brazilian' ? 'selected' : ''; ?>>Brazilian</option>
                    <option value="Canadian" <?php echo $formData['nationality'] == 'Canadian' ? 'selected' : ''; ?>>Canadian</option>
                    <option value="Chinese" <?php echo $formData['nationality'] == 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                    <option value="Egyptian" <?php echo $formData['nationality'] == 'Egyptian' ? 'selected' : ''; ?>>Egyptian</option>
                    <option value="French" <?php echo $formData['nationality'] == 'French' ? 'selected' : ''; ?>>French</option>
                    <option value="German" <?php echo $formData['nationality'] == 'German' ? 'selected' : ''; ?>>German</option>
                    <option value="Indian" <?php echo $formData['nationality'] == 'Indian' ? 'selected' : ''; ?>>Indian</option>
                    <option value="Japanese" <?php echo $formData['nationality'] == 'Japanese' ? 'selected' : ''; ?>>Japanese</option>
                    <option value="Mexican" <?php echo $formData['nationality'] == 'Mexican' ? 'selected' : ''; ?>>Mexican</option>
                    <option value="Saudi" <?php echo $formData['nationality'] == 'Saudi' ? 'selected' : ''; ?>>Saudi</option>
                    <option value="South African" <?php echo $formData['nationality'] == 'South African' ? 'selected' : ''; ?>>South African</option>
                    <option value="United Kingdom" <?php echo $formData['nationality'] == 'United Kingdom' ? 'selected' : ''; ?>>United Kingdom</option>
                </select>
                <?php if (!empty($errors['nationality'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['nationality']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p5">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{9,}" 
                       title="Please enter a valid mobile number (9 digits minimum)" 
                       value="<?php echo htmlspecialchars($formData['mobile']); ?>">
                <?php if (!empty($errors['mobile'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['mobile']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p6">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($formData['email']); ?>">
                <?php if (!empty($errors['email'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p7">
                <label for="Semster">Semester:</label>
                <input type="number" id="Semster" name="Semster" required step="1" min="1" max="8" 
                       value="<?php echo htmlspecialchars($formData['semester']); ?>">
                <?php if (!empty($errors['semester'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['semester']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p8">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8" 
                       title="Password must be at least 8 characters long">
                <?php if (!empty($errors['password'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['password']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p9">
                <label for="Repassword">Re-enter Password:</label>
                <input type="password" id="Repassword" name="Repassword" required minlength="8">
                <?php if (!empty($errors['repassword'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['repassword']); ?></div>
                <?php endif; ?>
            </p>

            <p id="p10">
                <label>CAPTCHA:</label>
                <div class="captcha-display"><?php echo $captcha; ?></div>
                <label for="captchaInput">Enter CAPTCHA:</label>
                <input type="text" id="captchaInput" name="captchaInput" required>
                <?php if (!empty($errors['captcha'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['captcha']); ?></div>
                <?php endif; ?>
            </p>

            <button type="submit">Register and Proceed to Sign In</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            <a href="signIn.php" style="color: aquamarine;">Already Registered? Sign In</a>
        </p>
    </main>
    
    <script>
        // Client-side validation
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            let valid = true;
            const password = document.getElementById('password');
            const repassword = document.getElementById('Repassword');
            const dob = document.getElementById('dob');
            const semester = document.getElementById('Semster');
            
            // Password validation
            if (password.value.length < 8) {
                alert("Password must be at least 8 characters long!");
                valid = false;
            }
            
            // Password pattern validation
            const pattern = /(?=.*[A-Z])(?=.*[0-9])/;
            if (!pattern.test(password.value)) {
                alert("Password must contain at least 1 uppercase letter and 1 number!");
                valid = false;
            }
            
            // Password match validation
            if (password.value !== repassword.value) {
                alert("Passwords do not match!");
                valid = false;
            }
            
            // Age validation
            if (dob.value) {
                const today = new Date();
                const birthDate = new Date(dob.value);
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                if (age < 18) {
                    alert("You must be at least 18 years old!");
                    valid = false;
                }
            }
            
            // Semester validation
            const semValue = parseInt(semester.value);
            if (semValue < 1 || semValue > 8 || isNaN(semValue)) {
                alert("Semester must be a number between 1 and 8!");
                valid = false;
            }
            
            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>