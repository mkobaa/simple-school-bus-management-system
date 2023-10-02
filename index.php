<?php
# Initialize the session
session_start();

# If the user is not logged in, redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام تسجيل الدخول للمستخدمين</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    .module-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
    }
    .module-circle {
      width: 100px;
      height: 100px;
      border: 3px solid #007BFF; 
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 20px;
    }
    .module-icon {
      font-size: 36px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-between mt-4">
      <div class="col-lg-6">
        <div class="d-flex align-items-center">
          <img src="./img/blank-avatar.jpg" class="img-fluid rounded" alt="صورة المستخدم" width="60">
          <h4 class="my-2 ms-3">مرحبًا، <?= htmlspecialchars($_SESSION["username"]); ?></h4>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="text-start">
          <a href="./logout.php" class="btn btn-primary">تسجيل الخروج</a>
        </div>
      </div>
    </div>

    <div class="row justify-content-center mt-4">
      <div class="col-lg-6 text-center">
        <div class="alert alert-success" id="success-alert">
          مرحبًا! أنت مسجل الدخول الآن إلى حسابك.
        </div>
      </div>
    </div>

    <div class="row justify-content-center mt-4">
      <div class="col-lg-12">
        <div class="module-container">
          <a href="./etudiants.php" class="module-circle">
            <i class="fas fa-user-graduate module-icon"></i>
          </a>
          <a href="./facturation.php" class="module-circle">
            <i class="fas fa-file-invoice module-icon"></i>
          </a>

        </div>
      </div>
    </div>
  </div>

  <script>
    setTimeout(function() {
      document.getElementById("success-alert").style.display = "none";
    }, 5000); 
  </script>
</body>

</html>
