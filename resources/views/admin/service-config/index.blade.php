@extends('admin.layouts.app')

@section('title', 'Configuration des Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Configuration des Services API</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Configuration Services</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title mb-0">Gestion des Credentials API</h4>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="window.location.reload()">
                                <i class="mdi mdi-refresh"></i> Recharger
                            </button>
                            <form action="{{ route('admin.service-config.clear-cache') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-cached"></i> Vider le cache
                                </button>
                            </form>
                        </div>
                    </div>

                    <ul class="nav nav-tabs nav-bordered mb-3" id="serviceConfigTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="whatsapp-tab" data-bs-toggle="tab" href="#whatsapp" role="tab" aria-controls="whatsapp" aria-selected="true">
                                <i class="mdi mdi-whatsapp"></i> WhatsApp
                                @if($whatsappConfig && $whatsappConfig->isConfigured())
                                    <span class="badge bg-success ms-1">Configuré</span>
                                @else
                                    <span class="badge bg-warning ms-1">Non configuré</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="sms-tab" data-bs-toggle="tab" href="#sms" role="tab" aria-controls="sms" aria-selected="false">
                                <i class="mdi mdi-message-text"></i> SMS (Nexah)
                                @if($nexahConfig && $nexahConfig->isConfigured())
                                    <span class="badge bg-success ms-1">Configuré</span>
                                @else
                                    <span class="badge bg-warning ms-1">Non configuré</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">
                                <i class="mdi mdi-currency-usd"></i> Paiement (FreeMoPay)
                                @if($freemopayConfig && $freemopayConfig->isConfigured())
                                    <span class="badge bg-success ms-1">Configuré</span>
                                @else
                                    <span class="badge bg-warning ms-1">Non configuré</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="paypal-tab" data-bs-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="false">
                                <i class="mdi mdi-paypal"></i> PayPal
                                @if($paypalConfig && $paypalConfig->isConfigured())
                                    <span class="badge bg-success ms-1">Configuré</span>
                                @else
                                    <span class="badge bg-warning ms-1">Non configuré</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="preferences-tab" data-bs-toggle="tab" href="#preferences" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="mdi mdi-cog"></i> Préférences
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="serviceConfigTabsContent">
                        {{-- WhatsApp Tab --}}
                        @include('admin.service-config.whatsapp', ['config' => $whatsappConfig])

                        {{-- SMS Tab --}}
                        @include('admin.service-config.nexah', ['config' => $nexahConfig])

                        {{-- Payment Tab --}}
                        @include('admin.service-config.freemopay', ['config' => $freemopayConfig])

                        {{-- PayPal Tab --}}
                        @include('admin.service-config.paypal', ['config' => $paypalConfig])

                        {{-- Preferences Tab --}}
                        @include('admin.service-config.preferences')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Test connection functions
    function testService(serviceName, url) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Test en cours...';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Erreur lors du test: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }

    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = event.target;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('mdi-eye');
            icon.classList.add('mdi-eye-off');
        } else {
            input.type = 'password';
            icon.classList.remove('mdi-eye-off');
            icon.classList.add('mdi-eye');
        }
    }

    // WhatsApp Test Modal Functions
    function openWhatsAppTestModal() {
        document.getElementById('whatsappTestModal').style.display = 'flex';
    }

    function closeWhatsAppTestModal() {
        document.getElementById('whatsappTestModal').style.display = 'none';
        document.getElementById('whatsapp_test_result').style.display = 'none';
    }

    function sendWhatsAppTest() {
        const phone = document.getElementById('test_phone_whatsapp').value;
        const otp = document.getElementById('test_otp_whatsapp').value || Math.floor(100000 + Math.random() * 900000);
        const resultDiv = document.getElementById('whatsapp_test_result');

        if (!phone) {
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = 'Veuillez entrer un numéro de téléphone';
            resultDiv.style.display = 'block';
            return;
        }

        resultDiv.className = 'alert alert-info';
        resultDiv.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...';
        resultDiv.style.display = 'block';

        fetch('{{ route("admin.service-config.send-test-whatsapp") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                phone: phone,
                otp: otp
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.className = 'alert alert-success';
                resultDiv.innerHTML = '✅ ' + data.message + '<br><small>OTP envoyé: ' + otp + '</small>';
            } else {
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = '❌ ' + data.message;
            }
            resultDiv.style.display = 'block';
        })
        .catch(error => {
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = '❌ Erreur: ' + error.message;
            resultDiv.style.display = 'block';
        });
    }

    // Nexah Test Modal Functions
    function openNexahTestModal() {
        document.getElementById('nexahTestModal').style.display = 'flex';
    }

    function closeNexahTestModal() {
        document.getElementById('nexahTestModal').style.display = 'none';
        document.getElementById('nexah_test_result').style.display = 'none';
    }

    function sendNexahTest() {
        const phone = document.getElementById('test_phone_nexah').value;
        const message = document.getElementById('test_message_nexah').value;
        const resultDiv = document.getElementById('nexah_test_result');

        if (!phone || !message) {
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = 'Veuillez remplir tous les champs';
            resultDiv.style.display = 'block';
            return;
        }

        resultDiv.className = 'alert alert-info';
        resultDiv.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...';
        resultDiv.style.display = 'block';

        fetch('{{ route("admin.service-config.send-test-nexah") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                phone: phone,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.className = 'alert alert-success';
                resultDiv.innerHTML = '✅ ' + data.message;
            } else {
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = '❌ ' + data.message;
            }
            resultDiv.style.display = 'block';
        })
        .catch(error => {
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = '❌ Erreur: ' + error.message;
            resultDiv.style.display = 'block';
        });
    }
</script>
@endpush
@endsection
