<?php

require_once '../../../config/database.php';

$currentMonth = date('F Y');
$currentDay = date('j');

/*
|--------------------------------------------------------------------------
| Count Holidays This Month
|--------------------------------------------------------------------------
*/

$holidayQuery = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total_holidays
    FROM holidays
    WHERE MONTH(holiday_date) = MONTH(CURDATE())
    AND YEAR(holiday_date) = YEAR(CURDATE())
    "
);

$holidayData =
    mysqli_fetch_assoc($holidayQuery);

$totalHolidays =
    (int)$holidayData['total_holidays'];

/*
|--------------------------------------------------------------------------
| Count Sundays Until Today
|--------------------------------------------------------------------------
*/

$totalSundays = 0;

for ($i = 1; $i <= $currentDay; $i++) {

    $date =
        date('Y-m-') .
        str_pad(
            $i,
            2,
            '0',
            STR_PAD_LEFT
        );

    if (date('w', strtotime($date)) == 0) {
        $totalSundays++;
    }
}

/*
|--------------------------------------------------------------------------
| Working Days
|--------------------------------------------------------------------------
*/

$totalWorkingDays =
    max(
        0,
        $currentDay
            - $totalSundays
            - $totalHolidays
    );



/*
|--------------------------------------------------------------------------
| Attendance Report
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

e.employee_name,

COUNT(a.id) AS present_days

FROM employees e

LEFT JOIN attendance a

ON e.id = a.employee_id

AND MONTH(a.attendance_date) = MONTH(CURDATE())

AND YEAR(a.attendance_date) = YEAR(CURDATE())

WHERE e.status = 'active'

GROUP BY e.id

ORDER BY e.employee_name

";

$result = mysqli_query(
    $conn,
    $sql
);

/*
|--------------------------------------------------------------------------
| Store Result Into Array
|--------------------------------------------------------------------------
*/

$employees = [];

while (
    $row = mysqli_fetch_assoc($result)
) {
    $employees[] = $row;
}

$totalTasks =
    $sql = "
SELECT
    e.id,
    e.employee_name,
    COUNT(a.id) AS present_days,

    GROUP_CONCAT(
        CONCAT_WS('<br>',
            NULLIF(d.task1,''),
            NULLIF(d.task2,''),
            NULLIF(d.task3,'')
        )
        SEPARATOR '<hr>'
    ) AS tasks

FROM employees e

LEFT JOIN attendance a
ON e.id = a.employee_id
AND MONTH(a.attendance_date)=MONTH(CURDATE())
AND YEAR(a.attendance_date)=YEAR(CURDATE())

LEFT JOIN daily_tasks d
ON a.id = d.attendance_id

WHERE e.status='active'

GROUP BY e.id

ORDER BY e.employee_name
";

$result = mysqli_query($conn, $sql);

$employees = [];

while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Monthly Attendance Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f4f7fb;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #1e293b;
            margin-bottom: 10px;
        }

        .month-badge {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 600;
        }

        .table-container {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #2563eb;
            color: white;
            padding: 16px;
            text-align: center;
        }

        table td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        table tr:hover {
            background: #f8fafc;
        }

        .present {
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .absent {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .holiday {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .view-btn {
            background: #2563eb;
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }

        .view-btn:hover {
            background: #1d4ed8;
        }

        .mobile-cards {
            display: none;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
        }

        @media (max-width:768px) {

            body {
                padding: 12px;
            }

            .header h1 {
                font-size: 26px;
            }

            .table-container {
                display: none;
            }

            .mobile-cards {
                display: block;
            }

            .employee-card {
                background: white;
                border-radius: 16px;
                padding: 18px;
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
                margin-bottom: 12px;
            }

            .label {
                color: #64748b;
                font-weight: 600;
            }
        }
    </style>

</head>

<body>

    <div class="header">

        <h1>📊 Monthly Attendance Report</h1>

        <div class="month-badge">
            <?= $currentMonth ?>
        </div>

    </div>

    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Employee Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Holiday</th>
                    <th>Working Days</th>
                    <th>Daily Tasks</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($employees as $row) {

                    $presentDays =
                        (int)$row['present_days'];

                    $absentDays =
                        max(
                            0,
                            $totalWorkingDays
                                - $presentDays
                        );

                ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars(
                                $row['employee_name']
                            ) ?>
                        </td>

                        <td>
                            <span class="present">
                                <?= $presentDays ?>
                            </span>
                        </td>

                        <td>
                            <span class="absent">
                                <?= $absentDays ?>
                            </span>
                        </td>

                        <td>
                            <span class="holiday">
                                <?= $totalHolidays ?>
                            </span>
                        </td>

                        <td>
                            <?= $totalWorkingDays ?>
                        </td>
                        <td style="text-align:left;max-width:350px;">

                            <a href="timecheck.php?employee_id=<?= $row['id'] ?>" class="view-btn">
                                View Work
                            </a>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>
        </table>

    </div>

    <div
        style="
        margin-top:30px;
        background:white;
        padding:20px;
        border-radius:18px;
        box-shadow:0 8px 25px rgba(0,0,0,.08);
    ">

        <h2
            style="
            margin-bottom:15px;
            color:#2563eb;
        ">
            Upcoming Holidays
        </h2>

        <?php

        $holidayResult = mysqli_query(
            $conn,
            "
        SELECT *
        FROM holidays
        WHERE holiday_date >= CURDATE()
        ORDER BY holiday_date
        "
        );

        if (
            mysqli_num_rows(
                $holidayResult
            ) > 0
        ) {

            while (
                $holiday =
                mysqli_fetch_assoc(
                    $holidayResult
                )
            ) {

        ?>

                <div
                    style="
                background:#fff7ed;
                border-left:5px solid orange;
                padding:12px;
                margin-bottom:10px;
                border-radius:10px;
            ">

                    <strong>
                        <?= htmlspecialchars(
                            $holiday['holiday_name']
                        ) ?>
                    </strong>

                    <br>

                    <?= $holiday['holiday_date'] ?>

                </div>

        <?php

            }
        } else {

            echo
            "<p>No upcoming holidays.</p>";
        }

        ?>

    </div>

    <div class="footer">

        PCL Attendance System

    </div>

</body>

</html>