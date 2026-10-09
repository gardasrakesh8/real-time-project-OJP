<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$message = "";


// Get current user

$sql = "SELECT * FROM users WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


// Update profile

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);


    // Check whether another account already uses this email

    $check_sql = "SELECT id
                  FROM users
                  WHERE email = ?
                  AND id != ?";

    $check_stmt = $conn->prepare($check_sql);

    $check_stmt->bind_param(
        "si",
        $email,
        $user_id
    );

    $check_stmt->execute();

    $check_result = $check_stmt->get_result();


    if ($check_result->num_rows > 0) {

        $message = "This email is already used by another account.";

    } else {

        $update_sql = "UPDATE users
                       SET name = ?,
                           email = ?
                       WHERE id = ?";

        $update_stmt = $conn->prepare($update_sql);

        $update_stmt->bind_param(
            "ssi",
            $name,
            $email,
            $user_id
        );


        if ($update_stmt->execute()) {

            $_SESSION["user_name"] = $name;

            $message = "Profile updated successfully!";


            // Get updated user information

            $sql = "SELECT * FROM users WHERE id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("i", $user_id);

            $stmt->execute();

            $result = $stmt->get_result();

            $user = $result->fetch_assoc();

        } else {

            $message = "Error updating profile.";

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

    <title>My Profile - JobFinder</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<header>

    <div class="logo">

        <h1>JobFinder</h1>

    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="jobs.php">
            Jobs
        </a>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </nav>

</header>


<main>

    <div class="profile-container">

        <h2>My Profile</h2>


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
                value="<?php echo htmlspecialchars($user["name"]); ?>"
                required
            >


            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($user["email"]); ?>"
                required
            >


            <label>Account Type</label>

            <input
                type="text"
                value="<?php echo htmlspecialchars($user["role"]); ?>"
                readonly
            >


            <button type="submit">
                Update Profile
            </button>

        </form>

    </div>

</main>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>


</body>

</html>