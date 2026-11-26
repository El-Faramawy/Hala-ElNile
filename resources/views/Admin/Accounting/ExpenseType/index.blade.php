@extends('layouts.accounting.app')

@section('page_title')
    أنواع وبنود المصروفات
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px; border-radius: 12px 12px 0 0;">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="badge badge-primary p-2" style="border-radius: 8px;">
                    <i class="fe fe-layers fs-16"></i>
                </span>
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">شجرة تصنيفات وبنود المصروفات</h4>
                    <small class="text-muted">التصنيف الرئيسي (1، 2، ...) ويندرج تحته البنود الفرعية (1.1، 1.2، ...)</small>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                <input type="text" id="treeSearchInput" class="form-control form-control-sm" style="width: 210px;" placeholder="ابحث عن تصنيف أو بند...">
                <a href="{{ route('expense-categories.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
                    <i class="fe fe-folder ml-1"></i> إدارة التصنيفات الرئيسية
                </a>
                <button type="button" class="btn btn-success btn-sm font-weight-bold" id="addExpenseTypeBtn">
                    <i class="fe fe-plus ml-1"></i> إضافة بند فرعي جديد
                </button>
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            @foreach($groupedTypes['groups'] as $group)
                <div class="card border mb-3 expense-tree-group" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-header bg-light py-2 px-3 d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="badge badge-primary font-weight-bold fs-13 px-2 py-1">{{ $group['number'] }}</span>
                            <h5 class="mb-0 font-weight-bold text-dark fs-15">{{ $group['category'] }}</h5>
                            <span class="badge badge-light border text-muted">{{ count($group['items']) }} بنود</span>
                            @if(! $group['is_active'])
                                <span class="badge badge-danger">غير مفعل</span>
                            @endif
                        </div>
                        <button type="button"
                                class="btn btn-outline-primary btn-xs font-weight-bold add-sub-item-btn"
                                data-category-id="{{ $group['category_id'] }}">
                            <i class="fe fe-plus ml-1"></i> إضافة بند تحت ({{ $group['category'] }})
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($group['items'] as $entry)
                                    @php $item = $entry['model']; @endphp
                                    <tr class="expense-tree-row">
                                        <td style="width: 90px;">
                                            <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">{{ $entry['code'] }}</span>
                                        </td>
                                        <td class="font-weight-bold text-dark">{{ $item->name }}</td>
                                        <td style="width: 170px;">
                                            @if($item->is_other)
                                                <span class="badge badge-warning px-2 py-1">يتطلب تفاصيل إضافية</span>
                                            @else
                                                <span class="badge badge-light text-muted px-2 py-1">بند قياسي</span>
                                            @endif
                                        </td>
                                        <td style="width: 110px;">{!! tableStatus((bool) $item->is_active) !!}</td>
                                        <td style="width: 130px;" class="text-left">
                                            <button type="button" class="btn btn-primary btn-sm edit-type-btn" data-id="{{ $item->id }}" title="تعديل">
                                                <i class="fa fa-edit text-white"></i>
                                            </button>
                                            @if(! $item->is_other)
                                                <button type="button" class="btn btn-danger btn-sm delete-type-btn" data-id="{{ $item->id }}" title="حذف">
                                                    <i class="fa fa-trash-o text-white"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3 small">لا توجد بنود فرعية مضافة تحت هذا التصنيف بعد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @if(! empty($groupedTypes['standalone']))
                <div class="card border mb-0 expense-tree-group" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="badge badge-secondary font-weight-bold fs-13 px-2 py-1">#</span>
                            <h5 class="mb-0 font-weight-bold text-dark fs-15">بنود عامة / غير مصنفة</h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                @foreach($groupedTypes['standalone'] as $entry)
                                    @php $item = $entry['model']; @endphp
                                    <tr class="expense-tree-row">
                                        <td style="width: 90px;">
                                            <span class="badge badge-light border text-secondary font-weight-bold px-2 py-1">{{ $entry['code'] }}</span>
                                        </td>
                                        <td class="font-weight-bold text-dark">{{ $item->name }}</td>
                                        <td style="width: 170px;">
                                            @if($item->is_other)
                                                <span class="badge badge-warning px-2 py-1">يتطلب تفاصيل إضافية</span>
                                            @else
                                                <span class="badge badge-light text-muted px-2 py-1">بند قياسي</span>
                                            @endif
                                        </td>
                                        <td style="width: 110px;">{!! tableStatus((bool) $item->is_active) !!}</td>
                                        <td style="width: 130px;" class="text-left">
                                            <button type="button" class="btn btn-primary btn-sm edit-type-btn" data-id="{{ $item->id }}" title="تعديل">
                                                <i class="fa fa-edit text-white"></i>
                                            </button>
                                            @if(! $item->is_other)
                                                <button type="button" class="btn btn-danger btn-sm delete-type-btn" data-id="{{ $item->id }}" title="حذف">
                                                    <i class="fa fa-trash-o text-white"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-list.modal name="بند المصروف" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
<script>
    $(document).ready(function() {
        $('#treeSearchInput').on('input', function() {
            var query = $(this).val().trim().toLowerCase();
            $('.expense-tree-group').each(function() {
                var $group = $(this);
                var groupHeaderText = $group.find('.card-header').text().toLowerCase();
                var hasVisibleRow = false;

                $group.find('.expense-tree-row').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var matches = query === '' || rowText.indexOf(query) !== -1 || groupHeaderText.indexOf(query) !== -1;
                    $(this).toggle(matches);
                    if (matches) {
                        hasVisibleRow = true;
                    }
                });

                $group.toggle(hasVisibleRow || query === '');
            });
        });

        $('#addExpenseTypeBtn').on('click', function() {
            $('#Modal').modal('show');
            $('#form-load').load("{{ route('expense-types.create') }}");
        });

        $(document).on('click', '.add-sub-item-btn', function() {
            var categoryId = $(this).data('category-id');
            $('#Modal').modal('show');
            $('#form-load').load("{{ route('expense-types.create') }}?expense_category_id=" + encodeURIComponent(categoryId));
        });

        $(document).on('click', '.edit-type-btn', function() {
            var id = $(this).data('id');
            $('#Modal').modal('show');
            $('#form-load').load("{{ url('accounting/expense-types') }}/" + id + "/edit");
        });

        $(document).off('submit', 'form#form').on('submit', 'form#form', function(e) {
            e.preventDefault();
            var $form = $(this);
            if ($form.data('is_submitting')) return false;
            $form.data('is_submitting', true);

            var $btn = $('#submit');
            $btn.prop('disabled', true);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                headers: { 'Accept': 'application/json' },
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#Modal').modal('hide');
                    my_toaster(res.message);
                    setTimeout(function() { location.reload(); }, 400);
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'حدث خطأ أثناء الحفظ.';
                    my_toaster(msg, 'error');
                },
                complete: function() {
                    $form.data('is_submitting', false);
                    $btn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.delete-type-btn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'حذف بند المصروف؟',
                text: 'هل أنت متأكد من حذف هذا البند؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، حذف',
                cancelButtonText: 'إلغاء'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('accounting/expense-types') }}/" + id,
                        type: 'DELETE',
                        headers: { 'Accept': 'application/json' },
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(res) {
                            my_toaster(res.message || 'تم الحذف بنجاح', 'info');
                            setTimeout(function() { location.reload(); }, 400);
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'لا يمكن الحذف.';
                            my_toaster(msg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
