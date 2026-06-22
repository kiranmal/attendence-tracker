<?php

session_start();
require_once '../../../config/database.php';
require_once '../../Services/ReportService.php';

$sql = "
SELECT
e.employee_name,
a.attendance_date,
a.check_in,
a.checkin_city,
a.checkin_pincode,
a.checkin_address,
a.checkin_state,
a.checkin_country,
a.checkin_latitude,
a.checkin_longitude,
a.check_out,
a.checkout_city,
a.checkout_pincode,
a.checkout_address,
a.checkout_state,
a.checkout_latitude,
a.checkout_longitude,
a.checkout_country,
a.location_status,
d.task1,
    d.task2,
    d.task3
FROM attendance a
INNER JOIN employees e
ON e.id = a.employee_id
LEFT JOIN daily_tasks d
    ON d.attendance_id= a.id
WHERE a.employee_id = e.id
ORDER BY a.id DESC
";

$result = mysqli_query($conn, $sql);

$reportService = new ReportService();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Attendance Report</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 1300px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .table-wrapper {
            overflow-x: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #0d6efd;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .map-btn {
            display: inline-block;
            padding: 6px 12px;
            background: #198754;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .map-btn:hover {
            background: #157347;
        }

        .inside {
            color: #871919;
            font-weight: bold;
        }

        .outside {
            color: #35dc49;
            font-weight: bold;
        }

        .pending {
            color: #fd7e14;
            font-weight: bold;
        }

        /* Mobile Cards */
        @media(max-width:768px) {

            .table-wrapper {
                display: none;
            }

            .mobile-card {
                background: white;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .mobile-card p {
                margin-bottom: 8px;
                font-size: 14px;
            }

            .label {
                font-weight: bold;
                color: #555;
            }
        }

        @media(min-width:769px) {
            .mobile-view {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Attendance Report</h1>

        <!-- Desktop Table -->
        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Balance</th>
                        <th>Tasks</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td><?= htmlspecialchars($row['employee_name']) ?></td>

                            <td><?= $row['attendance_date'] ?></td>

                            <td><?= $row['check_in'] ?></td>

                            <td><?= $row['check_out'] ?></td>

                            <td>
                                <a
                                    class="map-btn"
                                    target="_blank"
                                    href="https://maps.google.com/?q=<?= $row['checkin_latitude'] ?>,<?= $row['checkin_longitude'] ?>">
                                    View Map
                                </a>
                            </td>

                            <td
                                class="<?=
                                        strtolower($row['location_status']) == 'inside'
                                            ? 'inside'
                                            : 'outside'
                                        ?>">
                                <?= $row['location_status'] ?>
                            </td>

                            <td>
                                <?= $reportService->getDailyBalance(
                                    $row['attendance_date'],
                                    $row['check_in'],
                                    $row['check_out']
                                ) ?>
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

        <!-- Mobile Cards -->
        <div class="mobile-view">

            <?php

            mysqli_data_seek($result, 0);

            while ($row = mysqli_fetch_assoc($result)) {

            ?>

                <div class="mobile-card">

                    <p><span class="label">Employee:</span> <?= $row['employee_name'] ?></p>

                    <p><span class="label">Date:</span> <?= $row['attendance_date'] ?></p>

                    <p><span class="label">Check In:</span> <?= $row['check_in'] ?></p>

                    <p><span class="label">Check Out:</span> <?= $row['check_out'] ?></p>

                    <p>
                        <span class="label">Status:</span>

                        <span
                            class="<?=
                                    strtolower($row['location_status']) == 'inside'
                                        ? 'inside'
                                        : 'outside'
                                    ?>">
                            <?= $row['location_status'] ?>
                        </span>
                    </p>

                    <p>
                        <span class="label">Balance:</span>

                        <?= $reportService->getDailyBalance(
                            $row['attendance_date'],
                            $row['check_in'],
                            $row['check_out']
                        ) ?>
                    </p>

                    <a
                        class="map-btn"
                        target="_blank"
                        href="https://maps.google.com/?q=<?= $row['checkin_latitude'] ?>,<?= $row['checkin_longitude'] ?>">
                        View Location
                    </a>

                </div>

            <?php } ?>

        </div>

    </div>

</body>

</html>