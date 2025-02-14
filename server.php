<?php
include 'db-config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $name = $conn->real_escape_string($_POST["name"]);
        $email = $conn->real_escape_string($_POST["email"]);
        $password = password_hash($conn->real_escape_string($_POST["password"]), PASSWORD_BCRYPT);

        $sql = "INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.html");
            exit;
        } else {
            echo "Error: " . htmlspecialchars($sql) . "<br>" . htmlspecialchars($conn->error);
        }
    } elseif (isset($_POST['login'])) {
        $email = $conn->real_escape_string($_POST["email"]);
        $password = $_POST["password"];

        $sql = "SELECT password FROM students WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                echo "Login successful";
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No user found with that email";
        }
    }
    $conn->close();
}
?>