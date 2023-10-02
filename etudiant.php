<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

if (!isset($_GET["studentID"])) {
    echo "Student ID is missing in the URL.";
    exit;
}

$studentID = $_GET["studentID"];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nour";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentSql = "SELECT * FROM studentinformation WHERE StudentID = $studentID";
$studentResult = $conn->query($studentSql);

$billingSql = "SELECT * FROM billing WHERE StudentID = $studentID";
$billingResult = $conn->query($billingSql);

$boardingPoint = "";
$dropOffPoint = "";

if ($studentResult->num_rows > 0) {
    $studentData = $studentResult->fetch_assoc();
    $boardingPoint = isset($studentData['BoardingPoint']) ? $studentData['BoardingPoint'] : "";
    $dropOffPoint = isset($studentData['DropOffPoint']) ? $studentData['DropOffPoint'] : "";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلومات الطالب</title>
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
                    <a class="nav-link active" href="etudiants.php">
                        الطلاب
                    </a>
                </li>

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
            <h2 class="text-center">معلومات الطالب</h2>
            <?php
            if (!empty($studentData)) {
                echo "<h3 class='text-center'>" . $studentData['FirstName'] . " " . $studentData['LastName'] . "</h3>";
                echo "<div class='text-center mb-3'>";
                echo "<p><strong>مكان الصعود: </strong>" . $boardingPoint . "</p>";
                echo "<p><strong>مكان النزول: </strong>" . $dropOffPoint . "</p>";
                echo "</div>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th class='text-center'>الشهر</th>";
                echo "<th class='text-center'>السنة</th>";
                echo "<th class='text-center'>المبلغ المدفوع</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if ($billingResult->num_rows > 0) {
                    while ($row = $billingResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='text-center'>" . $row['Month'] . "</td>";
                        echo "<td class='text-center'>" . $row['Year'] . "</td>";
                        echo "<td class='text-center'>" . $row['TotalAmount'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>لم يتم العثور على معلومات الفواتير لهذا الطالب.</td></tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='text-center'>لم يتم العثور على معلومات الطالب.</div>";
            }
            ?>
        </div>
    </main>
</body>
</html>
