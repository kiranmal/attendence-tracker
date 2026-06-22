<?php

session_start();

require_once '../../../config/database.php';
require_once '../../Services/ReportService.php';

if (!isset($_SESSION['employee_id'])) {
    die('Please login first');
}

$employeeId = (int)$_SESSION['employee_id'];

$sql = "
SELECT
    a.*,
    e.employee_name,
    d.task1,
    d.task2,
    d.task3
FROM attendance a
INNER JOIN employees e
    ON e.id = a.employee_id
LEFT JOIN daily_tasks d
    ON d.attendance_id= a.id
WHERE a.employee_id = $employeeId
ORDER BY a.attendance_date DESC
";

$result = mysqli_query($conn, $sql);

$reportService = new ReportService();

$currentMonth = date('F Y');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Attendance</title>

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
            text-align: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            color: #1e293b;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .month-badge {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 15px;
        }

        .table-container {
            background: white;
            border-radius: 18px;
            overflow-x: auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th {
            background: #2563eb;
            color: white;
            padding: 16px;
            text-align: center;
            white-space: nowrap;
        }

        .attendance-table td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .attendance-table tr:hover {
            background: #f8fafc;
        }

        .office {
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .outside {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .pending {
            color: #dc2626;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
            font-size: 14px;
        }

        .mobile-card {
            display: none;
        }

        .attendance-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .attendance-card h3 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .label {
            color: #64748b;
            font-weight: 600;
        }

        .value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
        }

        @media (max-width: 480px) {

            body {
                padding: 10px;
            }

            .mobile-card {
                display: none;
            }

            .page-header h2 {
                font-size: 24px;
            }

            .month-badge {
                font-size: 14px;
                padding: 8px 16px;
            }

            /* Hide table on mobile */
            .table-container {
                display: none;
            }

            /* Show cards on mobile */
            .mobile-card {
                display: block;
            }

            .attendance-card {
                padding: 20px;
            }

            .row {
                margin-bottom: 12px;
            }

            .label,
            .value {
                font-size: 14px;
            }
        }
    </style>

</head>

<body>

    <div class="page-header">

        <h2>📅 My Attendance</h2>

        <div class="month-badge">
            <?= $currentMonth ?>
        </div>

    </div>

    <div class="table-container">

        <table class="attendance-table">

            <thead>

                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Worked Hours</th>
                    <th>Balance</th>
                    <th>Location</th>
                    <th>Tasks</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?= date(
                                'd M Y',
                                strtotime($row['attendance_date'])
                            ) ?>
                        </td>

                        <td>
                            <?= date(
                                'D',
                                strtotime($row['attendance_date'])
                            ) ?>
                        </td>

                        <td>
                            <?= $row['check_in'] ?>
                        </td>

                        <td>
                            <?= $row['check_out']
                                ? $row['check_out']
                                : '<span class="pending">Pending</span>' ?>
                        </td>

                        <td>
                            <?= $reportService->getWorkedHours(
                                $row['check_in'],
                                $row['check_out']
                            ) ?>
                        </td>

                        <td>
                            <?= $reportService->getDailyBalance(
                                $row['attendance_date'],
                                $row['check_in'],
                                $row['check_out']
                            ) ?>
                        </td>

                        <td>

                            <span class="
                            <?= strtolower(
                                $row['location_status']
                            ) ?>">
                                <?= $row['location_status'] ?>
                            </span>

                        </td>

                        <td>

                            <?php

                            if ($row['task1'])
                                echo "• " . htmlspecialchars($row['task1']) . "<br>";

                            if ($row['task2'])
                                echo "• " . htmlspecialchars($row['task2']) . "<br>";

                            if ($row['task3'])
                                echo "• " . htmlspecialchars($row['task3']);

                            ?>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>
    <div class="mobile-card">

        <?php
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="attendance-card">

                <h3>
                    <?= date(
                        'd M Y',
                        strtotime($row['attendance_date'])
                    ) ?>
                </h3>

                <div class="row">
                    <span class="label">Day</span>
                    <span class="value">
                        <?= date('l', strtotime($row['attendance_date'])) ?>
                    </span>
                </div>

                <div class="row">
                    <span class="label">Check In</span>
                    <span class="value"><?= $row['check_in'] ?></span>
                </div>

                <div class="row">
                    <span class="label">Check Out</span>
                    <span class="value">
                        <?= $row['check_out'] ?: 'Pending' ?>
                    </span>
                </div>

                <div class="row">
                    <span class="label">Worked Hours</span>
                    <span class="value">
                        <?= $reportService->getWorkedHours(
                            $row['check_in'],
                            $row['check_out']
                        ) ?>
                    </span>
                </div>

                <div class="row">
                    <span class="label">Location</span>
                    <span class="value">
                        <?= $row['location_status'] ?>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Tasks</span>

                    <span class="value">

                        <?= htmlspecialchars($row['task1']) ?>

                        <?php if ($row['task2']) { ?>
                            <br><?= htmlspecialchars($row['task2']) ?>
                        <?php } ?>

                        <?php if ($row['task3']) { ?>
                            <br><?= htmlspecialchars($row['task3']) ?>
                        <?php } ?>

                    </span>
                </div>

            </div>

        <?php } ?>

    </div>

    <div class="footer">
        PCL Attendance System
    </div>


    <script>
        document
            .getElementById('taskForm')
            .addEventListener('submit', async function(e) {

                e.preventDefault();

                const formData = new FormData(this);

                formData.append(
                    'attendance_id',
                    this.dataset.attendance
                );

                const response = await fetch(
                    '/pcl_attendence_sheet/public/employee/save_tasks.php', {
                        method: 'POST',
                        body: formData
                    }
                );

                const result = await response.text();

                alert('Tasks Saved Successfully');

                document.getElementById('taskModal').style.display = 'none';

            });
    </script>
</body>


</html>