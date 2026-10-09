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

/* Delete only the recruiter's own job */

$sql = "DELETE FROM jobs
        WHERE id = $job_id
        AND recruiter_id = $recruiter_id";

if ($conn->query($sql) === TRUE) {

    header("Location: recruiter-dashboard.php");
    exit();

} else {

    echo "Error deleting job: " . $conn->error;

}

?>