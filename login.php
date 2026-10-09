<?php

session_start();

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    // Find user by email

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();


        // Check password

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];

            $_SESSION["user_name"] = $user["name"];

            $_SESSION["user_role"] = $user["role"];


            // Send recruiter to recruiter dashboard

            if ($user["role"] == "recruiter") {

                header("Location: recruiter-dashboard.php");

            } else {

                header("Location: dashboard.php");

            }

            exit();

        } else {

            $message = "Incorrect password!";

        }

    } else {

        $message = "Email not found!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - JobFinder</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<div class="login-container">

    <h2>Welcome Back</h2>

    <p class="login-subtitle">
        Login to your JobFinder account
    </p>


    <?php

    if ($message != "") {

        echo "<p class='message'>"
             . htmlspecialchars($message)
             . "</p>";

    }

    ?>


    <form method="POST">

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
            placeholder="Enter your password"
            required
        >


        <button type="submit">
            Login
        </button>

    </form>


    <p class="register-link">

        Don't have an account?

        <a href="register.php">
            Create an account
        </a>

    </p>

</div>


</body>

</html>