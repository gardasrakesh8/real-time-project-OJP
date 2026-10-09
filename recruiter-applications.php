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

$recruiter_id = $_SESSION["user_id"];


/* Get applications for recruiter's jobs */

$sql = "SELECT applications.*,
               users.name,
               users.email,
               jobs.title,
               jobs.company
        FROM applications
        JOIN users
            ON applications.user_id = users.id
        JOIN jobs
            ON applications.job_id = jobs.id
        WHERE jobs.recruiter_id = $recruiter_id
        ORDER BY applications.applied_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Applications - JobFinder</title>

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

        <a href="logout.php">Logout</a>

    </nav>

</header>


<main>

    <h2>Job Applications</h2>

    <p class="jobs-subtitle">
        View applications submitted for your jobs.
    </p>


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
                        <strong>Applicant:</strong>
                        <?php echo htmlspecialchars($application["name"]); ?>
                    </p>

                    <p>
                        <strong>Email:</strong>
                        <?php echo htmlspecialchars($application["email"]); ?>
                    </p>

                    <p>
                        <strong>Company:</strong>
                        <?php echo htmlspecialchars($application["company"]); ?>
                    </p>

                   <p>
    <strong>Status:</strong>
    <?php echo htmlspecialchars($application["status"]); ?>
</p>

<form method="POST"
      action="update-application-status.php?id=<?php echo $application["id"]; ?>">

    <select name="status">

        <option value="Pending"
            <?php if ($application["status"] == "Pending") echo "selected"; ?>>
            Pending
        </option>

        <option value="Shortlisted"
            <?php if ($application["status"] == "Shortlisted") echo "selected"; ?>>
            Shortlisted
        </option>

        <option value="Selected"
            <?php if ($application["status"] == "Selected") echo "selected"; ?>>
            Selected
        </option>

        <option value="Rejected"
            <?php if ($application["status"] == "Rejected") echo "selected"; ?>>
            Rejected
        </option>

    </select>

    <button type="submit">
        Update Status
    </button>

</form>

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

            echo "<p>No applications received yet.</p>";

        }

        ?>

    </div>

</main>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>

</body>

</html>