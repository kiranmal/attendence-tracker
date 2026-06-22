<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-card {
            width: 400px;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .otp-card h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        .otp-card p {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .logo {
            text-align: center;
            font-size: 40px;
            margin-bottom: 15px;
        }

        .mobile-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {

            body {
                padding: 0;
                background: #f5f7fb;
            }

            .login-card {
                width: calc(100% - 24px);
                min-height: 90vh;
                max-width: none;
                margin: 12px;
                padding: 40px 28px;
                border-radius: 24px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .logo {
                width: 110px;
                height: 110px;
                font-size: 52px;
                margin: 0 auto 30px;
            }

            h2 {
                font-size: 32px;
                font-weight: 700;
                text-align: center;
                margin-bottom: 12px;
                line-height: 1.3;
            }

            .subtitle {
                font-size: 18px;
                text-align: center;
                line-height: 1.6;
                margin-bottom: 35px;
                color: #64748b;
            }

            .form-group {
                margin-bottom: 24px;
            }

            label {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 12px;
                display: block;
            }

            input {
                width: 100%;
                height: 64px;
                font-size: 18px;
                padding: 0 18px;
                border-radius: 14px;
            }

            input::placeholder {
                font-size: 17px;
            }

            button {
                width: 100%;
                height: 64px;
                font-size: 19px;
                font-weight: 700;
                border-radius: 14px;
            }

            .footer-text {
                margin-top: 30px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="otp-card">

        <div class="logo">🔐</div>

        <h2>Verify OTP</h2>

        <p class="subtitle">
            Login using your registered mobile number
        </p>

        <form
            method="POST"
            action="/pcl_attendence_sheet/public/auth/verify_otp.php"
            autocomplete="off">

            <div class="form-group">

                <label>
                    Mobile Number
                </label>

                <div class="mobile-box">
                    <?= htmlspecialchars($_GET['mobile']) ?>
                </div>

                <input
                    type="hidden"
                    name="phone_number"
                    value="<?= htmlspecialchars($_GET['mobile']) ?>">



                <div class="form-group">

                    <label>
                        OTP
                    </label>

                    <input
                        type="text"
                        name="otp"
                        placeholder="Enter OTP"
                        maxlength="6"
                        required>

                </div>

                <button type="submit">
                    Verify OTP
                </button>
            </div>
            <input
                type="hidden"
                name="action"
                value="<?= $_GET['action'] ?? '' ?>">
            <input type="hidden"
                id="device_token"
                name="device_token">



        </form>

    </div>

    <script>
        // Disable back button cache
        window.history.forward();

        // Prevent accidental form resubmission warning
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        document.getElementById('device_token').value =
            localStorage.getItem('device_token');
    </script>

</body>

</html>