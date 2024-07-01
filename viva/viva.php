<?php
session_start();

include_once('../connection.php');

// Loading the template.php
include_once('../assests/content/static/template.php');

// Fetch the current user's team ID
$current_username = $_SESSION['username']; // Assuming the username is stored in session
$team_id = null;
$sql = "SELECT viva_name, id FROM team_members WHERE username = '" . mysqli_real_escape_string($conn, $current_username) . "'";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $team_id = $row['id'];
    $exam_name = $row['viva_name'];
}

// Fetch the submitted details from the form
$module_name = isset($_POST['module_name']) ? $_POST['module_name'] : '';
$exam_name = isset($_POST['exam_name']) ? $_POST['exam_name'] : '';

// Fetch all team members' data based on the team ID and the selected viva name
$viva_data = [];
if ($team_id !== null && !empty($exam_name)) {
    $sql = "SELECT * FROM team_members WHERE id = " . intval($team_id) . " AND viva_name = '" . mysqli_real_escape_string($conn, $exam_name) . "'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $viva_data[] = $row;
    }
}


$message = isset($_GET['message']) ? $_GET['message'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viva Session Registration</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viva.css">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
<div class="main_container">
<marquee><p class="text">*If you are registering for a team project, please have only the group leader fill out the form.</p></marquee>
<h1 class="title">Viva Session Registration</h1>

    <?php if ($message == 'updated'): ?>
        <div class="alert alert-success">Records are updated successfully.</div>
    <?php elseif ($message == 'deleted'): ?>
        <div class="alert alert-danger">Records are deleted successfully.</div>
    <?php elseif ($message == 'already_registered'): ?>
        <div class="alert alert-warning">You have already registered.</div>
    <?php endif; ?>

    <div class="form_container">
        <form id="vivaForm" action="submit.php" method="POST">
            <div class="form_group">
                <label for="viva_name">Viva Name:</label>
                <input type="text" id="viva_name" name="viva_name" value="<?= htmlspecialchars($exam_name) ?>" readonly>
            </div>

            <div class="form_group">
                <label for="module_name">Module Name:</label>
                <input type="text" id="module_name" name="module_name" value="<?= htmlspecialchars($module_name) ?>" readonly>
            </div>
            <div class="form_group">
                <div id="teamMembers">
                    <div class="member">
                        <label>Username: <input type="text" name="username[]" required></label>
                        <label>Name: <input type="text" name="name[]" required></label>
                    </div>
                </div>
            </div>
            <div class="button_container">
                <button type="button" id="addMember" class="view-link">Add Member</button>
                <button type="submit" class="view-link">Submit</button>
            </div>
        </form>
    </div>
    
    <br>
    <br>
    <div id="timer"></div>
</div>
    
<script>
    const vivaData = <?php echo json_encode($viva_data); ?>;

    document.getElementById('addMember').addEventListener('click', function() {
        const teamMembersDiv = document.getElementById('teamMembers');
        const newMemberDiv = document.createElement('div');
        newMemberDiv.classList.add('member');
        newMemberDiv.innerHTML = `
            <label>Username: <input type="text" name="username[]" required></label>
            <label>Name: <input type="text" name="name[]" required></label>
        `;
        teamMembersDiv.appendChild(newMemberDiv);
    });

    document.getElementById('vivaForm').addEventListener('submit', function(event) {
        const usernames = Array.from(document.querySelectorAll('input[name="username[]"]')).map(input => input.value);
        const vivaName = document.getElementById('viva_name').value;
        
        const hasDuplicate = vivaData.some(member => usernames.includes(member.username) && member.viva_name === vivaName);

        if (hasDuplicate) {
            alert('You have already registered!');
            event.preventDefault();
        }
    });
</script>
<script src="script.js"></script>

</body>
</html>
