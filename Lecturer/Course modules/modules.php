<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

// Fetch distinct courses and module names for filtering
$courses = [];
$module_names = [];
$result = mysqli_query($conn, "SELECT DISTINCT course_name FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course_name'];
}
$result = mysqli_query($conn, "SELECT DISTINCT module_name FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $module_names[] = $row['module_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchModules() {
            var course = document.getElementById('course').value;
            var moduleName = document.getElementById('module_name').value;
            $.ajax({
                url: 'search_modules.php',
                type: 'GET',
                data: { course: course, module_name: moduleName },
                success: function(response) {
                    document.getElementById('modules-tbody').innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</head>
<body>
    <div class="container_main">
        <div class="topic">
            <h1>Modules</h1>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="course" class="form-label">Search by Course:</label>
                <div class="input-group">
                    <select id="course" name="course" class="form-select">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" class="btn btn-outline-primary" onclick="searchModules()"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-6">
                <label for="module_name" class="form-label">Search by Module Name:</label>
                <div class="input-group">
                    <select id="module_name" name="module_name" class="form-select">
                        <option value="">Select Module Name</option>
                        <?php foreach ($module_names as $module_name): ?>
                            <option value="<?= htmlspecialchars($module_name) ?>"><?= htmlspecialchars($module_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="search-icon" class="btn btn-outline-primary" onclick="searchModules()"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Module Name</th>
                        <th>Module Code</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Number of Assignments</th>
                    </tr>
                </thead>
                <tbody id="modules-tbody">
                    <!-- Modules data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
