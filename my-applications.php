<?php
session_start();

include "includes/db.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT applications.*, jobs.title, jobs.company, jobs.location
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE applications.user_id = $user_id
        ORDER BY applications.applied_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Applications - JobFinder</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <h1>JobFinder</h1>

    <nav>
        <a href="index.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

</header>

<main>

    <h2>My Applications</h2>

    <div class="applications-container">

        <?php

        if ($result->num_rows > 0) {

            while ($application = $result->fetch_assoc()) {

        ?>

            <div class="application-card">

                <h3>
                    <?php echo htmlspecialchars($application["title"]); ?>
                </h3>

                <p>
                    <strong>Company:</strong>
                    <?php echo htmlspecialchars($application["company"]); ?>
                </p>

                <p>
                    <strong>Location:</strong>
                    <?php echo htmlspecialchars($application["location"]); ?>
                </p>

               <p>
    <strong>Status:</strong>

    <?php if ($application["status"] == "Pending") { ?>

        <span class="status pending">Pending</span>

    <?php } elseif ($application["status"] == "Shortlisted") { ?>

        <span class="status shortlisted">Shortlisted</span>

    <?php } elseif ($application["status"] == "Selected") { ?>

        <span class="status selected">Selected 🎉</span>

    <?php } elseif ($application["status"] == "Rejected") { ?>

        <span class="status rejected">Rejected</span>

    <?php } ?>

</p>

<p>
    <strong>Applied On:</strong>
    <?php echo htmlspecialchars($application["applied_at"]); ?>
</p>

<p>
    <strong>Cover Letter:</strong>
</p>

<p>
    <?php echo nl2br(htmlspecialchars($application["cover_letter"])); ?>
</p>

            </div>

        <?php

            }

        } else {

            echo "<p>You have not applied for any jobs yet.</p>";

        }

        ?>

    </div>

</main>

</body>

</html>