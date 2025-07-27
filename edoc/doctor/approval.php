<?php
session_start();
include("../connection.php");

// Redirect if not doctor
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit;
}

$useremail = $_SESSION["user"];

// Handle approval/rejection before any HTML
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == "approve") {
        $sql = "UPDATE appointment SET approval_status='Approved' WHERE appoid=?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $id);

        $stmt->execute();
        echo "<script>alert('Appointment Approved'); window.location='approval.php';</script>";
        exit;
    } elseif ($action == "reject") {
        $sql = "UPDATE appointment SET approval_status='Rejected' WHERE appoid=?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $id);

        $stmt->execute();
        echo "<script>alert('Appointment Rejected'); window.location='approval.php';</script>";
        exit;
    }
}

// Get doctor ID
$sql = "SELECT * FROM doctor WHERE docemail=?";
$stmt = $database->prepare($sql);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$docid = $row["docid"];
$username = $row["docname"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Approvals</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <div class="menu">
            <?php include("sidebar.php"); ?>
        </div>

        <div class="dash-body">
            <h2 style="margin: 20px;">Pending Approvals</h2>
            <table width="95%" class="sub-table scrolldown" style="margin: 20px;">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Session Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>App No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT appointment.appoid, appointment.apponum, appointment.approval_status, patient.pname, schedule.title, schedule.scheduledate, schedule.scheduletime
                            FROM appointment
                            INNER JOIN patient ON appointment.pid = patient.pid
                            INNER JOIN schedule ON appointment.scheduleid = schedule.scheduleid
                            WHERE schedule.docid = ? AND appointment.approval_status = 'Pending'
                            ORDER BY schedule.scheduledate ASC";
                    $stmt = $database->prepare($sql);
                    $stmt->bind_param("i", $docid);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        echo "<tr><td colspan='6'>No pending appointments.</td></tr>";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['pname']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['scheduledate']}</td>
                                <td>{$row['scheduletime']}</td>
                                <td>0{$row['apponum']}</td>
                                <td>
                                    <a href='approval.php?action=approve&id={$row['appoid']}'><button class='btn-primary btn'>Approve</button></a>
                                    <a href='approval.php?action=reject&id={$row['appoid']}'><button class='btn-danger btn'>Reject</button></a>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
