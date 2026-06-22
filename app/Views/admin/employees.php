<?php

session_start();

require_once '../../../config/database.php';

$result = mysqli_query(
    $conn,
    "SELECT * FROM employees"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f7fb;
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-header h1 {
            color: #1e293b;
        }

        .dashboard-btn {
            text-decoration: none;
            background: #2563eb;
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
        }

        .table-container {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-table th {
            background: #2563eb;
            color: #fff;
            padding: 15px;
        }

        .employee-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .employee-table tr:hover {
            background: #f8fafc;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .action-btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .edit-btn {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .deactivate-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        .mobile-card {
            display: none;
        }

        @media(max-width:768px) {

            body {
                padding: 12px;
            }

            .page-header {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .dashboard-btn {
                text-align: center;
            }

            .table-container {
                display: none;
            }

            .mobile-card {
                display: block;
            }

            .employee-card {
                background: #fff;
                padding: 18px;
                border-radius: 16px;
                margin-bottom: 15px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            }

            .employee-card h3 {
                color: #2563eb;
                margin-bottom: 15px;
            }

            .card-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .card-label {
                color: #64748b;
                font-weight: 600;
            }

            .card-actions {
                display: flex;
                gap: 10px;
                margin-top: 15px;
            }

            .card-actions a {
                flex: 1;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="page-header">

        <h1>👥 Employees</h1>

        <a href="dashboard.php" class="dashboard-btn">
            ← Dashboard
        </a>

    </div>

    <div class="table-container">

        <table class="employee-table">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php mysqli_data_seek($result, 0); ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td><?= $row['id'] ?></td>

                    <td><?= $row['employee_name'] ?></td>

                    <td><?= $row['phone_number'] ?></td>

                    <td><?= $row['department'] ?></td>

                    <td>

                        <?php if ($row['status'] == 'active') { ?>

                            <span class="status-active">
                                Active
                            </span>

                        <?php } else { ?>

                            <span class="status-inactive">
                                Inactive
                            </span>

                        <?php } ?>

                    </td>

                    <td>

                        <a
                            class="action-btn edit-btn"
                            href="edit_employee.php?id=<?= $row['id'] ?>">
                            Edit
                        </a>

                        <a
                            class="action-btn deactivate-btn"
                            href="/pcl_attendence_sheet/public/admin/deactivate_employee.php?id=<?= $row['id'] ?>"
                            onclick="return confirm('Deactivate employee?')">
                            Deactivate
                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

    <div class="mobile-card">

        <?php mysqli_data_seek($result, 0); ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="employee-card">

                <h3>
                    <?= $row['employee_name'] ?>
                </h3>

                <div class="card-row">
                    <span class="card-label">ID</span>
                    <span><?= $row['id'] ?></span>
                </div>

                <div class="card-row">
                    <span class="card-label">Phone</span>
                    <span><?= $row['phone_number'] ?></span>
                </div>

                <div class="card-row">
                    <span class="card-label">Department</span>
                    <span><?= $row['department'] ?></span>
                </div>

                <div class="card-row">
                    <span class="card-label">Status</span>

                    <?php if ($row['status'] == 'active') { ?>
                        <span class="status-active">Active</span>
                    <?php } else { ?>
                        <span class="status-inactive">Inactive</span>
                    <?php } ?>

                </div>

                <div class="card-actions">

                    <a
                        class="action-btn edit-btn"
                        href="edit_employee.php?id=<?= $row['id'] ?>">
                        Edit
                    </a>

                    <a
                        class="action-btn deactivate-btn"
                        href="/pcl_attendence_sheet/public/admin/deactivate_employee.php?id=<?= $row['id'] ?>"
                        onclick="return confirm('Deactivate employee?')">
                        Deactivate
                    </a>

                </div>

            </div>

        <?php } ?>

    </div>
</body>

</html>