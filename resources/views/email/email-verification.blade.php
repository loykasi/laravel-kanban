<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjectManagement</title>
</head>
<body>
    <div>
        <h3>E-mail verification.</h3>
        <p>Click link below to verify your email: {{ $user->email }}</p>
        <a href="http://127.0.0.1:8000/check-email/{{ $user->remember_token }}">Click to verify</a>
    </div>
</body>
</html>