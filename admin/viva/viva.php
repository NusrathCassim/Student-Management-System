<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Fetch courses and batch numbers from login_tbl
$result = mysqli_query($conn, "SELECT DISTINCT course, batch_number FROM login_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course'];
    $batch_numbers[] = $row['batch_number'];
}
$courses = array_unique($courses);
$batch_numbers = array_unique($batch_numbers);

// Fetch module names, codes, and courses from modules
$result = mysqli_query($conn, "SELECT module_name, module_code, course FROM modules");
while ($row = mysqli_fetch_assoc($result)) {
    $modules[] = $row;
}

// Fetch all data from the exam_schedule table
$exam_schedule_data = [];
$result = mysqli_query($conn, "SELECT * FROM viva_schedules");
while ($row = mysqli_fetch_assoc($result)) {
    $exam_schedule_data[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="viva.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modules = <?php echo json_encode($modules); ?>;
            const courseSelect = document.getElementById('course');
            const moduleNameSelect = document.getElementById('module_name');
            const moduleCodeSelect = document.getElementById('module_code');

            courseSelect.addEventListener('change', function() {
                const selectedCourse = this.value;

                // Clear previous options
                moduleNameSelect.innerHTML = '<option value="">Select Module Name</option>';
                moduleCodeSelect.innerHTML = '<option value="">Select Module Code</option>';

                // Populate module dropdowns based on selected course
                modules.forEach(module => {
                    if (module.course === selectedCourse) {
                        const optionName = document.createElement('option');
                        optionName.value = module.module_name;
                        optionName.textContent = module.module_name;
                        moduleNameSelect.appendChild(optionName);
                    }
                });
            });

            moduleNameSelect.addEventListener('change', function() {
                const selectedModuleName = this.value;

                // Clear previous options
                moduleCodeSelect.innerHTML = '<option value="">Select Module Code</option>';

                // Populate module code based on selected module name
                modules.forEach(module => {
                    if (module.module_name === selectedModuleName) {
                        const optionCode = document.createElement('option');
                        optionCode.value = module.module_code;
                        optionCode.textContent = module.module_code;
                        moduleCodeSelect.appendChild(optionCode);
                    }
                });
            });

            // Search functionality
            const searchIcon = document.getElementById('search-icon');
            const searchSelect = document.getElementById('search');
            const rows = document.querySelectorAll('#exam-schedule-tbody tr');

            function filterRows() {
                const searchQuery = searchSelect.value.toLowerCase().trim();
                rows.forEach(row => {
                    const courseCell = row.querySelector('td:nth-child(1)');
                    if (courseCell.textContent.toLowerCase().includes(searchQuery)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchIcon.addEventListener('click', filterRows);
            searchSelect.addEventListener('change', filterRows);

            function manageExam(row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('modal').style.display = 'block';
                document.getElementById('manage-course').value = cells[0].textContent;
                document.getElementById('manage-batch_number').value = cells[1].textContent;
                document.getElementById('manage-viva_name').value = cells[2].textContent;
                document.getElementById('manage-date').value = cells[3].textContent;
                document.getElementById('manage-location').value = cells[4].textContent;
                document.getElementById('manage-allow_submission').checked = cells[5].textContent === 'Yes';
            }

            // Attach the event listener to the buttons after the rows are created
            document.querySelectorAll('.manage-button').forEach(button => {
                button.addEventListener('click', function() {
                    manageExam(this.closest('tr'));
                });
            });

            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            document.querySelector('.close').addEventListener('click', closeModal);
        });

    </script>

</head>
<body>
<div class="main_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">Records are inserted successfully.</div>
    <?php elseif ($message == 'updated'): ?>
        <div class="alert alert-success">Records are updated successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">Records are deleted successfully.</div>
    <?php endif; ?>

    <form action="viva_submission.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course" name="course" required>
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="module_name">Module Name:</label>
                    <select id="module_name" name="module_name" required>
                        <option value="">Select Module Name</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="module_code">Module Code:</label>
                    <select id="module_code" name="module_code" required>
                        <option value="">Select Module Code</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="batch_number">Batch Number:</label>
                    <select id="batch_number" name="batch_number" required>
                        <option value="">Select Batch Number</option>
                        <?php foreach ($batch_numbers as $batch_number): ?>
                            <option value="<?= htmlspecialchars($batch_number) ?>"><?= htmlspecialchars($batch_number) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="viva_name">Viva Name:</label>
                    <input type="text" id="viva_name" name="viva_name" required>
                </div>

                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>
                </div>
            </div>

        </div>
        <br>
        <button type="submit" class="view-link">Submit</button>
    </form>


    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Exam Schedule</h2>
            <form action="viva_scheduleUpdateDelete.php" method="POST">
                <input type="hidden" name="batch_number" id="manage-batch_number">
                <div class="form-group">
                    <label for="manage-course">Course:</label>
                    <input type="text" id="manage-course" name="course" readonly>
                </div>
                <div class="form-group">
                    <label for="manage-viva_name">Viva Name:</label>
                    <input type="text" id="manage-viva_name" name="viva_name">
                </div>
                <div class="form-group">
                    <label for="manage-date">Date:</label>
                    <input type="date" id="manage-date" name="date">
                </div>
                <div class="form-group">
                    <label for="manage-location">Location:</label>
                    <input type="text" id="manage-location" name="location">
                </div>
                <div class="form-group">
                    <label for="manage-allow_submission">Allow Submission</label>
                    <input type="checkbox" id="manage-allow_submission" name="allow_submission" value="1" class="checkbox-label">
                </div>

                <br>
                <div class="form-group">
                    <div class="button-container">
                        <button type="submit" name="action" value="edit" class="view-link">Edit</button>
                        <button type="submit" name="action" value="delete" class="delete-link">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        
        <br>
        <h2 class="topic">Viva Schedule Details</h2>
        <br>

        <!-- Search bar -->
        <div class="search-bar">
            <label for="search">Search by Course:</label>
            <select id="search" class="search-select">
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                <?php endforeach; ?>
            </select>
            <button id="search-icon"><i class="fas fa-search"></i></button>
        </div>
        <br>



        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Batch Number</th>
                        <th>Viva Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Allow Registration</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="exam-schedule-tbody">
                    <?php foreach ($exam_schedule_data as $row): ?>
                        <tr>
                            <td data-cell="Course"><?= htmlspecialchars($row['course']) ?></td>
                            <td data-cell="Batch Number"><?= htmlspecialchars($row['batch_number']) ?></td>
                            <td data-cell="Exam Name"><?= htmlspecialchars($row['viva_name']) ?></td>
                            <td data-cell="Date"><?= htmlspecialchars($row['date']) ?></td>
                            <td data-cell="Location"><?= htmlspecialchars($row['location']) ?></td>
                            <td data-cell="Allow Registration"><?= $row['allow_submission'] ? 'Yes' : 'No' ?></td>
                            <td data-cell="Action"><button onclick="manageExam(this.parentNode.parentNode)" class="manage-button view-link">Manage</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</div>
</body>
</html>