<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["user_role"] != "recruiter") {
    die("Access denied.");
}

if (!isset($_GET["id"])) {
    die("Job not found.");
}

$job_id = $_GET["id"];
$recruiter_id = $_SESSION["user_id"];

$message = "";


/* Get job */

$sql = "SELECT * FROM jobs
        WHERE id = ?
        AND recruiter_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $job_id,
    $recruiter_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {
    die("Job not found or you do not have permission to edit it.");
}

$job = $result->fetch_assoc();


/* Update job */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $company = trim($_POST["company"]);
    $location = trim($_POST["location"]);
    $salary = trim($_POST["salary"]);
    $job_type = trim($_POST["job_type"]);
    $experience = trim($_POST["experience"]);
    $skills = trim($_POST["skills"]);
    $description = trim($_POST["description"]);


    $update_sql = "UPDATE jobs SET

                   title = ?,
                   company = ?,
                   location = ?,
                   salary = ?,
                   job_type = ?,
                   experience = ?,
                   skills = ?,
                   description = ?

                   WHERE id = ?
                   AND recruiter_id = ?";


    $update_stmt = $conn->prepare($update_sql);


    $update_stmt->bind_param(
        "ssssssssii",
        $title,
        $company,
        $location,
        $salary,
        $job_type,
        $experience,
        $skills,
        $description,
        $job_id,
        $recruiter_id
    );


    if ($update_stmt->execute()) {

        $message = "Job updated successfully!";


        /* Get updated job */

        $sql = "SELECT * FROM jobs
                WHERE id = ?
                AND recruiter_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ii",
            $job_id,
            $recruiter_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $job = $result->fetch_assoc();

    } else {

        $message = "Error updating job.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Job - JobFinder</title>

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

        <a href="recruiter-dashboard.php">
            Recruiter Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </nav>

</header>


<main>

    <div class="add-job-container">

        <h2>Edit Job</h2>


        <?php

        if ($message != "") {

            echo "<p class='message'>"
                 . htmlspecialchars($message)
                 . "</p>";

        }

        ?>


        <form method="POST">


            <label>Job Title</label>

            <input
                type="text"
                name="title"
                value="<?php echo htmlspecialchars($job["title"]); ?>"
                required
            >


            <label>Company</label>

            <input
                type="text"
                name="company"
                value="<?php echo htmlspecialchars($job["company"]); ?>"
                required
            >


            <label>Location</label>

            <input
                type="text"
                name="location"
                value="<?php echo htmlspecialchars($job["location"]); ?>"
                required
            >


            <label>Salary</label>

            <input
                type="text"
                name="salary"
                value="<?php echo htmlspecialchars($job["salary"]); ?>"
            >


            <label>Job Type</label>

            <select name="job_type">

                <option value="Full Time"
                    <?php
                    if ($job["job_type"] == "Full Time") {
                        echo "selected";
                    }
                    ?>>
                    Full Time
                </option>

                <option value="Part Time"
                    <?php
                    if ($job["job_type"] == "Part Time") {
                        echo "selected";
                    }
                    ?>>
                    Part Time
                </option>

                <option value="Internship"
                    <?php
                    if ($job["job_type"] == "Internship") {
                        echo "selected";
                    }
                    ?>>
                    Internship
                </option>

            </select>


            <label>Experience</label>

            <input
                type="text"
                name="experience"
                value="<?php echo htmlspecialchars($job["experience"]); ?>"
            >


            <label>Skills</label>

            <input
                type="text"
                name="skills"
                value="<?php echo htmlspecialchars($job["skills"]); ?>"
            >


            <label>Job Description</label>

            <textarea
                name="description"
                rows="8"
                required
            ><?php echo htmlspecialchars($job["description"]); ?></textarea>


            <button type="submit">
                Update Job
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