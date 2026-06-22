<?php


require_once '../../../config/database.php';

/*
|--------------------------------------------------------------------------
| Total Active Employees
|--------------------------------------------------------------------------
*/
$totalEmployees = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*)
        FROM employees
        WHERE status = 'active'
        "
    )
)[0];

/*
|--------------------------------------------------------------------------
| Present Today (Only Active Employees)
|--------------------------------------------------------------------------
*/
$presentToday = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(DISTINCT a.employee_id)
        FROM attendance a
        INNER JOIN employees e
            ON a.employee_id = e.id
        WHERE a.attendance_date = CURDATE()
        AND e.status = 'active'
        "
    )
)[0];

/*
|--------------------------------------------------------------------------
| Absent Today (Active Employees With No Attendance Today)
|--------------------------------------------------------------------------
*/
$absentToday = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*)
        FROM employees e
        WHERE e.status = 'active'
        AND e.id NOT IN (
            SELECT employee_id
            FROM attendance
            WHERE attendance_date = CURDATE()
        )
        "
    )
)[0];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f7fb;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            opacity: .9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .stat-card .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-card h2 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #64748b;
        }

        .total {
            border-top: 5px solid #2563eb;
        }

        .present {
            border-top: 5px solid #16a34a;
        }

        .absent {
            border-top: 5px solid #dc2626;
        }

        .menu-section {
            display: flex;
            gap: 15px;
        }

        .menu-btn {
            flex: 1;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 18px;
            text-align: center;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 600;
            transition: .3s;
        }

        .menu-btn:hover {
            background: #1d4ed8;
        }

        @media(max-width:768px) {

            body {
                padding: 12px;
            }

            .header {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 22px;
            }

            .stat-card h2 {
                font-size: 36px;
            }

            .menu-section {
                flex-direction: column;
            }

            .menu-btn {
                padding: 20px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-container">

        <div class="header">
            <h1>📊 Admin Dashboard</h1>
            <p>Employee Attendance Management System</p>
        </div>

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="icon">👥</div>
                <h2><?= $totalEmployees ?></h2>
                <p>Total Employees</p>
            </div>

            <div class="stat-card present">
                <div class="icon">✅</div>
                <h2><?= $presentToday ?></h2>
                <p>Present Today</p>
            </div>

            <div class="stat-card absent">
                <div class="icon">❌</div>
                <h2><?= $absentToday ?></h2>
                <p>Absent Today</p>
            </div>

        </div>

        <div class="menu-section">

            <a href="employees.php" class="menu-btn">
                👤 Manage Employees
            </a>

            <a href="attendence.php" class="menu-btn">
                📅 Attendance Report
            </a>
            <a href="reports.php" class="menu-btn">
                📅 Monthly Report
            </a>


        </div>

    </div>

</body>

</html>