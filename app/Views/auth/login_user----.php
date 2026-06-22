<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f7fb;
            padding: 15px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eef4ff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 38px;
        }

        h2 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
        }

        input {
            width: 100%;
            height: 52px;
            padding: 0 15px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        button {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        @media (max-width: 480px) {

            body {
                padding: 10px;
            }

            .login-card {
                max-width: 100%;
                min-height: 75vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 40px 25px;
            }

            .logo {
                width: 95px;
                height: 95px;
                font-size: 45px;
            }

            h2 {
                font-size: 30px;
            }

            .subtitle {
                font-size: 17px;
                margin-bottom: 30px;
            }

            label {
                font-size: 16px;
            }

            input,
            button {
                height: 58px;
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">👨</div>

        <h2>Admin Dashboard</h2>

        <p class="subtitle">Login using your registered mobile number</p>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'access_denied'): ?>
            <p style="color:red; margin-bottom:10px;">
                Access Denied. Your mobile number is not registered.
            </p>
        <?php endif; ?>

        <form
            method="POST"
            action="/pcl_attendence_sheet/public/auth/admin_otp.php">
            <input type="hidden" name="action" value="employee">
            <div class="form-group">
                <label for="phone_number"> Mobile Number </label>

                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    placeholder="Enter Mobile Number"
                    maxlength="10"
                    required />
            </div>

            <button type="submit">Send OTP</button>
        </form>
    </div>
</body>

</html>