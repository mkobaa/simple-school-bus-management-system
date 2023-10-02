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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$massarNumber = $invoiceID = $firstName = $lastName = $month = $year = $totalAmount = $paymentStatus = "";

if (isset($_GET["massar_number"])) {
    $massarNumber = $_GET["massar_number"];

    $sql = "SELECT invoices.InvoiceID, studentinformation.FirstName, studentinformation.LastName, invoices.Month, invoices.Year, invoices.TotalAmount, invoices.PaymentStatus
            FROM invoices
            JOIN studentinformation ON invoices.StudentID = studentinformation.StudentID
            WHERE studentinformation.MassarNumber = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $massarNumber);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $invoiceID = $row["InvoiceID"];
                $firstName = $row["FirstName"];
                $lastName = $row["LastName"];
                $month = $row["Month"];
                $year = $row["Year"];
                $totalAmount = $row["TotalAmount"];
                $paymentStatus = $row["PaymentStatus"];
            } else {
                echo "No invoice data found for this Massar number.";
            }
        } else {
            echo "Error executing SQL query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
} else {
    echo "Massar number not provided in the URL.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الفاتورة</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
                    <a class="nav-link" href="etudiants.php">
                        الطلاب
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="facturation.php">
                        الفواتير
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
        <div class="container mt-5">
            <h2 class="text-center">تفاصيل الفاتورة</h2>
            <table class="table table-bordered">
                <tr>
                    <th class="text-center">رقم الفاتورة</th>
                    <td class="text-center"><?php echo $invoiceID; ?></td>
                </tr>
                <tr>
                    <th class="text-center">الاسم الأول</th>
                    <td class="text-center"><?php echo $firstName; ?></td>
                </tr>
                <tr>
                    <th class="text-center">الاسم الأخير</th>
                    <td class="text-center"><?php echo $lastName; ?></td>
                </tr>
                <tr>
                    <th class="text-center">الشهر</th>
                    <td class="text-center"><?php echo $month; ?></td>
                </tr>
                <tr>
                    <th class="text-center">السنة</th>
                    <td class="text-center"><?php echo $year; ?></td>
                </tr>
                <tr>
                    <th class="text-center">المبلغ الإجمالي</th>
                    <td class="text-center"><?php echo $totalAmount; ?></td>
                </tr>
                <tr>
                    <th class="text-center">حالة الدفع</th>
                    <td class="text-center"><?php echo $paymentStatus; ?></td>
                </tr>
            </table>
            
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="printInvoiceTable()">طباعة الجدول</button>
            </div>
        </div>
    </main>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="container mt-5">
        <table class="table table-bordered">
        </table>

        <div class="text-center mt-4">
            <br><br><br>
            <p>توقيع الإدارة:</p>
        </div>
    </div>
</main>



    <script>
        function printInvoiceTable() {
            var buttons = document.getElementsByClassName("btn");
            var sidebar = document.getElementById("sidebar");
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].style.display = "none";
            }
            sidebar.style.display = "none";

            window.print();

            for (var i = 0; i < buttons.length; i++) {
                buttons[i].style.display = "block";
            }
            sidebar.style.display = "block";
        }
    </script>
</body>
</html>
