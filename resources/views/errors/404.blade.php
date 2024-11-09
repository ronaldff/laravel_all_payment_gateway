<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .error-container {
            text-align: center;
        }
        h1 {
            font-size: 10rem;
            color: #e74c3c;
            margin: 0;
        }
        p {
            font-size: 1.5rem;
            margin-top: -20px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p>Sorry, the page you're looking for cannot be found.</p>
        <a href="{{ url('/') }}">Go to Home</a>
    </div>
</body>
</html>
