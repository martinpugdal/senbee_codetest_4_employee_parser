<?php
require 'functions.php';

$employees = parse_csv('employees.csv');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$group  = isset($_GET['group_by']) ? $_GET['group_by'] : 'no';

$filtered = filter_employees($employees, $search);
$grouped  = group_by_department($filtered);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <!-- Styling is done by Copilot since we dont need that much styling and its very basic styling done faster -->
    <style>
        body { font-family: -apple-system, sans-serif; margin: 2rem; }
        form { margin-bottom: 1.5rem; }
        label { margin-right: 0.5rem; }
        input, select { margin-right: 1rem; padding: 0.25rem 0.5rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 0.4rem 0.6rem; text-align: left; }
        th { background: #f0f0f0; }
        h2 { margin-top: 1.5rem; }
    </style>
</head>
<body>
    <h1>Employees</h1>

    <form method="get">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" value="<?= escape($search) ?>">

        <label for="group_by">Group by department:</label>
        <select id="group_by" name="group_by">
            <option value="no" <?= $group === 'no' ? 'selected' : '' ?>>No</option>
            <option value="yes" <?= $group === 'yes' ? 'selected' : '' ?>>Yes</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <p><?= count($filtered) ?> employees found<?= $group === 'yes' ? ' in ' . count($grouped) . ' departments' : '' ?></p>

<?php if ($group === 'yes'): 
    foreach ($grouped as $dept => $emps): ?>
        <h2><?= escape($dept) ?> (<?= count($emps) ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Gender</th>
                    <th>Start Date</th>
                    <th>Salary</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($emps as $emp): ?>
                <tr>
                    <td><?= escape($emp['First Name']) ?></td>
                    <td><?= escape($emp['Gender']) ?></td>
                    <td><?= escape($emp['Start Date']) ?></td>
                    <td><?= escape($emp['Salary']) ?></td>
                    <td><?= escape($emp['Department']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php else: ?>  
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Gender</th>
                <th>Start Date</th>
                <th>Salary</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($filtered as $emp): ?>
            <tr>
                <td><?= escape($emp['First Name']) ?></td>
                <td><?= escape($emp['Gender']) ?></td>
                <td><?= escape($emp['Start Date']) ?></td>
                <td><?= escape($emp['Salary']) ?></td>
                <td><?= escape($emp['Department']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
