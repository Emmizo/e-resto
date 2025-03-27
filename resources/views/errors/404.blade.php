<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
            color: black;
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
        a {
            color: #61dafb;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404 | Page Not Found</h1>
        <p>The page you are looking for might have been removed or does not exist.</p>
        <a href="{{ url()->previous() }}">Go back</a>
    </div>
</body>
</html>
