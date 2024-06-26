<?php
session_start();
include_once('../connection.php');
include_once('../assests/content/static/template.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM course_materials WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $_SESSION['delete_success'] = "Material deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting material: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error in SQL query: " . $conn->error;
        }
    }
}

$search_batch = '';
if (isset($_GET['search'])) {
    $search_batch = $_GET['search_batch'];
}

$sql = "SELECT * FROM course_materials";
$params = [];
if ($search_batch) {
    $sql .= " WHERE batch_number LIKE ?";
    $params[] = '%' . $search_batch . '%';
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $modules = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $error = "Error in SQL query: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Module Table Page</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-course_materials.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body class="body">
<div class="container">
    <div class="topic">
        <h1>Course Materials</h1>
    </div>
    <div class="add-new">
        <br>
        <a href="add_material.php" class="btn btn-success">Add New Material</a>
    </div>
    <br><br>
    <div class="search-container">
        <form method="get" action="coursematerials.php">
            <div class="input-group mb-3">
                <input type="text" name="search_batch" class="form-control" placeholder="Search by Batch Name" value="<?php echo htmlspecialchars($search_batch); ?>">
                <button class="btn btn-primary" type="submit" name="search">Search</button>
            </div>
        </form>
    </div>
    <div class="table-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['delete_success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['delete_success']); ?>
            </div>
            <?php unset($_SESSION['delete_success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['edit_success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['edit_success']); ?>
            </div>
            <?php unset($_SESSION['edit_success']); ?>
        <?php endif; ?>
        <?php if (!empty($modules)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Module Code</th>
                        <th>Topic</th>
                        <th>Batch Number</th>
                        <th>Course</th>
                        <th>Download</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($module['module_code']); ?></td>
                            <td><?php echo htmlspecialchars($module['topic']); ?></td>
                            <td><?php echo htmlspecialchars($module['batch_number']); ?></td>
                            <td><?php echo htmlspecialchars($module['course']); ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($module['download']) ?>" class="view-link" target="_blank">Download</a>
                            </td>
                            <td>
                                <a href="edit_material.php?id=<?= htmlspecialchars($module['id']) ?>" class="btn btn-primary">Edit</a>
                            </td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($module['id']) ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <img src="Images/no_data.jpg" alt="No data available">
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
