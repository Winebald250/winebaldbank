<?php 
function setBalance($amount, $process, $accountno)
{
    $con = new mysqli('localhost', 'root', '', 'charusat_bank');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $con->prepare("SELECT deposit FROM useraccounts WHERE accountno = ?");
    $stmt->bind_param("s", $accountno);
    $stmt->execute();
    $stmt->bind_result($deposit);
    
    // Check if a result was returned
    if ($stmt->fetch()) {
        if ($process == 'credit') {
            $deposit += $amount;
        } else {
            if ($deposit < $amount) {
                $stmt->close();
                return false; // Not enough balance to withdraw
            }
            $deposit -= $amount;
        }

        // Prepare update statement
        $updateStmt = $con->prepare("UPDATE useraccounts SET deposit = ? WHERE accountno = ?");
        $updateStmt->bind_param("is", $deposit, $accountno);
        $result = $updateStmt->execute();
        $updateStmt->close();
        
        return $result;
    } else {
        // No account found
        $stmt->close();
        return false;
    }
}

function setOtherBalance($amount, $process, $accountno)
{
    $con = new mysqli('localhost', 'root', '', 'charusat_bank');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $con->prepare("SELECT deposit FROM otheraccounts WHERE accountno = ?");
    $stmt->bind_param("s", $accountno);
    $stmt->execute();
    $stmt->bind_result($deposit);
    
    // Check if a result was returned
    if ($stmt->fetch()) {
        if ($process == 'credit') {
            $deposit += $amount;
        } else {
            if ($deposit < $amount) {
                $stmt->close();
                return false; // Not enough balance to withdraw
            }
            $deposit -= $amount;
        }

        // Prepare update statement
        $updateStmt = $con->prepare("UPDATE otheraccounts SET deposit = ? WHERE accountno = ?");
        $updateStmt->bind_param("is", $deposit, $accountno);
        $result = $updateStmt->execute();
        $updateStmt->close();
        
        return $result;
    } else {
        // No account found
        $stmt->close();
        return false;
    }
}

function makeTransaction($action, $amount, $other)
{
    $con = new mysqli('localhost', 'root', '', 'charusat_bank');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO transaction (action, debit, other, userid) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $action, $amount, $other, $_SESSION['userid']);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function makeTransactionCashier($action, $amount, $other, $userid)
{
    $con = new mysqli('localhost', 'root', '', 'charusat_bank');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO transaction (action, debit, other, userid) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $action, $amount, $other, $userid);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}
?>
