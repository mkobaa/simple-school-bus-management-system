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

$sql = "SELECT invoices.InvoiceID, studentinformation.MassarNumber, studentinformation.FirstName, studentinformation.LastName, invoices.Month, invoices.Year, invoices.TotalAmount, invoices.PaymentStatus FROM invoices JOIN studentinformation ON invoices.StudentID = studentinformation.StudentID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفواتير</title>
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
            text-align: center; /* Center-align text in the sidebar */
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
            <h2 class="text-center">الفواتير</h2>
            
            <div class="text-center mb-3">
                <a href="add_invoice.php" class="btn btn-success">إضافة فاتورة جديدة</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الفاتورة</th>
                            <th class="text-center">رقم المسار</th>
                            <th class="text-center">الاسم الأول</th>
                            <th class="text-center">الاسم الأخير</th>
                            <th class="text-center">الشهر</th>
                            <th class="text-center">السنة</th>
                            <th class="text-center">المبلغ الإجمالي</th>
                            <th class="text-center">حالة الدفع</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-center'>" . $row['InvoiceID'] . "</td>";
                                echo "<td class='text-center'><a href='facture.php?massar_number=" . $row['MassarNumber'] . "'>" . $row['MassarNumber'] . "</a></td>";
                                echo "<td class='text-center'>" . $row['FirstName'] . "</td>";
                                echo "<td class='text-center'>" . $row['LastName'] . "</td>";
                                echo "<td class='text-center'>" . $row['Month'] . "</td>";
                                echo "<td class='text-center'>" . $row['Year'] . "</td>";
                                echo "<td class='text-center'>" . $row['TotalAmount'] . "</td>";
                                echo "<td class='text-center'>" . $row['PaymentStatus'] . "</td>";
                                echo "<td class='text-center'>";
                                echo '<a href="delete_invoice.php?id=' . $row['InvoiceID'] . '" class="btn btn-danger btn-sm ml-2">حذف</a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>لا توجد بيانات للفواتير</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="printInvoiceTable()">طباعة الجدول</button>
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
