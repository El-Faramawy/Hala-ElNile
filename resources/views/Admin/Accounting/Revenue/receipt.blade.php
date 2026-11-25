<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سند قبض رسمي #{{ $revenue->id }} - {{ setting()->name }}</title>
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            background: #f8fafc;
            color: #0b223d;
            margin: 0;
            padding: 30px;
        }
        .receipt-card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid #008c45;
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .receipt-title {
            text-align: center;
        }
        .receipt-title h2 {
            margin: 0;
            color: #008c45;
            font-size: 24px;
        }
        .receipt-title p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 13px;
        }
        .receipt-body {
            margin-bottom: 30px;
        }
        .receipt-row {
            display: flex;
            margin-bottom: 15px;
            font-size: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 8px;
        }
        .receipt-label {
            width: 180px;
            font-weight: bold;
            color: #475569;
        }
        .receipt-val {
            flex-grow: 1;
            font-weight: 700;
            color: #0f172a;
        }
        .amount-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-val {
            font-size: 26px;
            font-weight: 900;
            color: #065f46;
        }
        .receipt-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-line {
            margin-top: 50px;
            border-bottom: 1px solid #94a3b8;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; border: 1px solid #000; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 20px; text-align: left;">
        <button onclick="window.print()" style="background: #008c45; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">
            🖨️ طباعة السند
        </button>
        <button onclick="window.close()" style="background: #64748b; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; margin-right: 5px;">
            إغلاق
        </button>
    </div>

    <div class="receipt-card">
        <div class="receipt-header">
            <div>
                <h3 style="margin: 0; color: #0b223d;">{{ setting()->name }}</h3>
                <p style="margin: 4px 0 0; color: #d4af37; font-weight: bold; font-size: 12px;">ترخيص وزارة العمل رقم 912 شركات</p>
                <small style="color: #64748b;">هاتف: {{ setting()->phone ?? '01000000000' }}</small>
            </div>
            <div class="receipt-title">
                <h2>سند قبض نقدية</h2>
                <p>RECEIPT VOUCHER</p>
            </div>
            <div style="text-align: left;">
                <div style="font-weight: bold; font-size: 16px; color: #dc2626;">رقم: #{{ $revenue->id }}</div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;">التاريخ: {{ $revenue->transaction_date?->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="receipt-row">
                <span class="receipt-label">استلمنا من السيد/ة:</span>
                <span class="receipt-val">{{ $revenue->contractTracker?->applicant_name ?? ($revenue->supplier?->name ?? 'عميل الشركة') }}</span>
            </div>

            @if($revenue->contractTracker)
            <div class="receipt-row">
                <span class="receipt-label">كود العقد / المعاملة:</span>
                <span class="receipt-val">{{ $revenue->contractTracker->contract_code }} ({{ $revenue->contractTracker->job_title }})</span>
            </div>
            @endif

            <div class="amount-box">
                <span style="display: block; font-size: 13px; color: #047857; font-weight: bold; margin-bottom: 5px;">المبلغ المسدد:</span>
                <span class="amount-val">
                    {{ number_format((float)$revenue->amount, 2) }} {{ $revenue->received_currency === 'EUR' ? 'يورو (€)' : 'جنيه مصري' }}
                </span>
                @if($revenue->received_currency === 'EGP' && $revenue->eur_equivalent)
                <div style="font-size: 14px; color: #0f172a; font-weight: bold; margin-top: 5px;">
                    (ما يعادل: {{ number_format((float)$revenue->eur_equivalent, 2) }} يورو تُخصم من أصل العقد)
                </div>
                @endif
            </div>

            <div class="receipt-row">
                <span class="receipt-label">الخزينة المودع بها:</span>
                <span class="receipt-val">{{ $revenue->treasury?->name ?? 'الخزينة الرئيسية' }}</span>
            </div>

            @if($revenue->notes)
            <div class="receipt-row">
                <span class="receipt-label">وذلك عن (البيان):</span>
                <span class="receipt-val">{{ $revenue->notes }}</span>
            </div>
            @endif
        </div>

        <div class="receipt-footer">
            <div class="sign-box">
                <span style="font-weight: bold; color: #475569;">توقيع المستلم</span>
                <div class="sign-line"></div>
            </div>
            <div class="sign-box">
                <span style="font-weight: bold; color: #475569;">توقيع المحاسب</span>
                <div class="sign-line"></div>
            </div>
            <div class="sign-box">
                <span style="font-weight: bold; color: #475569;">ختم الشركة الرسمي</span>
                <div class="sign-line"></div>
            </div>
        </div>
    </div>
</body>
</html>
