<?php

session_start();

include "includes/db.php";


/* Check login */

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();

}


/* Check job ID */

if (!isset($_GET["id"])) {

    die("Job not found.");

}


$user_id = $_SESSION["user_id"];

$job_id = $_GET["id"];


/* Check whether job exists */

$sql = "SELECT id FROM jobs WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $job_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    die("Job not found.");

}


/* Check whether already saved */

$check_sql = "SELECT id
              FROM saved_jobs
              WHERE user_id = ?
              AND job_id = ?";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param(
    "ii",
    $user_id,
    $job_id
);

$check_stmt->execute();

$check_result = $check_stmt->get_result();


/* Save job */

if ($check_result->num_rows == 0) {

    $insert_sql = "INSERT INTO saved_jobs
                   (user_id, job_id)
                   VALUES (?, ?)";

    $insert_stmt = $conn->prepare($insert_sql);

    $insert_stmt->bind_param(
        "ii",
        $user_id,
        $job_id
    );

    $insert_stmt->execute();

}


/* Go back to job details */

header("Location: job-details.php?id=$job_id");

exit();

?>