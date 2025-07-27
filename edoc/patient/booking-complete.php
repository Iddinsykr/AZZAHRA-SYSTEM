<?php
// Start session
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") || ($_SESSION['usertype'] != 'p')) {
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

// Import database connection
include("../connection.php");

// Fetch user info
$sqlmain = "SELECT * FROM patient WHERE pemail = ?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

if ($_POST) {
    if (isset($_POST["booknow"])) {
        $apponum = $_POST["apponum"];
        $scheduleid = $_POST["scheduleid"];
        $date = $_POST["date"];

        // Check how many appointments already exist for this session
        $checkCount = $database->query("SELECT COUNT(*) AS current FROM appointment WHERE scheduleid = $scheduleid");
        $current = $checkCount->fetch_assoc()['current'];

        // Get the allowed number of patients for this session
        $maxQuery = $database->query("SELECT nop FROM schedule WHERE scheduleid = $scheduleid");
        $maxNop = $maxQuery->fetch_assoc()['nop'];

        // If session is full, stop and notify
        if ($current >= $maxNop) {
            echo "<script>
                alert('Sorry, the session is full.');
                window.location.href = 'schedule.php';
            </script>";
            exit();
        }

        // Insert appointment
        $sql2 = "INSERT INTO appointment(pid, apponum, scheduleid, appodate) 
                 VALUES ($userid, $apponum, $scheduleid, '$date')";
        $result = $database->query($sql2);

        // Optional: send email notification
        $subject = "Appointment Confirmation";
        $message = "Dear $username,\n\nYour appointment (No. $apponum) is confirmed for $date.\n\nRegards,\neDoc Team";
        mail($useremail, $subject, $message); // Make sure mail() is configured on your server

        // Redirect to confirmation
        header("Location: appointment.php?action=booking-added&id=$apponum");
        exit();
    }
}
?>
