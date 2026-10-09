<?php

session_start();

include "includes/db.php";

if (!isset($_GET["id"])) {
    die("Job not found.");
}

$job_id = $_GET["id"];

$sql = "SELECT * FROM jobs WHERE id = $job_id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc();


// Check whether the logged-in user already applied

$already_applied = false;

if (isset($_SESSION["user_id"])) {

    $user_id = $_SESSION["user_id"];

    $check_sql = "SELECT * FROM applications
                  WHERE user_id = $user_id
                  AND job_id = $job_id";

    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $already_applied = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($job["title"]); ?> - JobFinder
    </title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<header>

    <div class="logo">

        <h1>JobFinder</h1>

    </div>

    <nav>

        <a href="index.php">Home</a>

        <a href="jobs.php">Jobs</a>

        <a href="dashboard.php">Dashboard</a>

        <a href="logout.php">Logout</a>

    </nav>

</header>


<main>

    <div class="job-details">

        <h2>
            <?php echo htmlspecialchars($job["title"]); ?>
        </h2>


        <p>
            <strong>Company:</strong>
            <?php echo htmlspecialchars($job["company"]); ?>
        </p>


        <p>
            <strong>Location:</strong>
            <?php echo htmlspecialchars($job["location"]); ?>
        </p>


        <p>
            <strong>Salary:</strong>
            <?php echo htmlspecialchars($job["salary"]); ?>
        </p>


        <p>
            <strong>Job Type:</strong>
            <?php echo htmlspecialchars($job["job_type"]); ?>
        </p>


        <p>
            <strong>Experience:</strong>
            <?php echo htmlspecialchars($job["experience"]); ?>
        </p>


        <p>
            <strong>Skills:</strong>
            <?php echo htmlspecialchars($job["skills"]); ?>
        </p>


        <h3>Job Description</h3>

        <p>
            <?php echo nl2br(htmlspecialchars($job["description"])); ?>
        </p>


        <br>


        <?php if (!isset($_SESSION["user_id"])) { ?>

            <a href="login.php">

                <button type="button">
                    Login to Apply
                </button>

            </a>


        <?php } elseif ($already_applied) { ?>

            <p class="already-applied">
                ✅ You have already applied for this job.
            </p>


        <?php } else { ?>

            <a href="apply.php?id=<?php echo $job["id"]; ?>">

                <button type="button">
                    Apply Now
                </button>

            </a>

        <?php } ?>


        <a href="save-job.php?id=<?php echo $job["id"]; ?>">

            <button type="button">
                Save Job
            </button>

        </a>


        <a href="jobs.php">

            <button type="button">
                Back to Jobs
            </button>

        </a>

    </div>

</main>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>


</body>

</html>