<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

date_default_timezone_set('Asia/Kuala_Lumpur');
$today = date("Y-m-d H:i:s");

// Initialize form-related variables
$reportType = $_POST['report_type'] ?? '';
$selectedPatientId = $_POST['patient_id'] ?? '';
$selectedDoctorId = $_POST['doctor_id'] ?? '';
$patientInfo = null;
$appointments = null;
$doctorInfo = null;
$sessions = null;
$doctorPatients = null;
$allPatients = null;
$allDoctors = null;
$allSessions = null;

// Load dropdown data
$patients = $database->query("SELECT pid, pname FROM patient");
$doctors = $database->query("SELECT docid, docname FROM doctor");

// Summary counts
$doctorRes = $database->query("SELECT * FROM doctor");
$patientRes = $database->query("SELECT * FROM patient");
$appointmentRes = $database->query("SELECT * FROM appointment");
$scheduleRes = $database->query("SELECT * FROM schedule");

// Patient report logic
if ($reportType == 'patient' && $selectedPatientId) {
    $patientInfo = $database->query("SELECT * FROM patient WHERE pid='$selectedPatientId'")->fetch_assoc();
    $appointments = $database->query("SELECT appointment.apponum, doctor.docname, schedule.title, appointment.appodate, schedule.scheduletime 
        FROM appointment 
        INNER JOIN schedule ON appointment.scheduleid = schedule.scheduleid 
        INNER JOIN doctor ON schedule.docid = doctor.docid 
        WHERE appointment.pid='$selectedPatientId'");
}

// Doctor report logic
if ($reportType == 'doctor' && $selectedDoctorId) {
    $doctorInfo = $database->query("SELECT * FROM doctor WHERE docid='$selectedDoctorId'")->fetch_assoc();

    $sessions = $database->query("SELECT schedule.title, schedule.scheduledate, schedule.scheduletime, COUNT(appointment.appoid) AS total_appointments
        FROM schedule 
        LEFT JOIN appointment ON schedule.scheduleid = appointment.scheduleid
        WHERE schedule.docid='$selectedDoctorId'
        GROUP BY schedule.scheduleid");

    $doctorPatients = $database->query("SELECT DISTINCT patient.pname, patient.pemail, patient.pnic, patient.ptel, schedule.title
        FROM appointment
        INNER JOIN patient ON appointment.pid = patient.pid
        INNER JOIN schedule ON appointment.scheduleid = schedule.scheduleid
        WHERE schedule.docid = '$selectedDoctorId'");
}

// Overall report logic
if ($reportType == 'overall') {
    $allPatients = $database->query("SELECT * FROM patient");
    $allDoctors = $database->query("SELECT * FROM doctor");
    $allSessions = $database->query("SELECT schedule.title, schedule.scheduledate, schedule.scheduletime, doctor.docname, COUNT(appointment.appoid) AS total_appointments
        FROM schedule
        LEFT JOIN doctor ON schedule.docid = doctor.docid
        LEFT JOIN appointment ON schedule.scheduleid = appointment.scheduleid
        GROUP BY schedule.scheduleid");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>System & Patient/Doctor Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 14px; }
        th { background-color: #f2f2f2; }
        select, input[type=submit] { padding: 8px; font-size: 14px; }
    </style>
</head>
<body>

<h1>Klinik AzZahra - System & Patient/Doctor Report</h1>
<p>Generated on: <?php echo $today; ?></p>
<hr>

<h2>Overall System Summary</h2>
<table class="summary-table">
    <tr><td>Total Doctors</td><td><?php echo $doctorRes->num_rows; ?></td></tr>
    <tr><td>Total Patients</td><td><?php echo $patientRes->num_rows; ?></td></tr>
    <tr><td>Total Appointments</td><td><?php echo $appointmentRes->num_rows; ?></td></tr>
    <tr><td>Total Sessions</td><td><?php echo $scheduleRes->num_rows; ?></td></tr>
</table>

<hr>

<!-- Report Type Selector -->
<form method="POST" id="reportForm">
    <label for="report_type">Report Type:</label>
    <select name="report_type" id="report_type" onchange="document.getElementById('reportForm').submit();" required>
        <option value="">-- Choose Type --</option>
        <option value="patient" <?= $reportType == 'patient' ? 'selected' : '' ?>>Patient Report</option>
        <option value="doctor" <?= $reportType == 'doctor' ? 'selected' : '' ?>>Doctor Report</option>
        <option value="overall" <?= $reportType == 'overall' ? 'selected' : '' ?>>Full Overall Report</option>
    </select>

    <!-- Patient Dropdown -->
    <?php if ($reportType == 'patient'): ?>
        <label for="patient_id">Select Patient:</label>
        <select name="patient_id" required>
            <option value="">-- Choose Patient --</option>
            <?php while ($pat = $patients->fetch_assoc()) {
                $selected = ($pat['pid'] == $selectedPatientId) ? 'selected' : '';
                echo "<option value='{$pat['pid']}' $selected>{$pat['pname']}</option>";
            } ?>
        </select>
        <input type="submit" value="View Report">
    <?php endif; ?>

    <!-- Doctor Dropdown -->
    <?php if ($reportType == 'doctor'): ?>
        <label for="doctor_id">Select Doctor:</label>
        <select name="doctor_id" required>
            <option value="">-- Choose Doctor --</option>
            <?php while ($doc = $doctors->fetch_assoc()) {
                $selected = ($doc['docid'] == $selectedDoctorId) ? 'selected' : '';
                echo "<option value='{$doc['docid']}' $selected>{$doc['docname']}</option>";
            } ?>
        </select>
        <input type="submit" value="View Report">
    <?php endif; ?>
</form>

<!-- Patient Report Section -->
<?php if ($patientInfo): ?>
    <h2>Patient Information</h2>
    <table>
        <tr><th>Name</th><td><?php echo $patientInfo['pname']; ?></td></tr>
        <tr><th>Email</th><td><?php echo $patientInfo['pemail']; ?></td></tr>
        <tr><th>NIC</th><td><?php echo $patientInfo['pnic']; ?></td></tr>
        <tr><th>Telephone</th><td><?php echo $patientInfo['ptel']; ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo $patientInfo['pdob']; ?></td></tr>
        <tr><th>Address</th><td><?php echo $patientInfo['paddress']; ?></td></tr>
    </table>

    <h2>Appointment History</h2>
    <table>
        <tr><th>No.</th><th>Appointment No</th><th>Doctor</th><th>Session</th><th>Date</th><th>Time</th></tr>
        <?php 
        $counter = 1;
        while ($app = $appointments->fetch_assoc()) {
            echo "<tr><td>{$counter}</td><td>{$app['apponum']}</td><td>{$app['docname']}</td><td>{$app['title']}</td><td>{$app['appodate']}</td><td>{$app['scheduletime']}</td></tr>";
            $counter++;
        } ?>
    </table>
<?php endif; ?>

<!-- Doctor Report Section -->
<?php if ($doctorInfo): ?>
    <h2>Doctor Information</h2>
    <table>
        <tr><th>Name</th><td><?php echo $doctorInfo['docname']; ?></td></tr>
        <tr><th>Email</th><td><?php echo $doctorInfo['docemail']; ?></td></tr>
        <tr><th>NIC</th><td><?php echo $doctorInfo['docnic']; ?></td></tr>
        <tr><th>Specialties</th><td><?php echo $doctorInfo['specialties']; ?></td></tr>
        <tr><th>Telephone</th><td><?php echo $doctorInfo['doctel']; ?></td></tr>
    </table>

    <h2>Doctor's Sessions & Appointments</h2>
    <table>
        <tr><th>No.</th><th>Session Title</th><th>Date</th><th>Time</th><th>Total Appointments</th></tr>
        <?php 
        $counter = 1;
        while ($session = $sessions->fetch_assoc()) {
            echo "<tr><td>{$counter}</td><td>{$session['title']}</td><td>{$session['scheduledate']}</td><td>{$session['scheduletime']}</td><td>{$session['total_appointments']}</td></tr>";
            $counter++;
        } ?>
    </table>

    <h2>Patients Treated by This Doctor</h2>
    <table>
        <tr><th>No.</th><th>Patient Name</th><th>Email</th><th>NIC</th><th>Phone</th><th>Session</th></tr>
        <?php 
        $counter = 1;
        while ($pat = $doctorPatients->fetch_assoc()) {
            echo "<tr>
                <td>{$counter}</td>
                <td>{$pat['pname']}</td>
                <td>{$pat['pemail']}</td>
                <td>{$pat['pnic']}</td>
                <td>{$pat['ptel']}</td>
                <td>{$pat['title']}</td>
            </tr>";
            $counter++;
        } ?>
    </table>
<?php endif; ?>

<!-- Overall Report Section -->
<?php if ($reportType == 'overall'): ?>

    <h2>All Patients</h2>
    <table>
        <tr><th>No.</th><th>Name</th><th>Email</th><th>NIC</th><th>Phone</th><th>DOB</th><th>Address</th></tr>
        <?php $i=1; while ($pat = $allPatients->fetch_assoc()) {
            echo "<tr>
                <td>{$i}</td>
                <td>{$pat['pname']}</td>
                <td>{$pat['pemail']}</td>
                <td>{$pat['pnic']}</td>
                <td>{$pat['ptel']}</td>
                <td>{$pat['pdob']}</td>
                <td>{$pat['paddress']}</td>
            </tr>";
            $i++;
        } ?>
    </table>

    <h2>All Doctors</h2>
    <table>
        <tr><th>No.</th><th>Name</th><th>Email</th><th>NIC</th><th>Specialties</th><th>Phone</th></tr>
        <?php $i=1; while ($doc = $allDoctors->fetch_assoc()) {
            echo "<tr>
                <td>{$i}</td>
                <td>{$doc['docname']}</td>
                <td>{$doc['docemail']}</td>
                <td>{$doc['docnic']}</td>
                <td>{$doc['specialties']}</td>
                <td>{$doc['doctel']}</td>
            </tr>";
            $i++;
        } ?>
    </table>

    <h2>All Sessions with Appointment Count</h2>
    <table>
        <tr><th>No.</th><th>Title</th><th>Date</th><th>Time</th><th>Doctor</th><th>Total Appointments</th></tr>
        <?php $i=1; while ($sess = $allSessions->fetch_assoc()) {
            echo "<tr>
                <td>{$i}</td>
                <td>{$sess['title']}</td>
                <td>{$sess['scheduledate']}</td>
                <td>{$sess['scheduletime']}</td>
                <td>{$sess['docname']}</td>
                <td>{$sess['total_appointments']}</td>
            </tr>";
            $i++;
        } ?>
    </table>
<?php endif; ?>

<!-- Footer and Print Button -->
<?php if ($patientInfo || $doctorInfo || $reportType == 'overall'): ?>
    <button onclick="window.print()" class="btn-primary btn" style="float:right; margin: 20px;">🖨️ Print / Save as PDF</button>
<?php endif; ?>

<p style="text-align: center; font-size: 13px; color: #999;">Generated by Klinik AzZahra Registration And Appointment System | <?php echo date("Y"); ?></p>

</body>
</html>
