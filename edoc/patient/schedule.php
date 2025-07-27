<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
<?php
session_start();
if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

include("../connection.php");
$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

date_default_timezone_set('Asia/Kuala_Lumpur');
$today = date('Y-m-d');

$insertkey = "";
$q = '';
$searchtype = "All";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["search"])) {
    $keyword = trim($_POST["search"]);
    $keyword_escaped = $database->real_escape_string($keyword);
    $is_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $keyword);
    $sqlmain = "SELECT * FROM schedule 
                INNER JOIN doctor ON schedule.docid = doctor.docid 
                WHERE schedule.scheduledate >= '$today' AND (
                    doctor.docname LIKE '%$keyword_escaped%' OR 
                    schedule.title LIKE '%$keyword_escaped%'";
    if ($is_date) {
        $sqlmain .= " OR schedule.scheduledate LIKE '%$keyword_escaped%'";
    }
    $sqlmain .= ") ORDER BY schedule.scheduledate ASC";
    $insertkey = $keyword;
    $searchtype = "Search Result : ";
    $q = '"';
} else {
    $sqlmain = "SELECT * FROM schedule 
                INNER JOIN doctor ON schedule.docid = doctor.docid 
                WHERE schedule.scheduledate >= '$today' 
                ORDER BY schedule.scheduledate ASC";
}

$result = $database->query($sqlmain);
?>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td colspan="2">
                    <table class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px">
                                <img src="../img/user.png" width="100%" style="border-radius:50%">
                            </td>
                            <td>
                                <p class="profile-title"><?php echo substr($username,0,13) ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail,0,22) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-home">
                    <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                    <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="dash-body">
        <table width="100%">
            <tr>
                <td width="13%">
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                </td>
                <td>
                    <form action="" method="post" class="header-search">
                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Title or Date (Y-m-d)" value="<?php echo $insertkey ?>">&nbsp;&nbsp;
                        <input type="submit" value="Search" class="login-btn btn-primary btn">
                    </form>
                </td>
                <td width="15%">
                    <p class="heading-sub12" style="text-align: right;">Today's Date</p>
                    <p class="heading-sub12" style="text-align: right;"><?php echo $today; ?></p>
                </td>
                <td width="10%">
                    <button class="btn-label"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px">
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49,49,49)"><?php echo $searchtype . " Sessions (" . $result->num_rows . ")"; ?></p>
                    <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49,49,49)"><?php echo $q . $insertkey . $q; ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown">
                                <tbody>
                                    <?php
                                    if ($result->num_rows == 0) {
                                        echo '<tr><td colspan="3"><center><img src="../img/notfound.svg" width="25%"><br>No sessions found.</center></td></tr>';
                                    } else {
                                        for ($x = 0; $x < ($result->num_rows); $x++) {
                                            echo "<tr>";
                                            for ($q = 0; $q < 3; $q++) {
                                                $row = $result->fetch_assoc();
                                                if (!isset($row)) {
                                                    break;
                                                }
                                                $scheduleid = $row["scheduleid"];
$title = $row["title"];
$docname = $row["docname"];
$scheduledate = $row["scheduledate"];
$scheduletime = $row["scheduletime"];

if ($scheduleid == "") {
    break;
}

// Check if session is full
$bookingQuery = $database->query("SELECT COUNT(*) AS booked FROM appointment WHERE scheduleid = $scheduleid");
$booked = $bookingQuery->fetch_assoc()['booked'];

$maxQuery = $database->query("SELECT nop FROM schedule WHERE scheduleid = $scheduleid");
$maxNop = $maxQuery->fetch_assoc()['nop'];

$isFull = ($booked >= $maxNop);

echo '
<td style="width: 25%;">
    <div class="dashboard-items search-items">
        <div style="width:100%">
            <div class="h1-search">' . substr($title, 0, 21) . '</div><br>
            <div class="h3-search">' . substr($docname, 0, 30) . '</div>
            <div class="h4-search">' . substr($scheduledate, 0, 10) . '<br>Starts: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)</div>
            <br>';
            
if ($isFull) {
    echo '<button class="login-btn btn-disabled" style="width:100%;background-color:#ccc;cursor: not-allowed;" disabled>Session Full</button>';
} else {
    echo '<a href="booking.php?id=' . $scheduleid . '" class="non-style-link">
            <button class="login-btn btn-primary-soft btn" style="width:100%">Book Now</button>
          </a>';
}

echo '
        </div>
    </div>
</td>';

                                            }
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
