
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBI Bank Management System</title>
    <link rel="stylesheet" href="bank.css">
    <script src="bank.js"></script>
    <a href="bank.php"></a>
    <a href="bank.sql"></a>

</head>
<body>
    <div class="sbi-header">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/SBI-logo.svg/1200px-SBI-logo.svg.png"
             alt="SBI Logo" class="sbi-logo">
        <h1>State Bank of India - Customer Portal</h1>
    </div>
    <div class="container">
        <div class="nav-buttons">
            <button class="nav-button" onclick="showSection('create')">New Account</button>
            <button class="nav-button" onclick="showSection('transaction')">Transactions</button>
            <button class="nav-button" onclick="showSection('modify')">Modify Account</button>
            <button class="nav-button" onclick="showSection('delete')">Close Account</button>
            <button class="nav-button" onclick="showSection('view')">View Account</button>
        </div>

        <!-- Create Account Form -->
        <div id="create" class="form-section active">
            <h2>New Account Opening Form</h2>
            <form class="bank-form" id="createForm">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>
                <!-- Removed Account Number Input Field -->
                <!-- <div class="form-group">
                    <label>Account Number:</label>
                    <input type="number" id="accNumber" name="accNumber" required>
                </div> -->
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label>Contact Number:</label>
                    <input type="tel" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label>Account Type:</label>
                    <select id="accType" name="accType">
                        <option value="Savings">Savings</option>
                        <option value="Current">Current</option>
                        <option value="Fixed Deposit">Fixed Deposit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Initial Deposit:</label>
                    <input type="number" id="balance" name="balance" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
        </div>

        <!-- Transaction Section -->
        <div id="transaction" class="form-section">
            <h2>Account Transactions</h2>
            <form id="transactionForm">
                <div class="form-group">
                    <label>Account Number:</label>
                    <input type="number" id="tAccNumber" name="tAccNumber" required>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" id="amount" name="amount" required>
                </div>
                <div class="transaction-buttons">
                    <button type="button" class="btn btn-primary"
                            onclick="performTransaction('deposit')">Deposit</button>
                    <button type="button" class="btn btn-danger"
                            onclick="performTransaction('withdraw')">Withdraw</button>
                </div>
            </form>
        </div>

        <!-- Modify Account Section -->
        <div id="modify" class="form-section">
            <h2>Modify Account Details</h2>
            <form id="modifyForm">
                <div class="form-group">
                    <label>Account Number:</label>
                    <input type="number" id="mAccNumber" name="mAccNumber" required>
                </div>
                <div class="form-group">
                    <label>New Contact Number:</label>
                    <input type="tel" id="newContact" name="newContact" required>
                </div>
                <div class="form-group">
                    <label>New Email Address:</label>
                    <input type="email" id="newEmail" name="newEmail" required>
                </div>
                <div class="form-group">
                    <label>New Address:</label>
                    <textarea id="newAddress" name="newAddress" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Details</button>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div id="delete" class="form-section">
            <h2>Close Account</h2>
            <form id="deleteForm">
                <div class="form-group">
                    <label>Account Number:</label>
                    <input type="number" id="dAccNumber" name="dAccNumber" required>
                </div>
                <button type="submit" class="btn btn-danger">Permanently Close Account</button>
            </form>
        </div>

        <!-- View Account Section -->
        <div id="view" class="form-section">
            <h2>Account Details</h2>
            <form id="viewForm">
                <div class="form-group">
                    <label>Account Number:</label>
                    <input type="number" id="vAccNumber" name="vAccNumber" required>
                    <button type="button" class="btn btn-primary"
                            onclick="viewAccount()">Search</button>
                </div>
            </form>
            <div id="accountDetails" class="account-details"></div>
        </div>
    </div>

    <!-- Success Modal (Added before </body> tag) -->
    <div class="success-modal" id="successModal">
        <div class="modal-content">
            <span class="close-btn" onclick="hideSuccess()">&times;</span>
            <h2>Account Created Successfully!</h2>
            <p>Your new account number is: <strong id="newAccountNumber"></strong></p>
            <p>Please note this number for future reference.</p>
        </div>
    </div>

        
</body>
</html>