<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto 3D</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            background: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: white;

            opacity: 1;
            transition: opacity 0.6s ease;
        }

        body.fade-out {
            opacity: 0;
        }

        .welcome {
            text-align: center;
            animation: appear 1s ease;
        }

        .logo {
            font-size: 80px;
        }

        h1 {
            margin-top: 15px;
            font-size: 40px;
            letter-spacing: 4px;
        }

        p {
            margin-top: 10px;
            color: #aaa;
        }

        @keyframes appear {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>

<div class="welcome">
    <div class="logo">🚗</div>
    <h1>FT Car</h1>
    <p>Khám phá chiếc xe của bạn</p>
</div>

<script>
    setTimeout(() => {
        document.body.classList.add('fade-out');

        setTimeout(() => {
            window.location.href = "<?php echo e(url('/home')); ?>";
        }, 600);

    }, 1500);
</script>

</body>
</html><?php /**PATH C:\laravel-projects\my-app\Web-Ban-Xe\resources\views/welcome.blade.php ENDPATH**/ ?>