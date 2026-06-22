<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f7fb;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px 28px;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background: #eef4ff;
            font-size: 40px;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 28px;
            font-size: 16px;
            line-height: 1.5;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #334155;
        }

        input {
            width: 100%;
            height: 55px;
            padding: 0 15px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            font-size: 17px;
        }

        button {
            width: 100%;
            height: 55px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        button:hover {
            background: #1d4ed8;
        }

        .footer-text {
            text-align: center;
            margin-top: 18px;
            color: #94a3b8;
            font-size: 13px;
        }

        @media (max-width: 480px) {

            body {
                padding: 8px;
            }

            .login-card {
                width: 100%;
                max-width: 500px;
                min-height: 70vh;
                padding: 40px 30px;
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }

            .logo {
                width: 100px;
                height: 100px;
                font-size: 28px;
                margin-bottom: 25px;
            }

            h2 {
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 15px;
            }

            .subtitle {
                font-size: 18px;
                line-height: 1.6;
                margin-bottom: 40px;
            }

            label {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 12px;
            }

            input {
                height: 62px;
                font-size: 20px;
                padding: 0 18px;
            }

            button {
                height: 62px;
                font-size: 28px;
                font-weight: 600;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .footer-text {
                font-size: 10px;
                margin-top: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">👨‍💼</div>

        <h2>Employee Attendance</h2>

        <p class="subtitle">Login using your registered mobile number</p>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'access_denied'): ?>
            <p style="color:red; margin-bottom:10px;">
                Access Denied. Your mobile number is not registered.
            </p>
        <?php endif; ?>

        <form
            method="POST"
            action="/pcl_attendence_sheet/public/auth/send_otp.php">
            <input type="hidden" name="action" value="admin">
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
            <input
                type="hidden"
                id="device_token"
                name="device_token">

            <button type="submit">Send OTP</button>
        </form>
    </div>
</body>
<script>
    if (!localStorage.getItem('device_token')) {
        localStorage.setItem(
            'device_token',
            crypto.randomUUID()
        );
    }

    document.getElementById('device_token').value =
        localStorage.getItem('device_token');
</script>

</html>