<form action="{{ route('revenues.store') }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Treasury -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الخزينة المستلمة <span class="text-danger">*</span></label>
            <select name="treasury_id" class="form-control select2-basic" id="revenueTreasurySelect" required>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}" data-currency="{{ $treasury->currency }}">
                        {{ $treasury->name }} ({{ $treasury->currency === 'EUR' ? '€ يورو' : 'ج.م جنيه' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Received Currency -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">العملة المستلمة <span class="text-danger">*</span></label>
            <select name="received_currency" id="revenueCurrencySelect" class="form-control" required>
                <option value="EGP" selected>جنيه مصري (EGP)</option>
                <option value="EUR">يورو (€ EUR)</option>
            </select>
        </div>

        <!-- Amount -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المبلغ المستلم <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" name="amount" id="revenueAmountInput" class="form-control font-weight-bold fs-16" placeholder="0.00" required>
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold" id="amountCurrencySymbol">ج.م</span>
                </div>
            </div>
        </div>

        <!-- EUR Equivalent (for contract credit) -->
        <div class="col-md-6 mb-3" id="eurEquivalentWrapper">
            <label class="form-label font-weight-bold">ما يعادله باليورو (€) <small class="text-muted">(تُخصم من حساب العقد)</small></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="eur_equivalent" id="revenueEurEquivalentInput" class="form-control font-weight-bold text-success fs-16" placeholder="0.00">
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold">€</span>
                </div>
            </div>
            <small class="text-muted d-block mt-1">القيمة المحسوبة للمسافر باليورو لخصمها من أصل دينه.</small>
        </div>

        <!-- Source / Beneficiary Type -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">الجهة الموردة للإيراد</label>
            <div class="d-flex align-items-center mb-2" style="gap: 20px;">
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="revenue_source" value="contract" checked>
                    <span class="custom-control-label font-weight-bold">مسافر / عقد سفر</span>
                </label>
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="revenue_source" value="supplier">
                    <span class="custom-control-label font-weight-bold">مورد / وكيل</span>
                </label>
                <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="revenue_source" value="general">
                    <span class="custom-control-label font-weight-bold">إيراد عام / أخرى</span>
                </label>
            </div>
        </div>

        <!-- Contract Select -->
        <div class="col-md-12 mb-3" id="contractFieldWrap">
            <label class="form-label font-weight-bold">اختيار العقد / المسافر</label>
            <select name="contract_tracker_id" class="form-control select2-basic" id="contractSelect">
                <option value="">-- اختر المسافر أو ابحث بالكود أو الاسم --</option>
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}">
                        {{ $contract->applicant_name }} (كود: {{ $contract->contract_code }}) - إجمالي العقد: {{ number_format((float)$contract->total_price_eur, 2) }} €
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Supplier Select -->
        <div class="col-md-12 mb-3 d-none" id="supplierFieldWrap">
            <label class="form-label font-weight-bold">اختيار المورد</label>
            <select name="supplier_id" class="form-control select2-basic" id="supplierSelect">
                <option value="">-- اختر المورد --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} {{ $supplier->phone ? '('.$supplier->phone.')' : '' }}</option>
                @endforeach
            </select>
        </div>

        <!-- Transaction Date -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">تاريخ المعاملة <span class="text-danger">*</span></label>
            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Notes -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">ملاحظات وبيان السند</label>
            <input type="text" name="notes" class="form-control" placeholder="مثال: دفعة أولى كاش...">
        </div>

        <!-- Receipt Image (Optional) -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold"><i class="fa fa-camera text-primary ml-1"></i> صورة إيصال / وصل القبض <small class="text-muted">(اختياري)</small></label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
            <small class="text-muted d-block mt-1">يمكنك إرفاق صورة الإيصال البنكي أو إشعار التحويل أو الوصل الورقي الممسوح ضوئياً (أقصى حجم 5 ميجابايت).</small>
        </div>
    </div>
</form>

<script>
    (function() {
        function syncCurrency() {
            var curr = $('#revenueCurrencySelect').val();
            if (curr === 'EUR') {
                $('#amountCurrencySymbol').text('€');
                $('#eurEquivalentWrapper').addClass('d-none');
                $('#revenueEurEquivalentInput').val('');
            } else {
                $('#amountCurrencySymbol').text('ج.م');
                $('#eurEquivalentWrapper').removeClass('d-none');
            }
        }

        $('#revenueCurrencySelect').on('change', syncCurrency);

        $('#revenueTreasurySelect').on('change', function() {
            var selectedCurr = $(this).find(':selected').data('currency');
            if (selectedCurr) {
                $('#revenueCurrencySelect').val(selectedCurr).trigger('change');
            }
        });

        $('input[name="revenue_source"]').on('change', function() {
            var val = $(this).val();
            if (val === 'contract') {
                $('#contractFieldWrap').removeClass('d-none');
                $('#supplierFieldWrap').addClass('d-none');
                $('#supplierSelect').val('').trigger('change');
            } else if (val === 'supplier') {
                $('#supplierFieldWrap').removeClass('d-none');
                $('#contractFieldWrap').addClass('d-none');
                $('#contractSelect').val('').trigger('change');
            } else {
                $('#contractFieldWrap').addClass('d-none');
                $('#supplierFieldWrap').addClass('d-none');
                $('#contractSelect').val('').trigger('change');
                $('#supplierSelect').val('').trigger('change');
            }
        });

        syncCurrency();
    })();
</script>
