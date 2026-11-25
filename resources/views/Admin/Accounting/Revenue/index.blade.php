@extends('layouts.accounting.app')

@section('page_title')
    المقبوضات وسندات القبض
@endsection

@section('content')
    <!-- Enhanced Responsive Filter Bar (Collapsible Dropdown) -->
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
        <div class="card-header bg-white py-2 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px; cursor: pointer;" data-toggle="collapse" data-target="#revenueFilterCollapse" aria-expanded="false" aria-controls="revenueFilterCollapse" id="revenueFilterCollapseHeader">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="badge badge-light border text-success p-2" style="border-radius: 8px;">
                    <i class="fe fe-sliders fs-14"></i>
                </span>
                <span class="font-weight-bold text-dark fs-14">تصفية وفلترة سندات القبض المتقدمة</span>
                <span class="badge badge-success px-2 py-1 fs-11 d-none" id="activeRevFilterBadge">فلاتر مفعّلة</span>
            </div>
            <button class="btn btn-sm btn-outline-success font-weight-bold" type="button">
                <i class="fe fe-filter ml-1"></i> خيارات الفلترة <i class="fa fa-chevron-down mr-1"></i>
            </button>
        </div>

        <div class="card-body p-3 p-md-4 collapse border-top" id="revenueFilterCollapse">
            <form id="revenueFilterForm">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-database text-info ml-1"></i> الخزينة المستلمة:
                        </label>
                        <select name="treasury_id" id="rev_filter_treasury_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الخزائن</option>
                            @foreach($treasuries as $treasury)
                                <option value="{{ $treasury->id }}">{{ $treasury->name }} ({{ $treasury->currency === 'EUR' ? '€ يورو' : 'ج.م' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-refresh-cw text-primary ml-1"></i> حالة تحويل العملة:
                        </label>
                        <select name="conversion_status" id="rev_filter_conversion_status" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الحالات</option>
                            <option value="pending">⏳ جنيه بانتظار التحويل</option>
                            <option value="converted">✅ تم تحويله ليورو</option>
                            <option value="direct_eur">💶 يورو مباشر</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-truck text-secondary ml-1"></i> المورد:
                        </label>
                        <select name="supplier_id" id="rev_filter_supplier_id" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع الموردين</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-dollar-sign text-success ml-1"></i> العملة:
                        </label>
                        <select name="received_currency" id="rev_filter_received_currency" class="form-control form-control-solid select2-filter w-100">
                            <option value="">جميع العملات</option>
                            <option value="EGP">جنيه مصري (EGP)</option>
                            <option value="EUR">يورو (€ EUR)</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-calendar text-primary ml-1"></i> من تاريخ المعاملة:
                        </label>
                        <input type="date" name="date_from" id="rev_filter_date_from" class="form-control form-control-solid">
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-calendar text-primary ml-1"></i> إلى تاريخ المعاملة:
                        </label>
                        <input type="date" name="date_to" id="rev_filter_date_to" class="form-control form-control-solid">
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                        <label class="form-label font-weight-bold fs-12 text-muted mb-1">
                            <i class="fe fe-search text-info ml-1"></i> بحث بالعميل أو الكود أو السند:
                        </label>
                        <input type="text" name="search_text" id="rev_filter_search_text" class="form-control form-control-solid" placeholder="اكتب للبحث السريع...">
                    </div>
                </div>

                <div class="row pt-2 border-top">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 mr-auto">
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <button type="button" id="filterBtn" class="btn btn-success font-weight-bold flex-fill py-2">
                                <i class="fe fe-filter ml-1"></i> تطبيق الفلتر
                            </button>
                            <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary font-weight-bold py-2 px-3" title="إعادة ضبط الفلاتر">
                                <i class="fe fe-rotate-ccw ml-1"></i> إعادة ضبط
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Card -->
    <x-list.card>
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between p-3" style="gap: 10px;">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <h3 class="card-title font-weight-bold mb-0">سجل المقبوضات وسندات القبض</h3>
                <span class="badge badge-light border text-muted px-2 py-1 fs-12" id="selectedCountBadge">المحدد للتحويل: 0</span>
            </div>
            <div class="d-flex flex-wrap" style="gap: 8px;">
                <!-- Bulk Convert Button -->
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" id="triggerConversionBtn" disabled>
                    <i class="fe fe-refresh-cw ml-1"></i> تحويل الإيرادات المحددة إلى يورو
                </button>
                <button type="button" class="btn btn-success btn-sm font-weight-bold" id="addBtn">
                    <i class="fe fe-plus ml-1"></i> قبض إيراد جديد
                </button>
                <button type="button" class="btn btn-danger btn-sm font-weight-bold" id="deleteSelected">
                    <i class="fe fe-trash-2 ml-1"></i> حذف المحدد
                </button>
            </div>
        </div>

        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">رقم السند</th>
            <th class="text-white">التاريخ</th>
            <th class="text-white">العميل / العقد</th>
            <th class="text-white">المبلغ المستلم</th>
            <th class="text-white">المقابل باليورو</th>
            <th class="text-white">الخزينة المستلمة</th>
            <th class="text-white">صورة الوصل</th>
            <th class="text-white">حالة التحويل</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <!-- Create/Edit Modal with Save & Print Button -->
    <x-list.modal name="سند القبض" :save_button="true" :save_and_print="true"></x-list.modal>

    <!-- Currency Conversion Modal -->
    <div class="modal fade" id="conversionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title font-weight-bold"><i class="fe fe-refresh-cw ml-1"></i> تحويل وتصريف نقدية إلى يورو</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="conversionForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class="fe fe-info ml-2 fs-18"></i>
                            <div>
                                سيتم جمع المبالغ بالجنيه المصري وخصمها من خزائنها الأصلية، وإيداع المبلغ الفعلي باليورو في خزينة اليورو.
                                <br><strong>معالجة الفروقات:</strong> سيتم تسجيل النقص آلياً كمصروف إداري، والزيادة كإيراد باقي تحويل.
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row text-center mb-3">
                            <div class="col-md-6 mb-2">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted font-weight-bold d-block">إجمالي الجنيه المصري المجمع:</small>
                                    <h4 class="font-weight-bold text-dark mb-0 mt-1" id="modalTotalEgp">0.00 ج.م</h4>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted font-weight-bold d-block">المفروض استلامه باليورو (€):</small>
                                    <h4 class="font-weight-bold text-primary mb-0 mt-1" id="modalExpectedEur">0.00 €</h4>
                                </div>
                            </div>
                        </div>

                        <div id="hiddenRevenueIdsContainer"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">المبلغ الفعلي المستلم باليورو (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="actual_eur" id="modalActualEur" class="form-control font-weight-bold fs-16 text-success" required placeholder="مثال: 1500.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text font-weight-bold">€</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">خزينة إيداع اليورو <span class="text-danger">*</span></label>
                                <select name="to_treasury_id" class="form-control" required>
                                    @foreach($treasuries->where('currency', 'EUR') as $eurTreasury)
                                        <option value="{{ $eurTreasury->id }}">{{ $eurTreasury->name }} (رصيدها الحالي: {{ number_format((float)$eurTreasury->balance, 2) }} €)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">تاريخ التحويل</label>
                                <input type="date" name="conversion_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">ملاحظات التحويل</label>
                                <input type="text" name="notes" class="form-control" placeholder="مثال: تحويل صرافة الأهلي...">
                            </div>
                        </div>

                        <!-- Difference Preview -->
                        <div class="p-3 rounded border mt-2 d-none" id="diffPreviewBox">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold fs-13" id="diffTitle">الفارق المحسوب:</span>
                                <span class="badge px-3 py-1 font-weight-bold fs-14" id="diffBadge">0.00 €</span>
                            </div>
                            <small class="text-muted d-block mt-1" id="diffExplanation"></small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="confirmConversionBtn">
                            <i class="fe fe-check-circle ml-1"></i> تأكيد وتنفيذ التحويل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'voucher_no', name: 'id'},
            {data: 'formatted_date', name: 'transaction_date'},
            {data: 'client_or_contract', name: 'contractTracker.applicant_name'},
            {data: 'amount_formatted', name: 'amount'},
            {data: 'eur_equivalent_formatted', name: 'eur_equivalent'},
            {data: 'treasury_name', name: 'treasury.name'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'conversion_status_badge', name: 'conversion_id', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        // Pass filter parameters to DataTable
        var originalDataFunc = function(d) {
            d.treasury_id = $('#rev_filter_treasury_id').val();
            d.conversion_status = $('#rev_filter_conversion_status').val();
            d.supplier_id = $('#rev_filter_supplier_id').val();
            d.received_currency = $('#rev_filter_received_currency').val();
            d.date_from = $('#rev_filter_date_from').val();
            d.date_to = $('#rev_filter_date_to').val();
            d.search_text = $('#rev_filter_search_text').val();
        };

        function updateActiveRevFilterBadge() {
            var activeCount = 0;
            if ($('#rev_filter_treasury_id').val()) activeCount++;
            if ($('#rev_filter_conversion_status').val()) activeCount++;
            if ($('#rev_filter_supplier_id').val()) activeCount++;
            if ($('#rev_filter_received_currency').val()) activeCount++;
            if ($('#rev_filter_date_from').val()) activeCount++;
            if ($('#rev_filter_date_to').val()) activeCount++;
            if ($('#rev_filter_search_text').val()) activeCount++;

            if (activeCount > 0) {
                $('#activeRevFilterBadge').text(activeCount + ' فلاتر نشطة').removeClass('d-none');
            } else {
                $('#activeRevFilterBadge').addClass('d-none');
            }
        }

        var revenueReloadTimer = null;
        function reloadRevenuesTable() {
            updateActiveRevFilterBadge();
            clearTimeout(revenueReloadTimer);
            revenueReloadTimer = setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#exportexample')) {
                    $('#exportexample').DataTable().ajax.reload();
                } else if (window.table && typeof window.table.draw === 'function') {
                    window.table.draw();
                }
            }, 80);
        }

        $(document).ready(function() {
            // Filter triggers
            $('#filterBtn').off('click').on('click', function(e) {
                e.preventDefault();
                reloadRevenuesTable();
            });

            $('#resetFilterBtn').off('click').on('click', function(e) {
                e.preventDefault();
                $('#revenueFilterForm')[0].reset();
                if ($.fn.select2) {
                    $('#revenueFilterForm select').val('').trigger('change.select2');
                }
                reloadRevenuesTable();
            });

            $('#revenueFilterForm input').off('keypress').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    reloadRevenuesTable();
                }
            });

            $('#revenueFilterForm select').off('change').on('change', function() {
                reloadRevenuesTable();
            });
        });

        // Track selected checkboxes for conversion
        function updateConversionSelection() {
            var selectedEgpCheckboxes = $('.convert-eligible-chk:checked');
            var count = selectedEgpCheckboxes.length;
            $('#selectedCountBadge').text('المحدد للتحويل: ' + count);

            if (count > 0) {
                $('#triggerConversionBtn').prop('disabled', false);
            } else {
                $('#triggerConversionBtn').prop('disabled', true);
            }
        }

        $(document).on('change', '.sub_chk', function() {
            updateConversionSelection();
        });

        $('#master').on('change', function() {
            setTimeout(updateConversionSelection, 50);
        });

        // Open Conversion Modal
        $('#triggerConversionBtn').on('click', function() {
            var selected = $('.convert-eligible-chk:checked');
            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى تحديد إيرادات بالجنيه المصري وغير محولة مسبقاً.'
                });
                return;
            }

            var totalEgp = 0;
            var expectedEur = 0;
            var hiddenHtml = '';

            selected.each(function() {
                var $chk = $(this);
                var id = $chk.data('id');
                var egp = parseFloat($chk.data('egp')) || 0;
                var eur = parseFloat($chk.data('eur')) || 0;

                totalEgp += egp;
                expectedEur += eur;
                hiddenHtml += '<input type="hidden" name="revenue_ids[]" value="' + id + '">';
            });

            $('#modalTotalEgp').text(totalEgp.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ج.م');
            $('#modalExpectedEur').text(expectedEur.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €');
            $('#modalActualEur').val(expectedEur.toFixed(2));
            $('#hiddenRevenueIdsContainer').html(hiddenHtml);

            calculateDifference(expectedEur, expectedEur);
            $('#conversionModal').modal('show');
        });

        $('#modalActualEur').on('input', function() {
            var expectedText = $('#modalExpectedEur').text().replace(/[^0-9.]/g, '');
            var expected = parseFloat(expectedText) || 0;
            var actual = parseFloat($(this).val()) || 0;
            calculateDifference(expected, actual);
        });

        function calculateDifference(expected, actual) {
            var diff = actual - expected;
            var $box = $('#diffPreviewBox');
            $box.removeClass('d-none');

            if (Math.abs(diff) < 0.01) {
                $('#diffTitle').text('حالة المطابقة:');
                $('#diffBadge').removeClass('badge-danger badge-success').addClass('badge-light text-muted').text('مطابق تماماً (0.00 €)');
                $('#diffExplanation').text('المبلغ المستلم يطابق القيمة المطلوبة تماماً، لا توجد فروقات.');
            } else if (diff < 0) {
                $('#diffTitle').text('يوجد نقص في المستلم (فارق تحويل):');
                $('#diffBadge').removeClass('badge-light badge-success').addClass('badge-danger').text(diff.toFixed(2) + ' €');
                $('#diffExplanation').html('<i class="fe fe-alert-circle text-danger mr-1"></i> سيتم إنشاء <strong>سند مصروفات إدارية تلقائي</strong> بقيمة <strong>' + Math.abs(diff).toFixed(2) + ' €</strong> لتسوية العجز.');
            } else {
                $('#diffTitle').text('يوجد زيادة في المستلم (باقي تحويل):');
                $('#diffBadge').removeClass('badge-light badge-danger').addClass('badge-success').text('+' + diff.toFixed(2) + ' €');
                $('#diffExplanation').html('<i class="fe fe-check-circle text-success mr-1"></i> سيتم إنشاء <strong>سند إيراد تلقائي</strong> بقيمة <strong>' + diff.toFixed(2) + ' €</strong> مع كومنت "باقي تحويل".');
            }
        }

        // Submit Conversion
        $('#conversionForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            if ($form.data('is_submitting')) {
                return false;
            }
            $form.data('is_submitting', true);

            var $btn = $('#confirmConversionBtn');
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin ml-1"></i> جاري التنفيذ...');

            $.ajax({
                url: "{{ route('conversions.store') }}",
                type: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                data: $form.serialize(),
                success: function(res) {
                    $('#conversionModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: res.message
                    });
                    if ($.fn.DataTable.isDataTable('#exportexample')) {
                        $('#exportexample').DataTable().ajax.reload(null, false);
                    } else if (window.table && typeof window.table.draw === 'function') {
                        window.table.draw();
                    }
                    $('#selectedCountBadge').text('المحدد للتحويل: 0');
                    $('#triggerConversionBtn').prop('disabled', true);
                },
                error: function(xhr) {
                    var errorMsg = 'حدث خطأ أثناء تنفيذ التحويل.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: errorMsg
                    });
                },
                complete: function() {
                    $form.data('is_submitting', false);
                    $btn.prop('disabled', false).html('<i class="fe fe-check-circle ml-1"></i> تأكيد وتنفيذ التحويل');
                }
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'revenues'])
@endpush
