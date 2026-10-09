<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("Saved job not found.");
}

$user_id = $_SESSION["user_id"];
$job_id = $_GET["id"];

$sql = "DELETE FROM saved_jobs
        WHERE user_id = $user_id
        AND job_id = $job_id";

if ($conn->query($sql) === TRUE) {

    header("Location: saved-jobs.php");
    exit();

} else {

    echo "Error: " . $conn->error;

}

?>