<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Add Employee</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f4f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-card {
            width: 100%;
            max-width: 650px;
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header .icon {
            font-size: 50px;
            margin-bottom: 10px;
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

        input {
            width: 100%;
            height: 54px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 0 15px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
        }

        button {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        button:hover {
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
                width: 100%;
            }

            .header h1 {
                font-size: 28px;
            }

            .header p {
                font-size: 15px;
            }

            label {
                font-size: 15px;
            }

            input {
                height: 58px;
                font-size: 16px;
            }

            button {
                height: 60px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="form-card">

        <div class="header">
            <div class="icon">👨‍💼</div>

            <h1>Add Employee</h1>

            <p>
                Create a new employee record
            </p>
        </div>

        <form
            method="POST"
            action="http://localhost:8080/pcl_attendence_sheet/public/employee-store.php">

            <div class="form-group">
                <label>Employee Code</label>

                <input
                    type="text"
                    name="employee_code"
                    placeholder="Enter employee code"
                    required>
            </div>

            <div class="form-group">
                <label>Employee Name</label>

                <input
                    type="text"
                    name="employee_name"
                    placeholder="Enter employee name"
                    required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>

                <input
                    type="text"
                    name="employee_number"
                    placeholder="Enter phone number"
                    required>
            </div>

            <div class="form-group">
                <label>Department</label>

                <input
                    type="text"
                    name="department"
                    placeholder="Enter department">
            </div>

            <button type="submit">
                Save Employee
            </button>

        </form>

    </div>

</body>

</html>