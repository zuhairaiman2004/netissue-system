<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetIssue - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { margin-top: 100px; max-width: 400px; }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>NetIssue Login</h4>
        </div>
        <div class="card-body">
            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
        <div class="card-footer text-center">
            <small>Diploma IT Networking Project</small>
        </div>
    </div>
</div>

</body>
</html>