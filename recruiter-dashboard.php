<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["user_role"] != "recruiter") {
    die("Access denied. Only recruiters can access this page.");
}

$recruiter_id = $_SESSION["user_id"];


/* Total Jobs */

$sql = "SELECT COUNT(*) AS total_jobs
        FROM jobs
        WHERE recruiter_id = $recruiter_id";

$result = $conn->query($sql);
$total_jobs = $result->fetch_assoc()["total_jobs"];


/* Total Applications */

$sql = "SELECT COUNT(*) AS total_applications
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id";

$result = $conn->query($sql);
$total_applications = $result->fetch_assoc()["total_applications"];


/* Pending Applications */

$sql = "SELECT COUNT(*) AS pending
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id
        AND applications.status = 'Pending'";

$result = $conn->query($sql);
$pending = $result->fetch_assoc()["pending"];


/* Shortlisted Applications */

$sql = "SELECT COUNT(*) AS shortlisted
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id
        AND applications.status = 'Shortlisted'";

$result = $conn->query($sql);
$shortlisted = $result->fetch_assoc()["shortlisted"];


/* Selected Applications */

$sql = "SELECT COUNT(*) AS selected
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id
        AND applications.status = 'Selected'";

$result = $conn->query($sql);
$selected = $result->fetch_assoc()["selected"];


/* Rejected Applications */

$sql = "SELECT COUNT(*) AS rejected
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id
        AND applications.status = 'Rejected'";

$result = $conn->query($sql);
$rejected = $result->fetch_assoc()["rejected"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recruiter Dashboard - JobFinder</title>

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

        <a href="recruiter-dashboard.php">
            Recruiter Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </nav>

</header>


<main>

    <div class="recruiter-dashboard">

        <h2>Recruiter Dashboard</h2>

        <p>
            Manage your jobs and applications from here.
        </p>


        <br>


        <!-- Dashboard Statistics -->

        <div class="dashboard-cards">


            <div class="dashboard-card">

                <h3>💼 Total Jobs</h3>

                <p class="stat-number">
                    <?php echo $total_jobs; ?>
                </p>

            </div>


            <div class="dashboard-card">

                <h3>📄 Applications</h3>

                <p class="stat-number">
                    <?php echo $total_applications; ?>
                </p>

            </div>


            <div class="dashboard-card">

                <h3>⏳ Pending</h3>

                <p class="stat-number">
                    <?php echo $pending; ?>
                </p>

            </div>


            <div class="dashboard-card">

                <h3>🔍 Shortlisted</h3>

                <p class="stat-number">
                    <?php echo $shortlisted; ?>
                </p>

            </div>


            <div class="dashboard-card">

                <h3>🎉 Selected</h3>

                <p class="stat-number">
                    <?php echo $selected; ?>
                </p>

            </div>


            <div class="dashboard-card">

                <h3>❌ Rejected</h3>

                <p class="stat-number">
                    <?php echo $rejected; ?>
                </p>

            </div>


        </div>


        <br>


        <!-- Dashboard Buttons -->

        <a href="add-job.php">

            <button type="button">
                + Post New Job
            </button>

        </a>


        <a href="recruiter-applications.php">

            <button type="button">
                View Applications
            </button>

        </a>


        <h3 class="posted-jobs-title">
            Your Job Postings
        </h3>


        <div class="jobs-container">


        <?php

        $sql = "SELECT * FROM jobs
                WHERE recruiter_id = $recruiter_id
                ORDER BY created_at DESC";

        $result = $conn->query($sql);


        if ($result->num_rows > 0) {

            while ($job = $result->fetch_assoc()) {

        ?>

                <div class="job-card">

                    <h3>
                        <?php echo htmlspecialchars($job["title"]); ?>
                    </h3>

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


                    <a href="job-details.php?id=<?php echo $job["id"]; ?>">

                        <button type="button">
                            View Job
                        </button>

                    </a>


                    <a href="edit-job.php?id=<?php echo $job["id"]; ?>">

                        <button type="button">
                            Edit Job
                        </button>

                    </a>


                    <a href="delete-job.php?id=<?php echo $job["id"]; ?>"
                       onclick="return confirm('Are you sure you want to delete this job?');">

                        <button type="button">
                            Delete Job
                        </button>

                    </a>

                </div>

        <?php

            }

        } else {

            echo "<p>No jobs posted yet.</p>";

        }

        ?>

        </div>

    </div>

</main>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>


</body>

</html>