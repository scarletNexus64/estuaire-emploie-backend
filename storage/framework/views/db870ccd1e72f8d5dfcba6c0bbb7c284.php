<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Annulé</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .info-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }

        .info-title {
            color: #c53030;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-text {
            color: #742a2a;
            font-size: 14px;
            line-height: 1.5;
        }

        .countdown {
            color: #f5576c;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 500;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f5576c;
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
            <span class="icon">✕</span>
        </div>

        <h1>Paiement Annulé</h1>
        <p class="subtitle">Vous avez annulé le paiement.<br>Aucune somme n'a été débitée.</p>

        <div class="info-box">
            <div class="info-title">Que s'est-il passé ?</div>
            <div class="info-text">
                Vous avez choisi d'annuler le paiement PayPal. Vous pouvez réessayer à tout moment depuis votre wallet.
            </div>
        </div>

        <p class="countdown">
            <span class="spinner"></span>
            <span id="countdown-text">Fermeture automatique dans <span id="countdown">3</span>s...</span>
        </p>

        <div class="footer">
            Estuaire Emploi - Paiement sécurisé
        </div>
    </div>

    <script>
        // Countdown timer
        let countdown = 3;
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
                cancelled: true,
                <?php if($payment_id): ?>
                payment_id: <?php echo e($payment_id); ?>,
                <?php endif; ?>
                status: 'cancelled'
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
            console.log('PAYMENT_CANCELLED:', JSON.stringify(paymentData));

            // Method 4: Try to close the webview after a short delay
            setTimeout(() => {
                // This might work in some WebView implementations
                if (window.close) {
                    window.close();
                }

                // Navigate to a special URL that Flutter can intercept
                window.location.href = 'flutter://payment-cancelled';
            }, 500);
        }

        // Also send immediately on page load (backup)
        window.addEventListener('load', function() {
            setTimeout(sendMessageToFlutter, 3000);
        });
    </script>
</body>
</html>
<?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/payment/cancel.blade.php ENDPATH**/ ?>