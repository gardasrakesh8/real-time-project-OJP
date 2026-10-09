<?php

include "includes/db.php";

$search = "";

if (isset($_GET["search"])) {
    $search = $_GET["search"];
}

if ($search != "") {

    $sql = "SELECT * FROM jobs
            WHERE title LIKE '%$search%'
            OR company LIKE '%$search%'
            OR location LIKE '%$search%'
            OR skills LIKE '%$search%'
            ORDER BY created_at DESC";

} else {

    $sql = "SELECT * FROM jobs ORDER BY created_at DESC";

}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jobs - JobFinder</title>

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

    <h2>Find Your Next Job</h2>

    <p class="jobs-subtitle">
        Search for jobs by title, company, location or skill.
    </p>


    <!-- Search Form -->

    <form method="GET" class="job-search">

        <input
            type="text"
            name="search"
            placeholder="Search jobs..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

    </form>


    <!-- Jobs -->

    <div class="jobs-container">

        <?php

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

                    <p>
                        <strong>Experience:</strong>
                        <?php echo htmlspecialchars($job["experience"]); ?>
                    </p>

                    <p>
                        <strong>Skills:</strong>
                        <?php echo htmlspecialchars($job["skills"]); ?>
                    </p>

                    <a href="job-details.php?id=<?php echo $job["id"]; ?>">

                        <button type="button">
                            View Job
                        </button>

                    </a>

                </div>

        <?php

            }

        } else {

            echo "<p>No jobs found.</p>";

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