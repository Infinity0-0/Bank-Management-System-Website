


<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // CORS के लिए

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sbi_bank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        createAccount($conn);
        break;
    case 'transaction':
        handleTransaction($conn);
        break;
    case 'modify':
        modifyAccount($conn);
        break;
    case 'delete':
        deleteAccount($conn);
        break;
    case 'view':
        viewAccount($conn);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}




function createAccount($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $required = ['number', 'name', 'dob', 'contact', 'email', 'address', 'type', 'balance'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(["error" => "Missing required field: $field"]);
            return;
        }
    }

    $stmt = $conn->prepare("INSERT INTO accounts (account_number, full_name, dob, contact, email, address, account_type, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssd", 
        $data['number'], $data['name'], $data['dob'], $data['contact'], $data['email'], $data['address'], $data['type'], $data['balance']
    );
    
    executeStatement($stmt, "Account created successfully");
}

function handleTransaction($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['number']) || empty($data['amount']) || empty($data['type'])) {
        echo json_encode(["error" => "Missing transaction parameters"]);
        return;
    }

    $stmt = $conn->prepare("SELECT balance FROM accounts WHERE account_number = ?");
    $stmt->bind_param("s", $data['number']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        echo json_encode(["error" => "Account not found"]);
        return;
    }
    
    $currentBalance = $result['balance'];
    $amount = floatval($data['amount']);
    $newBalance = ($data['type'] === 'deposit') ? $currentBalance + $amount : $currentBalance - $amount;
    
    if ($data['type'] === 'withdraw' && $currentBalance < $amount) {
        echo json_encode(["error" => "Insufficient funds"]);
        return;
    }

    $updateStmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE account_number = ?");
    $updateStmt->bind_param("ds", $newBalance, $data['number']);
    executeStatement($updateStmt, "Transaction completed. New balance: ₹$newBalance");
}

function modifyAccount($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['number'])) {
        echo json_encode(["error" => "Account number required"]);
        return;
    }
    
    $updates = [];
    $params = [];
    $types = "";
    
    foreach (["contact", "email", "address"] as $field) {
        if (!empty($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $data[$field];
            $types .= "s";
        }
    }

    if (empty($updates)) {
        echo json_encode(["error" => "No fields to update"]);
        return;
    }

    $types .= "s";
    $params[] = $data['number'];
    $query = "UPDATE accounts SET " . implode(", ", $updates) . " WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    executeStatement($stmt, "Account updated successfully");
}

function deleteAccount($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['number'])) {
        echo json_encode(["error" => "Account number required"]);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM accounts WHERE account_number = ?");
    $stmt->bind_param("s", $data['number']);
    executeStatement($stmt, "Account deleted successfully");
}

function viewAccount($conn) {
    $accountNumber = $_GET['number'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE account_number = ?");
    $stmt->bind_param("s", $accountNumber);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    echo json_encode($result ?: ["error" => "Account not found"]);
}

function executeStatement($stmt, $successMessage) {
    if ($stmt->execute()) {
        echo json_encode(["success" => $successMessage]);
    } else {
        echo json_encode(["error" => "Query failed: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>
