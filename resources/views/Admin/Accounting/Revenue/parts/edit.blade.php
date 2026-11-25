<form action="{{ route('revenues.update', $revenue->id) }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Treasury -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">الخزينة المستلمة <span class="text-danger">*</span></label>
            <select name="treasury_id" class="form-control select2-basic" id="editRevenueTreasurySelect" required>
                @foreach($treasuries as $treasury)
                    <option value="{{ $treasury->id }}" data-currency="{{ $treasury->currency }}" {{ $revenue->treasury_id == $treasury->id ? 'selected' : '' }}>
                        {{ $treasury->name }} ({{ $treasury->currency === 'EUR' ? '€ يورو' : 'ج.م جنيه' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Received Currency -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">العملة المستلمة <span class="text-danger">*</span></label>
            <select name="received_currency" id="editRevenueCurrencySelect" class="form-control" required>
                <option value="EGP" {{ $revenue->received_currency === 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                <option value="EUR" {{ $revenue->received_currency === 'EUR' ? 'selected' : '' }}>يورو (€ EUR)</option>
            </select>
        </div>

        <!-- Amount -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">المبلغ المستلم <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" name="amount" id="editRevenueAmountInput" class="form-control font-weight-bold fs-16" value="{{ $revenue->amount }}" required>
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold" id="editAmountCurrencySymbol">{{ $revenue->received_currency === 'EUR' ? '€' : 'ج.م' }}</span>
                </div>
            </div>
        </div>

        <!-- EUR Equivalent -->
        <div class="col-md-6 mb-3 {{ $revenue->received_currency === 'EUR' ? 'd-none' : '' }}" id="editEurEquivalentWrapper">
            <label class="form-label font-weight-bold">ما يعادله باليورو (€) <small class="text-muted">(تُخصم من حساب العقد)</small></label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="eur_equivalent" id="editRevenueEurEquivalentInput" class="form-control font-weight-bold text-success fs-16" value="{{ $revenue->eur_equivalent }}">
                <div class="input-group-append">
                    <span class="input-group-text font-weight-bold">€</span>
                </div>
            </div>
        </div>

        <!-- Contract Select -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اختيار العقد / المسافر (اختياري)</label>
            <select name="contract_tracker_id" class="form-control select2-basic">
                <option value="">-- بدون ربط بعقد --</option>
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}" {{ $revenue->contract_tracker_id == $contract->id ? 'selected' : '' }}>
                        {{ $contract->applicant_name }} (كود: {{ $contract->contract_code }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Supplier Select -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">اختيار المورد (اختياري)</label>
            <select name="supplier_id" class="form-control select2-basic">
                <option value="">-- بدون ربط بمورد --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $revenue->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Transaction Date -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">تاريخ المعاملة <span class="text-danger">*</span></label>
            <input type="date" name="transaction_date" class="form-control" value="{{ $revenue->transaction_date?->format('Y-m-d') }}" required>
        </div>

        <!-- Notes -->
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">ملاحظات وبيان السند</label>
            <input type="text" name="notes" class="form-control" value="{{ $revenue->notes }}">
        </div>

        <!-- Receipt Image (Optional) -->
        <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold"><i class="fa fa-camera text-primary ml-1"></i> صورة إيصال / وصل القبض <small class="text-muted">(اختياري - اترك فارغاً للإبقاء على الحالي)</small></label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
            @if($revenue->image)
                <div class="mt-2 d-flex align-items-center" style="gap: 12px;">
                    <img src="{{ asset('storage/'.$revenue->image) }}" class="rounded border shadow-sm" style="max-height: 60px; max-width: 90px; object-fit: cover;" alt="وصل #{{ $revenue->id }}">
                    <a href="{{ asset('storage/'.$revenue->image) }}" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
                        <i class="fa fa-external-link ml-1"></i> عرض الإيصال الحالي
                    </a>
                </div>
            @endif
        </div>
    </div>
</form>

<script>
    (function() {
        $('#editRevenueCurrencySelect').on('change', function() {
            var curr = $(this).val();
            if (curr === 'EUR') {
                $('#editAmountCurrencySymbol').text('€');
                $('#editEurEquivalentWrapper').addClass('d-none');
                $('#editRevenueEurEquivalentInput').val('');
            } else {
                $('#editAmountCurrencySymbol').text('ج.م');
                $('#editEurEquivalentWrapper').removeClass('d-none');
            }
        });

        $('#editRevenueTreasurySelect').on('change', function() {
            var selectedCurr = $(this).find(':selected').data('currency');
            if (selectedCurr) {
                $('#editRevenueCurrencySelect').val(selectedCurr).trigger('change');
            }
        });
    })();
</script>
