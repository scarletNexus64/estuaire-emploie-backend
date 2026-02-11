<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .checkmark-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .checkmark {
            width: 40px;
            height: 40px;
            stroke: white;
            stroke-width: 3;
            fill: none;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawCheck 0.8s ease-out 0.5s forwards;
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
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
        }

        .amount-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .amount-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .amount {
            color: white;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .payment-details {
            background: #f7fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #718096;
            font-size: 14px;
        }

        .detail-value {
            color: #2d3748;
            font-size: 14px;
            font-weight: 600;
        }

        .countdown {
            color: #667eea;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 500;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #667eea;
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
        <div class="checkmark-circle">
            <svg class="checkmark" viewBox="0 0 52 52">
                <path d="M14 27l7.5 7.5L38 18" />
            </svg>
        </div>

        <h1>Paiement Réussi!</h1>
        <p class="subtitle">Votre wallet a été rechargé avec succès</p>

        <div class="amount-box">
            <div class="amount-label">Montant Rechargé</div>
            <div class="amount">{{ $amount }} {{ $currency }}</div>
        </div>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Transaction #</span>
                <span class="detail-value">{{ $payment->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Méthode</span>
                <span class="detail-value">PayPal</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Statut</span>
                <span class="detail-value" style="color: #48bb78;">✓ Complété</span>
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
                success: true,
                payment_id: {{ $payment->id }},
                amount: {{ $payment->amount }},
                currency: '{{ $currency }}',
                status: 'completed'
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
            console.log('PAYMENT_SUCCESS:', JSON.stringify(paymentData));

            // Method 4: Try to close the webview after a short delay
            setTimeout(() => {
                // This might work in some WebView implementations
                if (window.close) {
                    window.close();
                }

                // Navigate to a special URL that Flutter can intercept
                window.location.href = 'flutter://payment-success?payment_id={{ $payment->id }}&amount={{ $payment->amount }}';
            }, 500);
        }

        // Also send immediately on page load (backup)
        window.addEventListener('load', function() {
            setTimeout(sendMessageToFlutter, 3000);
        });
    </script>
</body>
</html>
