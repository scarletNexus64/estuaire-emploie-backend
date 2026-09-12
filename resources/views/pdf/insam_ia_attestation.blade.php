<!DOCTYPE html>
<html lang="{{ $locale ?? 'fr' }}">
<head>
    <meta charset="utf-8">
    <style>
        /* DejaVu Sans est la seule police embarquée par dompdf qui rende
           correctement les accents : on l'impose partout. */
        * { font-family: DejaVu Sans, sans-serif; }

        @page { margin: 0; size: A4 landscape; }

        body { margin: 0; padding: 0; color: #212121; font-size: 12px; }

        /* Cadre décoratif : deux bordures imbriquées, sans image de fond
           (dompdf rend mal les fonds répétés). */
        /* A4 paysage = 210 mm de haut ; le cadre occupe la page entière pour
           que l'attestation ne flotte pas en haut du document. */
        .frame {
            margin: 10mm;
            border: 3px solid #059669;
            padding: 5px;
            height: 178mm;
        }
        .inner {
            border: 1px solid #A7D7C5;
            padding: 12mm 16mm;
            height: 152mm;
        }

        .brand { width: 100%; }
        .brand td { vertical-align: middle; }
        .brand .logo { width: 74px; }
        .brand .logo img { width: 64px; }
        .brand .name { font-size: 17px; font-weight: bold; color: #059669; }
        .brand .tagline { font-size: 10px; color: #757575; margin-top: 1px; }
        .brand .ref { text-align: right; font-size: 10px; color: #757575; line-height: 1.5; }

        .title {
            text-align: center;
            font-size: 27px;
            font-weight: bold;
            color: #212121;
            letter-spacing: 1.2px;
            margin-top: 26px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #757575;
            text-transform: uppercase;
            letter-spacing: 2.2px;
            margin-top: 5px;
        }
        .rule {
            width: 92px;
            height: 3px;
            background: #059669;
            margin: 12px auto 0;
        }

        .statement {
            text-align: center;
            font-size: 12px;
            color: #616161;
            margin-top: 28px;
        }
        .holder {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            color: #059669;
            margin-top: 7px;
        }
        .course {
            text-align: center;
            font-size: 13px;
            margin-top: 12px;
            line-height: 1.6;
        }
        .course strong { color: #212121; }

        .scores { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .scores td {
            width: 33.33%;
            text-align: center;
            padding: 11px 8px;
            border: 1px solid #E0E0E0;
        }
        .scores .label {
            color: #757575;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }
        .scores .value {
            font-size: 19px;
            font-weight: bold;
            color: #059669;
            margin-top: 3px;
        }

        .footer { width: 100%; margin-top: 34px; }
        .footer td { vertical-align: bottom; font-size: 10px; color: #757575; }
        .footer .sign { text-align: right; }
        .footer .sign .line {
            border-top: 1px solid #9E9E9E;
            width: 176px;
            margin-left: auto;
            padding-top: 4px;
            color: #616161;
        }
        .verify { margin-top: 12px; font-size: 9px; color: #9E9E9E; text-align: center; }
    </style>
</head>
<body>
<div class="frame">
    <div class="inner">

        <table class="brand">
            <tr>
                @if (!empty($logo))
                    {{-- Image transmise en data-URI : dompdf ne résout pas les chemins distants. --}}
                    <td class="logo"><img src="{{ $logo }}" alt=""></td>
                @endif
                <td>
                    <div class="name">Estuaire Emploi</div>
                    <div class="tagline">{{ __('insam_ia.attestation.tagline') }}</div>
                </td>
                <td class="ref">
                    {{ __('insam_ia.attestation.reference') }} : <strong>{{ $attestation->reference }}</strong><br>
                    {{ __('insam_ia.attestation.issued_on') }} : {{ $issuedAt }}
                </td>
            </tr>
        </table>

        <div class="title">{{ __('insam_ia.attestation.title') }}</div>
        <div class="subtitle">{{ __('insam_ia.attestation.subtitle') }}</div>
        <div class="rule"></div>

        <div class="statement">{{ __('insam_ia.attestation.awarded_to') }}</div>
        <div class="holder">{{ $holderName }}</div>

        <div class="course">
            {!! __('insam_ia.attestation.statement', [
                'course' => '<strong>' . e($attestation->title) . '</strong>',
            ]) !!}
            @if ($attestation->specialite)
                <br>{{ __('insam_ia.attestation.specialty') }} : <strong>{{ $attestation->specialite }}</strong>
            @endif
        </div>

        <table class="scores">
            <tr>
                <td>
                    <div class="label">{{ __('insam_ia.attestation.score') }}</div>
                    <div class="value">{{ $attestation->score }} / {{ $attestation->total }}</div>
                </td>
                <td>
                    <div class="label">{{ __('insam_ia.attestation.percentage') }}</div>
                    <div class="value">{{ $attestation->percentage }} %</div>
                </td>
                <td>
                    <div class="label">{{ __('insam_ia.attestation.mention') }}</div>
                    <div class="value">{{ $mentionLabel }}</div>
                </td>
            </tr>
        </table>

        <table class="footer">
            <tr>
                <td>
                    {{ __('insam_ia.attestation.partner') }}<br>
                    <strong>INSAM-IA</strong>
                </td>
                <td class="sign">
                    <div class="line">{{ __('insam_ia.attestation.signatory') }}</div>
                </td>
            </tr>
        </table>

        <div class="verify">
            {{ __('insam_ia.attestation.verify', ['reference' => $attestation->reference]) }}
        </div>

    </div>
</div>
</body>
</html>
