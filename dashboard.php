<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
$user_role = $_SESSION["user_role"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - JobFinder</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <div class="logo">
        <h1>JobFinder</h1>
    </div>

   

</header>


<main>

    <div class="dashboard-container">

        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
<nav>

<a href="index.php">Home</a>

<a href="jobs.php">Jobs</a>

<?php if ($user_role == "recruiter") { ?>

    <a href="recruiter-dashboard.php">
        Recruiter Dashboard
    </a>

<?php } else { ?>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="my-applications.php">
        My Applications
    </a>

    <a href="saved-jobs.php">
        Saved Jobs
    </a>

    <a href="profile.php">
        Profile
    </a>

<?php } ?>

<a href="logout.php">
    Logout
</a>

</nav>
        <p class="dashboard-subtitle">
            Welcome to your JobFinder dashboard.
        </p>


        <div class="dashboard-cards">

            <div class="dashboard-card">

                <h3>🔍 Browse Jobs</h3>

                <p>
                    Find jobs based on your skills,
                    experience and location.
                </p>

                <a href="jobs.php">
                    <button>View Jobs</button>
                </a>

            </div>


            <div class="dashboard-card">

                <h3>📝 My Applications</h3>

                <p>
                    View the jobs you have applied for
                    and check application status.
                </p>

                <a href="my-applications.php">
                    <button>View Applications</button>
                </a>

            </div>
            <div class="dashboard-card">

    <h3>🔖 Saved Jobs</h3>

    <p>
        View the jobs you saved and
        check them whenever you want.
    </p>

    <a href="saved-jobs.php">
        <button>View Saved Jobs</button>
    </a>

</div>


            <div class="dashboard-card">

                <h3>👤 My Profile</h3>

                <p>
                    Account Type:
                    <strong>
                        <?php echo htmlspecialchars($user_role); ?>
                    </strong>
                </p>
<a href="profile.php">
    <button type="button">Profile</button>
</a>
            </div>

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