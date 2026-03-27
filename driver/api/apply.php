<?php
// 1. SILENCE ALL ERRORS - We will handle them manually to ensure valid JSON output
error_reporting(0);
ini_set('display_errors', 0);

// 2. START BUFFER - Capture any accidental output from included files
ob_start();

header('Content-Type: application/json');

try {
    // 3. DATABASE CONNECTION
    require '../../api/db_connect.php';
    if (!$conn) {
        throw new Exception("Database connection failed.");
    }

    // 4. DATA COLLECTION (Handling both FormData and JSON fallbacks)
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $license = $_POST['license'] ?? '';
    $govId = $_POST['govId'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';

    // Fallback for JSON
    if (empty($firstName) && empty($email)) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data) {
            $firstName = $data['firstName'] ?? '';
            $lastName = $data['lastName'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $contact = $data['contact'] ?? '';
            $license = $data['license'] ?? '';
            $govId = $data['govId'] ?? '';
            $address = $data['address'] ?? '';
            $gender = $data['gender'] ?? '';
        }
    }

    // 5. VALIDATION
    if (empty($email) || empty($password) || empty($contact) || empty($license)) {
        throw new Exception("Email, Password, Mobile Number, and License Number are required.");
    }

    if (!preg_match('/@gmail\.com$/i', $email)) {
        throw new Exception("Email must end with @gmail.com.");
    }

    if (strlen($password) < 6 || strlen($password) > 32) {
        throw new Exception("Password length must be between 6 and 32 characters.");
    }

    $contactDigits = preg_replace('/\D+/', '', $contact);
    if (strlen($contactDigits) >= 10) {
        $contactDigits = substr($contactDigits, -10);
    }
    if (strlen($contactDigits) !== 10) {
        throw new Exception("Mobile number must be exactly 10 digits.");
    }

    if (!preg_match('/^[A-Z0-9]{10,16}$/', strtoupper($license))) {
        throw new Exception("Driving License must be 10 to 16 alphanumeric characters.");
    }

    // 6. SANITIZATION
    $fullname = $conn->real_escape_string($firstName . ' ' . $lastName);
    $email = $conn->real_escape_string($email);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $contact = $conn->real_escape_string('+91 ' . $contactDigits);
    $license = $conn->real_escape_string(strtoupper($license));
    $govId = $conn->real_escape_string($govId);
    $address = $conn->real_escape_string($address);
    $gender = $conn->real_escape_string($gender);

    // 7. DUPLICATE CHECK
    $check = $conn->query("SELECT id FROM drivers WHERE email = '$email' OR license_no = '$license'");
    if($check && $check->num_rows > 0) {
        throw new Exception("An account with this email or license number already exists.");
    }

    // 8. FILE UPLOAD ( KYC Photo)
    $profile_image = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../../uploads/drivers/";
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0777, true);
        }
        
        if (is_dir($target_dir)) {
            $file_ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $file_name = time() . "_" . uniqid() . "." . $file_ext;
            $target_file = $target_dir . $file_name;
            
            if (@move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $profile_image = "uploads/drivers/" . $file_name;
            }
        }
    }

    // 9. DATABASE INSERT
    // We use a safe query structure and check for column existence implicitly by checking query success
    $sql = "INSERT INTO drivers (name, email, password, contact, license_no, gov_id, address, gender, profile_image, status, is_approved, is_active) 
            VALUES ('$fullname', '$email', '$hashed_password', '$contact', '$license', '$govId', '$address', '$gender', '$profile_image', 'offline', 0, 0)";
    
    if ($conn->query($sql)) {
        // Clear any buffer garbage before sending success JSON
        ob_clean();
        echo json_encode(["success" => true, "message" => "Application Submitted Successfully"]);
    } else {
        // Here is where we catch "Unknown column" errors if setup.php wasn't run
        throw new Exception("Database Error: " . $conn->error . ". (Tip: Try running admin/setup.php to update tables)");
    }

} catch (Exception $e) {
    // 10. ERROR RESPONSE
    ob_clean(); // Discard any output/warnings
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
