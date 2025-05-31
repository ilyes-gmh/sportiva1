<?php


$message = $_SESSION['message'] ?? '';
$redirect_to = $_SESSION['redirect_to'] ?? 'index.php';

// حذف البيانات من الجلسة بعد قراءتها
unset($_SESSION['message']);
unset($_SESSION['redirect_to']);
?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .notification {
            background-color: #4caf50;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            max-width: 80%;
            animation: fadeIn 0.5s ease-in-out;
        }

        .notification.error {
            background-color: #f44336;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
</head>
<body>

<div class="notification <?php echo (strpos($message, '❌') !== false) ? 'error' : ''; ?>">
    <?php echo htmlspecialchars($message); ?><br>
    Redirection dans 3 secondes...
</div>

<script>
    setTimeout(() => {
        window.location.href = "<?php echo $redirect_to; ?>";
    }, 3000);
</script>

