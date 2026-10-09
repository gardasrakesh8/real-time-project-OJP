<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JobFinder - Find Your Dream Job</title>

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

        <?php if (isset($_SESSION["user_id"])) { ?>

            <a href="dashboard.php">Dashboard</a>

            <a href="logout.php">Logout</a>

        <?php } else { ?>

            <a href="login.php">Login</a>

            <a href="register.php">Register</a>

        <?php } ?>

    </nav>

</header>


<section class="hero">

    <div class="hero-content">

        <h2>Find Your Dream Job</h2>

        <p>
            Discover the right opportunity and take the next step
            in your career.
        </p>

        <a href="jobs.php">
            <button>Explore Jobs</button>
        </a>

    </div>

</section>


<section class="features">

    <h2>Why Choose JobFinder?</h2>

    <div class="feature-container">

        <div class="feature-card">

            <h3>🔍 Find Jobs</h3>

            <p>
                Search for jobs based on your skills,
                location and experience.
            </p>

        </div>


        <div class="feature-card">

            <h3>📝 Easy Application</h3>

            <p>
                Apply for jobs quickly and manage
                your applications in one place.
            </p>

        </div>


        <div class="feature-card">

            <h3>🚀 Build Your Career</h3>

            <p>
                Find opportunities that help you
                grow professionally.
            </p>

        </div>

    </div>

</section>


<footer>

    <p>
        © 2026 JobFinder. All rights reserved.
    </p>

</footer>

</body>

</html>