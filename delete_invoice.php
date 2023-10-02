<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nour";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $invoiceID = $_GET["id"];

    $sql = "DELETE FROM invoices WHERE InvoiceID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $invoiceID);

        if ($stmt->execute()) {
            echo "<script>" . "window.location.href='./facturation.php';" . "</script>";
            exit;
        } else {
            echo "Error deleting invoice: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "<script>" . "window.location.href='./facturation.php';" . "</script>";
    exit;
}
?>
