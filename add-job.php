<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["user_role"] != "recruiter") {
    die("Access denied. Only recruiters can post jobs.");
}

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $company = trim($_POST["company"]);
    $location = trim($_POST["location"]);
    $salary = trim($_POST["salary"]);
    $job_type = trim($_POST["job_type"]);
    $experience = trim($_POST["experience"]);
    $skills = trim($_POST["skills"]);
    $description = trim($_POST["description"]);

    $recruiter_id = $_SESSION["user_id"];


    $sql = "INSERT INTO jobs
            (
                title,
                company,
                location,
                salary,
                job_type,
                experience,
                skills,
                description,
                recruiter_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "ssssssssi",
        $title,
        $company,
        $location,
        $salary,
        $job_type,
        $experience,
        $skills,
        $description,
        $recruiter_id
    );


    if ($stmt->execute()) {

        $message = "Job added successfully!";

    } else {

        $message = "Error: " . $conn->error;

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Job - JobFinder</title>

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

        <h2>Post a New Job</h2>


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
                placeholder="Example: Python Developer"
                required
            >


            <label>Company</label>

            <input
                type="text"
                name="company"
                placeholder="Example: TCS"
                required
            >


            <label>Location</label>

            <input
                type="text"
                name="location"
                placeholder="Example: Hyderabad"
                required
            >


            <label>Salary</label>

            <input
                type="text"
                name="salary"
                placeholder="Example: ₹4 - ₹7 LPA"
            >


            <label>Job Type</label>

            <select name="job_type">

                <option value="Full Time">
                    Full Time
                </option>

                <option value="Part Time">
                    Part Time
                </option>

                <option value="Internship">
                    Internship
                </option>

            </select>


            <label>Experience</label>

            <input
                type="text"
                name="experience"
                placeholder="Example: 0-2 Years"
            >


            <label>Skills</label>

            <input
                type="text"
                name="skills"
                placeholder="Example: Python, SQL, Django"
            >


            <label>Job Description</label>

            <textarea
                name="description"
                rows="8"
                placeholder="Enter job description..."
                required
            ></textarea>


            <button type="submit">
                Post Job
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