<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dota 2 - GEsports</title>
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
        }

        .content {
            text-align: center;
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem 3rem;
            border-radius: 10px;
            backdrop-filter: blur(8px);
        }

        .warning-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <video autoplay loop muted playsinline>
        <source src="{{ asset('videos/dota2-background.mp4') }}" type="video/mp4">
    </video>
    
    <div class="overlay">
        <div class="content">
            <div class="warning-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <h2>Under Construction</h2>
            <p>The Dota 2 section is currently being developed.</p>
            <a href="{{ route('lolhome') }}" class="back-button">
                <i class="bi bi-arrow-left"></i>
                Back to League of Legends
            </a>
        </div>
    </div>
</body>
</html> 