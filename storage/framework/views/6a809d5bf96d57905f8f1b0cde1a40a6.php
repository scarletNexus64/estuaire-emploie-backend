<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur de Paiement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .icon {
            font-size: 48px;
            color: white;
        }

        h1 {
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .error-box {
            background: #fffaf0;
            border: 1px solid #fbd38d;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }

        .error-title {
            color: #c05621;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .error-text {
            color: #7c2d12;
            font-size: 14px;
            line-height: 1.5;
        }

        .help-text {
            background: #f7fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
        }

        .help-text p {
            color: #4a5568;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .help-text p:last-child {
            margin-bottom: 0;
        }

        .countdown {
            color: #fa709a;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 500;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #fa709a;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #a0aec0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle">
            <span class="icon">⚠</span>
        </div>

        <h1><?php echo e($message); ?></h1>
        <p class="subtitle">Une erreur s'est produite lors du traitement de votre paiement.</p>

        <div class="error-box">
            <div class="error-title">Détails de l'erreur</div>
            <div class="error-text"><?php echo e($details); ?></div>
        </div>

        <div class="help-text">
            <p><strong>💡 Que faire ?</strong></p>
            <p>• Vérifiez votre connexion internet</p>
            <p>• Réessayez le paiement</p>
            <p>• Contactez le support si le problème persiste</p>
        </div>

        <p class="countdown">
            <span class="spinner"></span>
            <span id="countdown-text">Fermeture automatique dans <span id="countdown">4</span>s...</span>
        </p>

        <div class="footer">
            Estuaire Emploi - Paiement sécurisé
        </div>
    </div>

    <script>
        // Countdown timer
        let countdown = 4;
        const countdownElement = document.getElementById('countdown');
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown-text').innerHTML = '<span class="spinner"></span>Redirection...';

                // Send message to Flutter WebView
                sendMessageToFlutter();
            }
        }, 1000);

        function sendMessageToFlutter() {
            const paymentData = {
                success: false,
                error: true,
                message: '<?php echo e($message); ?>',
                details: '<?php echo e($details); ?>',
                status: 'error'
            };

            // Try multiple methods to communicate with Flutter

            // Method 1: Flutter WebView JavaScriptChannel
            if (window.FlutterPayment) {
                window.FlutterPayment.postMessage(JSON.stringify(paymentData));
            }

            // Method 2: Alternative channel name
            if (window.PaymentResult) {
                window.PaymentResult.postMessage(JSON.stringify(paymentData));
            }

            // Method 3: Console log (for debugging)
            console.log('PAYMENT_ERROR:', JSON.stringify(paymentData));

            // Method 4: Try to close the webview after a short delay
            setTimeout(() => {
                // This might work in some WebView implementations
                if (window.close) {
                    window.close();
                }

                // Navigate to a special URL that Flutter can intercept
                window.location.href = 'flutter://payment-error';
            }, 500);
        }

        // Also send immediately on page load (backup)
        window.addEventListener('load', function() {
            setTimeout(sendMessageToFlutter, 4000);
        });
    </script>
</body>
</html>
<?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/payment/error.blade.php ENDPATH**/ ?>