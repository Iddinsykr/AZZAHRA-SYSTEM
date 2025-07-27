<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../connection.php");

// Only allow doctor access
if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'd') {
    header("location: ../login.php");
    exit;
}

$useremail = $_SESSION["user"];

// Get doctor info
$sql = "SELECT * FROM doctor WHERE docemail=?";
$stmt = $database->prepare($sql);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$username = $doctor["docname"];
?>

<table class="menu-container" border="0">
    <tr>
        <td style="padding:10px" colspan="2">
            <table border="0" class="profile-container">
                <tr>
                    <td width="30%" style="padding-left:20px">
                        <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                    </td>
                    <td style="padding:0px;margin:0px;">
                        <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                        <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="../logout.php">
                            <input type="button" value="Log out" class="logout-btn btn-primary-soft btn">
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-dashbord">
            <a href="index.php" class="non-style-link-menu">
                <div><p class="menu-text">Dashboard</p></div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="approval.php" class="non-style-link-menu">
                <div><p class="menu-text">Approvals</p></div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment">
            <a href="appointment.php" class="non-style-link-menu">
                <div><p class="menu-text">My Appointments</p></div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-session">
            <a href="schedule.php" class="non-style-link-menu">
                <div><p class="menu-text">My Sessions</p></div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-patient">
            <a href="patient.php" class="non-style-link-menu">
                <div><p class="menu-text">My Patients</p></div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div><p class="menu-text">Settings</p></div>
            </a>
        </td>
    </tr>
</table>
