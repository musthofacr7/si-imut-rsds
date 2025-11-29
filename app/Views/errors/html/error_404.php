<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --text-color: #1f2937;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
        }

        .container {
            text-align: center;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 400px;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            font-size: 6rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        h2 {
            font-size: 1.5rem;
            margin: 1rem 0;
            font-weight: 600;
        }

        p {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.3);
        }

        .illustration {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration">👻</div>
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>
            <?php if (ENVIRONMENT !== 'production') : ?>
                <?= nl2br(esc($message)) ?>
            <?php else : ?>
                Oops! The page you are looking for doesn't exist or has been moved.
            <?php endif; ?>
        </p>
        <a href="http://192.168.31.47/imut-rsds/public/" class="btn">Back to Home</a>
    </div>
</body>
</html>
