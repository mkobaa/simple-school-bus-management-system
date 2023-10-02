<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nour"; 

$inputText = isset($_GET["name"]) ? $_GET["name"] : "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT StudentID, CONCAT(FirstName, ' ', LastName) AS Name FROM studentinformation WHERE CONCAT(FirstName, ' ', LastName) LIKE ? LIMIT 10";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $inputText = '%' . $inputText . '%';
    $stmt->bind_param("s", $inputText);

    $stmt->execute();

    $result = $stmt->get_result();

    $suggestions = [];

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['StudentID'],
            'name' => $row['Name']
        ];
    }

    $stmt->close();

    echo json_encode($suggestions);
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
