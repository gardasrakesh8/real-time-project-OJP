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
    die("Application not found.");
}

$application_id = $_GET["id"];
$recruiter_id = $_SESSION["user_id"];

$status = $_POST["status"];


/* Update only applications for recruiter's jobs */

$sql = "UPDATE applications
        JOIN jobs ON applications.job_id = jobs.id
        SET applications.status = '$status'
        WHERE applications.id = $application_id
        AND jobs.recruiter_id = $recruiter_id";

if ($conn->query($sql) === TRUE) {

    header("Location: recruiter-applications.php");
    exit();

} else {

    echo "Error: " . $conn->error;

}

?>