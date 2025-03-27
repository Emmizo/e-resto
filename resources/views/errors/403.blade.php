<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fffff;
            color: red;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            padding: 20px;

        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403 | Unauthorized</h1>
        <p>{{ $exception->getMessage() ?? "You don't have permission to access this page." }}</p>
        <a href="{{ url()->previous() }}">Go back</a>
    </div>
</body>
</html>
