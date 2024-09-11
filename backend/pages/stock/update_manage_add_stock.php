<?php
require_once('../authen.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $serviceTypeIds = $_POST['service_type_id'];
    $stockIds = $_POST['stock_id'];
    $amounts = $_POST['amount'];

    $conn->beginTransaction();

    try {
        $checkSql = "SELECT amount FROM stock WHERE stock_id = :stockId";
        $checkStmt = $conn->prepare($checkSql);

        $updateSql = "UPDATE stock SET amount = :newAmount WHERE stock_id = :stockId";
        $updateStmt = $conn->prepare($updateSql);

        $insertSql = "INSERT INTO stock_history (stock_id, amount_change, change_date, change_type) 
                      VALUES (:stockId, :amount, NOW(), 'increase')";
        $insertStmt = $conn->prepare($insertSql);

        for ($i = 0; $i < count($stockIds); $i++) {
            $stockId = $stockIds[$i];
            $amountToAdd = intval($amounts[$i]);

            // Debugging
            echo "Processing Stock ID: $stockId with amount: $amountToAdd<br>";

            $checkStmt->execute(['stockId' => $stockId]);
            $currentAmount = $checkStmt->fetchColumn();

            if ($currentAmount === false) {
                throw new Exception("Stock ID $stockId not found.");
            }

            $newAmount = $currentAmount + $amountToAdd;

            $updateStmt->execute([
                'newAmount' => $newAmount,
                'stockId' => $stockId
            ]);

            $insertStmt->execute([
                'stockId' => $stockId,
                'amount' => $amountToAdd
            ]);
        }

        $conn->commit();
        $message = "Stock amounts updated successfully.";
        $status = "success";
    } catch (PDOException $e) {
        $conn->rollBack();
        $message = "Error updating stock amounts: " . $e->getMessage();
        $status = "error";
    } catch (Exception $e) {
        $conn->rollBack();
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }

    header("Location: index.php?status=$status&message=" . urlencode($message));
    exit;
}

header("Location: index.php?status=error&message=" . urlencode("Invalid form submission"));
exit;
?>
