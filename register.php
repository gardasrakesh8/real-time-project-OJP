<?php

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    // Check whether email already exists

    $check_sql = "SELECT id FROM users WHERE email = ?";

    $check_stmt = $conn->prepare($check_sql);

    $check_stmt->bind_param("s", $email);

    $check_stmt->execute();

    $check_result = $check_stmt->get_result();


    if ($check_result->num_rows > 0) {

        $message = "Email already registered!";

    } else {

        // Hash password

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        // Insert user

        $sql = "INSERT INTO users
                (name, email, password)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashed_password
        );


        if ($stmt->execute()) {

            $message = "Registration successful!";

        } else {

            $message = "Registration failed!";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - JobFinder</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<div class="register-container">

    <h2>Create Account</h2>


    <?php

    if ($message != "") {

        echo "<p class='message'>"
             . htmlspecialchars($message)
             . "</p>";

    }

    ?>


    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >


        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Create a password"
            required
        >


        <button type="submit">
            Register
        </button>

    </form>


    <p class="register-link">

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </p>

</div>


</body>

</html>