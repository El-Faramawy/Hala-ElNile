@extends('layouts.accounting.app')

@section('page_title')
    كشف الحساب المالي للمسافر: {{ $contractTracker->applicant_name }}
@endsection

@section('content')
@php
    $totalPriceEur = (float) $contractTracker->total_price_eur;
    $collectedEur = (float) $contractTracker->revenues->sum('eur_equivalent');
    $remainingEur = max(0.0, $totalPriceEur - $collectedEur);
    $percent = $totalPriceEur > 0 ? min(100, (int) round(($collectedEur / $totalPriceEur) * 100)) : 0;
    $totalExpenses = (float) $contractTracker->expenses->sum('amount');
@endphp

<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('accounting.contracts.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
            <i class="fe fe-arrow-right ml-1"></i> العودة لقائمة العقود
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm font-weight-bold">
            <i class="fe fe-printer ml-1"></i> طباعة كشف الحساب
        </button>
    </div>

    <!-- Contract Financial Summary Card -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-white mb-0">
                    <i class="fe fe-user ml-1"></i> بيانات العقد: {{ $contractTracker->applicant_name }} (كود: {{ $contractTracker->contract_code }})
                </h3>
                @php
                    $cityName = $contractTracker->city_name ?? 'إيطاليا';
                    $jobTitle = $contractTracker->job_title ?? '';
                    $jobDisplay = ($cityName !== '' && str_contains($jobTitle, $cityName))
                        ? $jobTitle
                        : trim($jobTitle.' - '.$cityName, ' -');
                @endphp
                <span class="badge badge-light text-primary font-weight-bold px-3 py-1 fs-13">
                    {{ $jobDisplay }}
                </span>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">قيمة العقد المتفق عليها:</small>
                            <h3 class="font-weight-bold text-dark mb-1 mt-1">{{ number_format($totalPriceEur, 2) }} €</h3>
                            <button type="button" class="btn btn-xs btn-outline-warning font-weight-bold mt-1" data-toggle="modal" data-target="#editLedgerPriceModal">
                                <i class="fa fa-edit ml-1"></i> تعديل السعر
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">إجمالي المسدد (ما يعادله باليورو):</small>
                            <h3 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($collectedEur, 2) }} €</h3>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">المتبقي المطلوب تحصيله:</small>
                            <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ number_format($remainingEur, 2) }} €</h3>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="p-3 bg-light rounded border h-100">
                            <small class="text-muted font-weight-bold d-block">نسبة الإنجاز المالي:</small>
                            <h3 class="font-weight-bold text-primary mb-0 mt-1">{{ $percent }}%</h3>
                        </div>
                    </div>
                </div>

                <div class="progress mt-2" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : ($percent >= 50 ? 'bg-info' : 'bg-warning') }}" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs card-header-tabs" id="contractTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="installments-tab" data-toggle="tab" href="#installmentsPane" role="tab">
                            <i class="fe fe-calendar ml-1 text-primary"></i> الدفعات المجدولة ({{ $contractTracker->installments->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="revenues-tab" data-toggle="tab" href="#revenuesPane" role="tab">
                            <i class="fe fe-download ml-1 text-success"></i> سندات القبض المحصلة ({{ $contractTracker->revenues->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="expenses-tab" data-toggle="tab" href="#expensesPane" role="tab">
                            <i class="fe fe-upload ml-1 text-danger"></i> المصاريف المباشرة على العقد ({{ $contractTracker->expenses->count() }})
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="contractTabsContent">
                    <!-- Tab 1: Installments -->
                    <div class="tab-pane fade show active" id="installmentsPane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold text-dark mb-0">جدول استحقاق الدفعات</h5>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#addInstallmentModal">
                                <i class="fe fe-plus ml-1"></i> جدولة دفعة جديدة
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>عنوان الدفعة</th>
                                        <th>القيمة باليورو (€)</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>تاريخ السداد الفعلي</th>
                                        <th>الحالة</th>
                                        <th>ملاحظات</th>
                                        <th>تحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->installments as $installment)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $installment->title }}</td>
                                        <td class="font-weight-bold text-primary fs-14">{{ number_format((float)$installment->amount_eur, 2) }} €</td>
                                        <td>{{ $installment->due_date?->format('Y-m-d') }}</td>
                                        <td>{{ $installment->paid_at?->format('Y-m-d') ?? '—' }}</td>
                                        <td>
                                            @if($installment->status === 'paid')
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> تم السداد</span>
                                            @elseif(\Carbon\Carbon::parse($installment->due_date)->isPast())
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-clock-o mr-1"></i> متأخر</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-hourglass-half mr-1"></i> ساري</span>
                                            @endif
                                        </td>
                                        <td>{{ $installment->notes ?? '—' }}</td>
                                        <td>
                                            @if($installment->status !== 'paid')
                                                <button type="button" class="btn btn-sm btn-success mark-paid-btn" data-id="{{ $installment->id }}" title="تأكيد السداد">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete-installment-btn" data-id="{{ $installment->id }}" title="حذف">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted p-4">لا توجد دفعات مجدولة حتى الآن لهذا العقد. يمكنك إضافة دفعة عبر الزر أعلاه.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Revenues -->
                    <div class="tab-pane fade" id="revenuesPane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th># السند</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ المسدد</th>
                                        <th>ما يعادله باليورو (€)</th>
                                        <th>الخزينة المودع بها</th>
                                        <th>البيان والملاحظات</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->revenues as $revenue)
                                    <tr>
                                        <td><span class="badge badge-default">#{{ $revenue->id }}</span></td>
                                        <td>{{ $revenue->transaction_date?->format('Y-m-d') }}</td>
                                        <td class="font-weight-bold {{ $revenue->received_currency === 'EUR' ? 'text-primary' : 'text-success' }}">
                                            {{ number_format((float)$revenue->amount, 2) }} {{ $revenue->received_currency === 'EUR' ? '€' : 'ج.م' }}
                                        </td>
                                        <td class="font-weight-bold text-dark">{{ number_format((float)$revenue->eur_equivalent, 2) }} €</td>
                                        <td><span class="badge badge-info">{{ $revenue->treasury?->name }}</span></td>
                                        <td>{{ $revenue->notes ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('revenues.receipt', $revenue->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-print mr-1"></i> سند القبض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted p-4">لم يتم تسجيل أي سندات قبض لهذا العقد حتى الآن.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Direct Expenses -->
                    <div class="tab-pane fade" id="expensesPane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th># السند</th>
                                        <th>التاريخ</th>
                                        <th>بند المصروف</th>
                                        <th>المبلغ المنصرف</th>
                                        <th>الخزينة المنصرف منها</th>
                                        <th>التفاصيل والملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractTracker->expenses as $expense)
                                    <tr>
                                        <td><span class="badge badge-default">#{{ $expense->id }}</span></td>
                                        <td>{{ $expense->transaction_date?->format('Y-m-d') }}</td>
                                        <td>{{ $expense->expenseType?->name }}</td>
                                        <td class="font-weight-bold text-danger">{{ number_format((float)$expense->amount, 2) }} {{ $expense->currency === 'EUR' ? '€' : 'ج.م' }}</td>
                                        <td><span class="badge badge-info">{{ $expense->treasury?->name }}</span></td>
                                        <td>{{ $expense->other_details ?? ($expense->notes ?? '—') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted p-4">لا توجد مصاريف مباشرة مسجلة على هذا العقد.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Installment Modal -->
<div class="modal fade" id="addInstallmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">جدولة دفعة جديدة للعقد</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addInstallmentForm">
                @csrf
                <input type="hidden" name="contract_tracker_id" value="{{ $contractTracker->id }}">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">مسمى / عنوان الدفعة <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="مثال: دفعة صدور النولا أوستا">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">قيمة الدفعة باليورو (€) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0.01" name="amount_eur" class="form-control font-weight-bold text-primary fs-16" required placeholder="مثال: 500.00">
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">حالة الدفعة <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="pending" selected>بانتظار الاستحقاق (قيد الانتظار)</option>
                            <option value="paid">مسددة بالفعل</option>
                            <option value="overdue">متأخرة</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">ملاحظات</label>
                        <input type="text" name="notes" class="form-control" placeholder="ملاحظات إضافية...">
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="saveInstallmentBtn">حفظ وجدولة الدفعة</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Contract Price Modal -->
    <div class="modal fade" id="editLedgerPriceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">تعديل قيمة العقد باليورو (€)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="editLedgerPriceForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold">إجمالي قيمة العقد باليورو (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="total_price_eur" class="form-control font-weight-bold fs-16 text-primary" value="{{ $totalPriceEur }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="saveLedgerPriceBtn">حفظ السعر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_js')
<script>
    $('#addInstallmentForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveInstallmentBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('accounting.installments.store') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#addInstallmentModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: res.message
                }).then(function() {
                    location.reload();
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء حفظ الدفعة.';
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: msg
                });
            }
        });
    });

    $(document).on('click', '.mark-paid-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'هل تم سداد هذه الدفعة بالفعل؟',
            text: 'سيتم تغيير حالة الدفعة إلى مسدد وتسجيل تاريخ اليوم.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، مسددة',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('accounting/overdue') }}/" + id + "/mark-paid",
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        location.reload();
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete-installment-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'حذف الدفعة المجدولة؟',
            text: 'لن تتمكن من استرجاعها بعد الحذف.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، حذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('accounting/contracts/installments') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        location.reload();
                    }
                });
            }
        });
    });

    $('#editLedgerPriceForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveLedgerPriceBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('accounting.contracts.updatePrice', $contractTracker->id) }}",
            type: 'POST',
            headers: { 'Accept': 'application/json' },
            data: $(this).serialize(),
            success: function(res) {
                $('#editLedgerPriceModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'تم التحديث', text: res.message }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء التحديث.';
                Swal.fire({ icon: 'error', title: 'خطأ', text: msg });
            }
        });
    });
</script>
@endpush
