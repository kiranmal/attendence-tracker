<?php

require_once '../../../config/database.php';

$employeeId = (int)$_GET['employee_id'];

$sql = "
SELECT
    a.attendance_date,
    a.check_in,
    a.check_out,
    d.task1,
    d.task2,
    d.task3

FROM attendance a

LEFT JOIN daily_tasks d
ON a.id = d.attendance_id

WHERE a.employee_id = $employeeId

ORDER BY a.attendance_date DESC
";

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <style>
        .report-container {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .report-table th {
            background: #2563eb;
            color: white;
        }

        .task-list {
            max-height: 90px;
            /* approximately 3 tasks */
            overflow-y: auto;
            text-align: left;
        }

        .task-list div {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
    <div class="report-container">
        <table class="report-table">
            <thead>
                <tr>

                    <th>Date</th>

                    <th>Check In</th>

                    <th>Check Out</th>

                    <th>Worked Hours</th>

                    <th>Written Work</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?= date('D M Y', strtotime($row['attendance_date'])) ?>
                        </td>

                        <td>
                            <?= $row['check_in'] ? '✓' : '-' ?>
                        </td>

                        <td>
                            <?= $row['check_out'] ? '✓' : '-' ?>
                        </td>
                        <td>
                            <?php

                            if ($row['check_out']) {
                                echo round((strtotime($row['check_out']) - strtotime($row['check_in'])) / 3600, 2) . " hrs";
                            } else {
                                echo "-";
                            }

                            ?>
                        </td>

                        <td>

                            <div class="task-list">

                                <?php if ($row['task1']) { ?>
                                    <div><?= htmlspecialchars($row['task1']) ?></div>
                                <?php } ?>

                                <?php if ($row['task2']) { ?>
                                    <div><?= htmlspecialchars($row['task2']) ?></div>
                                <?php } ?>

                                <?php if ($row['task3']) { ?>
                                    <div><?= htmlspecialchars($row['task3']) ?></div>
                                <?php } ?>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>

</body>

</html>