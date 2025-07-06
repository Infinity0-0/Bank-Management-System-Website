-- Database Creation
CREATE DATABASE IF NOT EXISTS sbi_bank;
USE sbi_bank;

-- Main Accounts Table
CREATE TABLE IF NOT EXISTS accounts (
    account_number BIGINT PRIMARY KEY COMMENT 'Unique 10-digit account number',
    full_name VARCHAR(100) NOT NULL COMMENT 'Account holder full name',
    dob DATE NOT NULL COMMENT 'Date of birth',
    contact VARCHAR(15) NOT NULL COMMENT 'Contact number',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email address',
    address TEXT NOT NULL COMMENT 'Residential address',
    account_type ENUM('Savings', 'Current', 'Fixed Deposit') NOT NULL DEFAULT 'Savings',
    balance DECIMAL(12,2) NOT NULL DEFAULT 0.00 CHECK (balance >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for faster queries
CREATE INDEX idx_email ON accounts (email);
CREATE INDEX idx_contact ON accounts (contact);

-- Audit Table for Tracking Changes
CREATE TABLE IF NOT EXISTS account_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    account_number BIGINT NOT NULL,
    changed_column VARCHAR(50) NOT NULL,
    old_value TEXT NOT NULL,
    new_value TEXT NOT NULL,
    change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (account_number) REFERENCES accounts(account_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger for Automatic Audit Logs
DELIMITER //
CREATE TRIGGER before_account_update
BEFORE UPDATE ON accounts
FOR EACH ROW
BEGIN
    IF OLD.balance <> NEW.balance THEN
        INSERT INTO account_audit (account_number, changed_column, old_value, new_value)
        VALUES (OLD.account_number, 'balance', OLD.balance, NEW.balance);
    END IF;
    
    IF OLD.contact <> NEW.contact THEN
        INSERT INTO account_audIT (account_number, changed_column, old_value, new_value)
        VALUES (OLD.account_number, 'contact', OLD.contact, NEW.contact);
    END IF;
END//
DELIMITER ;

-- Stored Procedure for Account Creation
DELIMITER //
CREATE PROCEDURE CreateAccount(
    IN p_full_name VARCHAR(100),
    IN p_dob DATE,
    IN p_contact VARCHAR(15),
    IN p_email VARCHAR(100),
    IN p_address TEXT,
    IN p_account_type VARCHAR(20),
    IN p_balance DECIMAL(12,2)
)
BEGIN
    DECLARE new_account_number BIGINT;
    
    -- Generate 10-digit account number
    SET new_account_number = FLOOR(1000000000 + RAND() * 9000000000);
    
    -- Insert new account
    INSERT INTO accounts (account_number, full_name, dob, contact, email, address, account_type, balance)
    VALUES (new_account_number, p_full_name, p_dob, p_contact, p_email, p_address, p_account_type, p_balance);
    
    -- Return generated account number
    SELECT new_account_number AS account_number;
END//
DELIMITER ;

-- Sample Data Insertion
INSERT INTO accounts (account_number, full_name, dob, contact, email, address, account_type, balance)
VALUES 
(1234567890, 'John Doe', '1990-01-01', '9876543210', 'john@example.com', 'Mumbai, India', 'Savings', 5000.00),
(0987654321, 'Jane Smith', '1985-05-15', '9123456780', 'jane@example.com', 'Delhi, India', 'Current', 25000.50);