<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <p>To reset your password, click the following link or button:</p>
        <a href="http://127.0.0.1:8000/change-password?token={{ $token }}" class="btn">Reset Password</a>
        <p>If the button does not work, you can also try this link: <a href="http://127.0.0.1:8000/change-password?token={{ $token }}">http://127.0.0.1:8000/change-password?token={{ $token }}</a></p>
        <p>If you did not request a password reset, no further action is required.</p>
    </div>
</body>
</html>
