<?php

session_start();
include '../server.php'; // Include your database connection file

// START: Handle Form Submission
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Sanitize & get form values
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "Email already registered.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO admins (user_id, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $msg = "✅ Registration successful!";
            } else {
                $msg = "❌ Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 50px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
        }

        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type=submit] {
            background: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        .msg {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <form method="POST" action="">
        <h2>Register</h2>
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="submit" value="Register" />
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    </form>

</body>

</html>