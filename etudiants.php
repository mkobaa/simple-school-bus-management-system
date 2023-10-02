<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلومات الطلاب</title>
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

        table {
            table-layout: fixed;
            width: 100%;
        }

        th, td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            <h2 class="text-center">معلومات الطلاب</h2>
            
            <div class="text-center mb-3">
                <button class="btn btn-success" onclick="window.location.href='ajouter_etudiant.php'">إضافة طالب</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">رقم المسار</th>
                            <th class="text-center">الاسم الأول</th>
                            <th class="text-center">الاسم الأخير</th>
                            <th class="text-center">اسم الأب</th>
                            <th class="text-center">مكان الولادة</th>
                            <th class="text-center">المستوى الدراسي</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "nour";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM StudentInformation";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-center'>" . $row['MassarNumber'] . "</td>";
                                echo "<td class='text-center'><a href='etudiant.php?studentID=" . $row['StudentID'] . "'>" . $row['FirstName'] . "</a></td>";
                                echo "<td class='text-center'><a href='etudiant.php?studentID=" . $row['StudentID'] . "'>" . $row['LastName'] . "</a></td>";
                                echo "<td class='text-center'>" . $row['FatherName'] . "</td>";
                                echo "<td class='text-center'>" . $row['PlaceOfBirth'] . "</td>";
                                echo "<td class='text-center'>" . $row['SchoolLevel'] . "</td>";
                                echo "<td class='text-center'>";
                                echo '<a href="#" onclick="confirmDelete(' . $row['StudentID'] . ')" class="btn btn-danger btn-sm ml-2">حذف</a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>لا توجد بيانات للطلاب</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="printStudentTable()">طباعة الجدول</button>
            </div>
        </div>
    </main>

    <script>
        function confirmDelete(studentID) {
            if (confirm("هل أنت متأكد من رغبتك في حذف هذا الطالب؟")) {
                window.location.href = "delete.php?id=" + studentID;
            }
        }

        function printStudentTable() {
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
