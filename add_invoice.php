<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nour"; 

$student_id = $month = $year = $total_amount = $payment_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = isset($_POST["student_id"]) ? htmlspecialchars($_POST["student_id"]) : "";
    $month = isset($_POST["month"]) ? htmlspecialchars($_POST["month"]) : "";
    $year = isset($_POST["year"]) ? htmlspecialchars($_POST["year"]) : "";
    $total_amount = isset($_POST["total_amount"]) ? htmlspecialchars($_POST["total_amount"]) : "";
    $payment_status = isset($_POST["payment_status"]) ? htmlspecialchars($_POST["payment_status"]) : "";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $invoiceSql = "INSERT INTO invoices (StudentID, Month, Year, TotalAmount, PaymentStatus) VALUES (?, ?, ?, ?, ?)";

    $billingSql = "INSERT INTO billing (StudentID, Month, Year, TotalAmount) VALUES (?, ?, ?, ?)";

    $invoiceStmt = $conn->prepare($invoiceSql);
    $billingStmt = $conn->prepare($billingSql);

    $invoiceStmt->bind_param("sssss", $student_id, $month, $year, $total_amount, $payment_status);
    $invoiceResult = $invoiceStmt->execute();

    $billingStmt->bind_param("ssss", $student_id, $month, $year, $total_amount);
    $billingResult = $billingStmt->execute();

    if ($invoiceResult && $billingResult) {
        header("location: facturation.php");
        exit();
    } else {
        $error_message = "Error: " . $conn->error;
    }

    $invoiceStmt->close();
    $billingStmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فاتورة جديدة</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        label {
            text-align: right;
            display: block;
        }

        .form-control {
            margin: 0 auto;
            text-align: center;
        }

        .main-content {
            margin-right: 250px; 
            padding: 20px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 250px;
            padding-top: 20px;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar ul.nav {
            flex-direction: column;
        }

        .sidebar .nav-link {
            color: #fff;
            text-align: center; 
        }

        .sidebar .nav-link:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="position-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="facturation.php">
                        الفواتير
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
        <div class="container mt-5">
            <h2 class="text-center">إضافة فاتورة جديدة</h2>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="form-group">
                    <label for="student_name">اسم الطالب:</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" required>
                    <div id="suggestions" class="autocomplete-suggestions"></div>
                </div>
                <input type="hidden" id="student_id" name="student_id" value="">
                <div class="form-group">
                    <label for="month">الشهر:</label>
                    <select class="form-control" id="month" name="month" required>
                        <option value="1">يناير</option>
                        <option value="2">فبراير</option>
                        <option value="3">مارس</option>
                        <option value="4">إبريل</option>
                        <option value="5">مايو</option>
                        <option value="6">يونيو</option>
                        <option value="7">يوليو</option>
                        <option value="8">أغسطس</option>
                        <option value="9">سبتمبر</option>
                        <option value="10">أكتوبر</option>
                        <option value="11">نوفمبر</option>
                        <option value="12">ديسمبر</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">السنة:</label>
                    <select class="form-control" id="year" name="year" required>
                        <?php
                        $currentYear = date("Y");
                        for ($i = $currentYear; $i <= 2040; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_amount">المبلغ الكلي:</label>
                    <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="payment_status">حالة الدفع:</label>
                    <input type="text" class="form-control" id="payment_status" name="payment_status" required>
                </div>
                <button type="submit" class="btn btn-success">إضافة</button>
            </form>
            <?php
            // Display error message, if any
            if (isset($error_message)) {
                echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
            }
            ?>
        </div>
    </main>

    <script>
        const studentNameInput = document.getElementById('student_name');
        const studentIdInput = document.getElementById('student_id');
        const suggestionsContainer = document.getElementById('suggestions');

        studentNameInput.addEventListener('input', function () {
            const inputText = this.value;
            if (inputText.length === 0) {
                suggestionsContainer.innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_student_suggestions.php?name=${inputText}`, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const suggestions = JSON.parse(xhr.responseText);

                    suggestionsContainer.innerHTML = '';
                    suggestions.forEach((suggestion) => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.className = 'autocomplete-suggestion';
                        suggestionItem.textContent = suggestion.name;
                        suggestionItem.addEventListener('click', function () {
                            studentIdInput.value = suggestion.id;
                            studentNameInput.value = suggestion.name;
                            suggestionsContainer.innerHTML = '';
                        });
                        suggestionsContainer.appendChild(suggestionItem);
                    });
                }
            };

            xhr.send();
        });
    </script>
</body>
</html>
