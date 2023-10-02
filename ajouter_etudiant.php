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

$massar_number = $first_name = $last_name = $father_name = $place_of_birth = $school_level = $boarding_point = $drop_off_point = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $massar_number = htmlspecialchars($_POST["massar_number"]);
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $father_name = htmlspecialchars($_POST["father_name"]);
    $place_of_birth = htmlspecialchars($_POST["place_of_birth"]);
    $school_level = htmlspecialchars($_POST["school_level"]);
    $boarding_point = htmlspecialchars($_POST["boarding_point"]);
    $drop_off_point = htmlspecialchars($_POST["drop_off_point"]);

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO studentinformation (MassarNumber, FirstName, LastName, FatherName, PlaceOfBirth, SchoolLevel, BoardingPoint, DropOffPoint) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssssssss", $massar_number, $first_name, $last_name, $father_name, $place_of_birth, $school_level, $boarding_point, $drop_off_point);

    if ($stmt->execute()) {
        header("location: etudiants.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طالب جديد</title>
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

        label {
            text-align: center;
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
                    <a class="nav-link" href="facturation.php">
                        الفواتير
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
        <div class="container mt-5">
            <h2 class="text-center">إضافة طالب جديد</h2>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="form-group">
                    <label for="massar_number" class="text-right">:رقم المسار</label>
                    <input type="text" class="form-control" id="massar_number" name="massar_number" required>
                </div>
                <div class="form-group">
                    <label for="first_name" class="text-right">:الاسم الأول</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name" class="text-right">:الاسم الأخير</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="father_name" class="text-right">:اسم الأب</label>
                    <input type="text" class="form-control" id="father_name" name="father_name">
                </div>
                <div class="form-group">
                    <label for="place_of_birth" class="text-right">:مكان الولادة</label>
                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth">
                </div>
                <div class="form-group">
                    <label for="school_level" class="text-right">:المستوى الدراسي</label>
                    <input type="text" class="form-control" id="school_level" name="school_level">
                </div>
                <div class="form-group">
                    <label for="boarding_point" class="text-right">:موقف الصعود</label>
                    <input type="text" class="form-control" id="boarding_point" name="boarding_point">
                </div>
                <div class="form-group">
                    <label for="drop_off_point" class="text-right">:موقف النزول</label>
                    <input type="text" class="form-control" id="drop_off_point" name="drop_off_point">
                </div>
                <button type="submit" class="btn btn-success">إضافة</button>
            </form>
            <?php
            if (isset($error_message)) {
                echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
            }
            ?>
        </div>
    </main>
</body>
</html>
