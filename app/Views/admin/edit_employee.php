<?php

require_once '../../../config/database.php';

$id = (int)$_GET['id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM employees WHERE id = $id"
);

$employee = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Employee</title>

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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-card {
            width: 100%;
            max-width: 700px;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1e293b;
            margin-bottom: 8px;
        }

        .header p {
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            height: 52px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 0 15px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .1);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            text-decoration: none;
            text-align: center;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-back {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-update {
            background: #2563eb;
            color: white;
        }

        .btn-update:hover {
            background: #1d4ed8;
        }

        @media (max-width: 768px) {

            body {
                padding: 12px;
                align-items: flex-start;
            }

            .form-card {
                padding: 25px;
                border-radius: 16px;
            }

            .header h1 {
                font-size: 26px;
            }

            label {
                font-size: 15px;
            }

            input,
            select {
                height: 56px;
                font-size: 16px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                padding: 16px;
                font-size: 16px;
            }
        }
    </style>

</head>

<body>

    <div class="form-card">

        <div class="header">
            <h1>✏️ Edit Employee</h1>
            <p>Update employee details below</p>
        </div>

        <form
            method="POST"
            action="/pcl_attendence_sheet/public/admin/update_employee.php">

            <input
                type="hidden"
                name="id"
                value="<?= $employee['id'] ?>">

            <div class="form-group">
                <label>Employee Code</label>

                <input
                    type="text"
                    name="employee_code"
                    value="<?= htmlspecialchars($employee['employee_code']) ?>">
            </div>

            <div class="form-group">
                <label>Employee Name</label>

                <input
                    type="text"
                    name="employee_name"
                    value="<?= htmlspecialchars($employee['employee_name']) ?>">
            </div>

            <div class="form-group">
                <label>Phone Number</label>

                <input
                    type="text"
                    name="phone_number"
                    value="<?= htmlspecialchars($employee['phone_number']) ?>">
            </div>

            <div class="form-group">
                <label>Department</label>

                <input
                    type="text"
                    name="department"
                    value="<?= htmlspecialchars($employee['department']) ?>">
            </div>

            <div class="form-group">
                <label>Status</label>

                <select name="status">

                    <option
                        value="active"
                        <?= $employee['status'] == 'active' ? 'selected' : '' ?>>
                        Active
                    </option>

                    <option
                        value="inactive"
                        <?= $employee['status'] == 'inactive' ? 'selected' : '' ?>>
                        Inactive
                    </option>

                </select>
            </div>

            <div class="button-group">

                <a
                    href="employees.php"
                    class="btn btn-back">
                    ← Back
                </a>

                <button
                    type="submit"
                    class="btn btn-update">
                    Update Employee
                </button>

            </div>

        </form>

    </div>

</body>

</html>