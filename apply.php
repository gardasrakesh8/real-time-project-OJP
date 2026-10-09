<?php

session_start();

include "includes/db.php";


/* Check login */

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();

}


/* Check job ID */

if (!isset($_GET["id"])) {

    die("Job not found.");

}


$job_id = $_GET["id"];

$user_id = $_SESSION["user_id"];

$message = "";


/* Check whether job exists */

$sql = "SELECT * FROM jobs WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $job_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    die("Job not found.");

}


$job = $result->fetch_assoc();


/* Submit application */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cover_letter = trim($_POST["cover_letter"]);


    /* Check duplicate application */

    $check_sql = "SELECT id
                  FROM applications
                  WHERE user_id = ?
                  AND job_id = ?";

    $check_stmt = $conn->prepare($check_sql);

    $check_stmt->bind_param(
        "ii",
        $user_id,
        $job_id
    );

    $check_stmt->execute();

    $check_result = $check_stmt->get_result();


    if ($check_result->num_rows > 0) {

        $message = "You have already applied for this job!";

    } else {


        /* Insert application */

        $sql = "INSERT INTO applications
                (
                    user_id,
                    job_id,
                    cover_letter
                )
                VALUES (?, ?, ?)";


        $stmt = $conn->prepare($sql);


        $stmt->bind_param(
            "iis",
            $user_id,
            $job_id,
            $cover_letter
        );


        if ($stmt->execute()) {

            $message = "Application submitted successfully!";

        } else {

            $message = "Error submitting application.";

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

    <title>Apply for Job - JobFinder</title>

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

    <div class="apply-container">

        <h2>
            Apply for
            <?php echo htmlspecialchars($job["title"]); ?>
        </h2>


        <p>

            <strong>Company:</strong>

            <?php echo htmlspecialchars($job["company"]); ?>

        </p>


        <br>


        <?php

        if ($message != "") {

            echo "<p class='message'>"
                 . htmlspecialchars($message)
                 . "</p>";

        }

        ?>


        <?php if ($message != "Application submitted successfully!") { ?>

        <form method="POST">


            <label>
                Cover Letter
            </label>


            <textarea
                name="cover_letter"
                rows="8"
                placeholder="Write your cover letter..."
                required
            ></textarea>


            <br>
            <br>


            <button type="submit">
                Submit Application
            </button>


        </form>

        <?php } else { ?>

            <a href="my-applications.php">

                <button type="button">
                    View My Applications
                </button>

            </a>

        <?php } ?>


    </div>

</main>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>


</body>

</html>