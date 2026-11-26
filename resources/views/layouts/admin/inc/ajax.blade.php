@php
    $createRoute = Route::has("$url.create") ? route("$url.create") : (Route::has("accounting.$url.create") ? route("accounting.$url.create") : null);
    $destroyRoute = Route::has("$url.destroy") ? route("$url.destroy", ":id") : (Route::has("accounting.$url.destroy") ? route("accounting.$url.destroy", ":id") : null);
    $multiDeleteRoute = Route::has("$url.multiDelete") ? route("$url.multiDelete") : (Route::has("accounting.$url.multiDelete") ? route("accounting.$url.multiDelete") : null);
    $editRoute = Route::has("$url.edit") ? route("$url.edit", ":id") : (Route::has("accounting.$url.edit") ? route("accounting.$url.edit", ":id") : null);
@endphp
<script>
    var createUrlPattern = @json($createRoute);
    var destroyUrlPattern = @json($destroyRoute);
    var multiDeleteUrlPattern = @json($multiDeleteRoute);
    var editUrlPattern = @json($editRoute);

    var loader = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:30px 20px;gap:12px;">
            <img src="{{ asset('Admin/imgs/loader.webp') }}" alt="جاري التحميل" style="width:160px;max-width:90%;height:auto;display:block;border-radius:10px;box-shadow:0 8px 18px rgba(11,34,61,0.15);">
            <p style="font-family:'Cairo',sans-serif;font-size:14px;font-weight:700;color:#0b223d;margin:0;">جاري تحميل البيانات...</p>
            <div style="display:flex;gap:6px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#008c45;animation:gl-bounce 1.2s ease-in-out infinite;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:#d4af37;animation:gl-bounce 1.2s ease-in-out infinite 0.2s;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:#0b223d;animation:gl-bounce 1.2s ease-in-out infinite 0.4s;"></span>
            </div>
        </div>
    `;

    var createFormCache = null;
    var isPreloadingCreate = false;

    function preloadCreateForm() {
        if (isPreloadingCreate || !createUrlPattern) return;
        isPreloadingCreate = true;
        $.ajax({
            url: createUrlPattern,
            type: 'GET',
            success: function (html) {
                createFormCache = html;
                isPreloadingCreate = false;
            },
            error: function () {
                isPreloadingCreate = false;
            }
        });
    }

    function initModalPlugins() {
        if ($.fn.select2) {
            $('#Modal select.select2-user').each(function () {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    dropdownParent: $('#Modal'),
                    placeholder: $select.data('placeholder') || 'ابحث باسم أو هاتف العميل...',
                    allowClear: true,
                    width: '100%',
                    dir: 'rtl'
                });
            });

            $('#Modal select.select2-basic, #Modal select.select2').not('.select2-user').each(function () {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    dropdownParent: $('#Modal'),
                    width: '100%',
                    dir: 'rtl',
                    minimumResultsForSearch: 0
                });
            });
        }
        if ($.fn.tooltip) {
            $('#Modal [data-toggle="tooltip"]').tooltip();
        }
        if (typeof window.onModalLoaded === 'function') {
            window.onModalLoaded();
        }
    }

    // Prevent Bootstrap modal focus trap from blocking Select2 search field typing
    if ($.fn.modal && $.fn.modal.Constructor) {
        $.fn.modal.Constructor.prototype._enforceFocus = function () {};
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    }

    $(document).on('shown.bs.modal', '#Modal', function () {
        initModalPlugins();
    });

    $(function () {
        preloadCreateForm();
        var cleanCellText = function (data) {
            if (typeof data === 'string') {
                return $('<div>').html(data).text().replace(/\s+/g, ' ').trim();
            }
            return data;
        };

        if ($.fn.dataTable && $.fn.dataTable.ext && $.fn.dataTable.ext.buttons && $.fn.dataTable.ext.buttons.collection) {
            if (!$.fn.dataTable.ext.buttons.collection._rtlPositionPatched) {
                $.fn.dataTable.ext.buttons.collection._rtlPositionPatched = true;
                var origCollectionAction = $.fn.dataTable.ext.buttons.collection.action;
                $.fn.dataTable.ext.buttons.collection.action = function (e, dt, button, config) {
                    origCollectionAction.call(this, e, dt, button, config);

                    var reposition = function () {
                        if (!config._collection || !config._collection.length) return;
                        if (!config._collection.parent().is('body')) {
                            config._collection.appendTo(document.body);
                        }
                        var $btn = $(button);
                        if (!$btn.length) return;

                        var btnOffset = $btn.offset();
                        var btnWidth = $btn.outerWidth();
                        var btnHeight = $btn.outerHeight();
                        var colWidth = config._collection.outerWidth() || 240;
                        var winWidth = $(window).width();

                        // In RTL, align right edge of collection with right edge of button:
                        var targetLeft = (btnOffset.left + btnWidth) - colWidth;

                        // Restrict from overlapping right sidebar:
                        var $sidebar = $('.app-sidebar:visible');
                        var rightLimit = winWidth;
                        if ($sidebar.length) {
                            var sOffset = $sidebar.offset();
                            if (sOffset.left > 100 && sOffset.left < winWidth) {
                                rightLimit = sOffset.left;
                            }
                        }

                        if (targetLeft + colWidth > rightLimit - 15) {
                            targetLeft = rightLimit - 15 - colWidth;
                        }
                        if (targetLeft < 15) {
                            targetLeft = 15;
                        }

                        config._collection.css({
                            'position': 'absolute',
                            'top': Math.round(btnOffset.top + btnHeight + 4) + 'px',
                            'left': Math.round(targetLeft) + 'px',
                            'right': 'auto',
                            'z-index': '99999'
                        });
                    };

                    reposition();
                    setTimeout(reposition, 10);
                    setTimeout(reposition, 60);
                    setTimeout(reposition, 150);
                };
            }
        }

        var table = $("#exportexample").DataTable({
            bLengthChange: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
                data: function (d) {
                    if (typeof originalDataFunc === 'function') {
                        originalDataFunc(d);
                    }
                }
            },
            columns: columns,
            // order: [
            //     [0, "desc"]
            // ],
            "language": {
                paginate: {
                    previous: "<i class='fa fa-angle-right ml-1'></i> السابق",
                    next: "التالي <i class='fa fa-angle-left mr-1'></i>"
                },
                "sProcessing": '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:12px;gap:6px;"><img src="{{ asset('Admin/imgs/loader.webp') }}" style="width:110px;height:auto;border-radius:8px;"><span style="font-family:\'Cairo\',sans-serif;font-weight:700;font-size:13px;color:#0b223d;">جاري التحميل...</span></div>',
                "sLengthMenu": "اظهار _MENU_ سجل",
                "sZeroRecords": "لا يوجد نتائج",
                "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                "sInfoEmpty": "لا نتائج",
                "sInfoFiltered": "للبحث",
                "sSearch": "بحث :    ",
                "oPaginate": {
                    "sPrevious": "<i class='fa fa-angle-right ml-1'></i> السابق",
                    "sNext": "التالي <i class='fa fa-angle-left mr-1'></i>"
                },
                "buttons": {
                    "colvis": "تحديد الأعمدة",
                    "colvisRestore": "إعادة التعيين",
                    "copy": "نسخ",
                    "excel": "Excel",
                    "csv": "CSV",
                    "print": "طباعة"
                }
            },
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'colvis',
                    text: '<i class="fe fe-columns ml-1"></i>الأعمدة',
                    className: 'btn btn-outline-success',
                    columns: ':not(.no-colvis):not(:first-child):not(:last-child)'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o ml-1"></i>Excel',
                    className: 'btn btn-outline-success',
                    title: document.title,
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o ml-1"></i>CSV',
                    className: 'btn btn-outline-success',
                    bom: true,
                    charset: 'utf-8',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-copy ml-1"></i>نسخ',
                    className: 'btn btn-outline-success',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print ml-1"></i>طباعة',
                    className: 'btn btn-outline-success',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)'
                    },
                    customize: function ( win ) {
                        $(win.document.body).css( 'direction', 'rtl' );
                        $(win.document.body).css( 'font-family', 'Cairo, sans-serif' );
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' )
                            .css( 'direction', 'rtl' );
                    }
                }
            ],
            searching: true,
            ordering:false,
            destroy: true,
            info: false,
            lengthChange: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
            pageLength: 10,

        });

        window.table = table;

        table.buttons().container().appendTo( '#exportexample_wrapper .col-md-6:eq(0)' );

        // Dynamic positioning for ColVis and button collections in RTL layout
        function adjustCollectionPosition() {
            var $col = $('div.dt-button-collection:visible');
            if (!$col.length) return;
            if (!$col.parent().is('body')) {
                $col.appendTo(document.body);
            }

            var $btn = $('.buttons-collection.active, .buttons-colvis.active, .buttons-collection:focus, .buttons-colvis:focus');
            if (!$btn.length) {
                $btn = $('.buttons-colvis, .buttons-collection').last();
            }

            if ($btn.length && $btn.is(':visible')) {
                var btnOffset = $btn.offset();
                var btnWidth = $btn.outerWidth();
                var btnHeight = $btn.outerHeight();
                var colWidth = $col.outerWidth() || 240;
                var winWidth = $(window).width();

                // In RTL, align right edge of collection with right edge of button:
                var targetLeft = (btnOffset.left + btnWidth) - colWidth;

                // Restrict dropdown from overflowing into the right sidebar
                var $sidebar = $('.app-sidebar:visible');
                var rightLimit = winWidth;
                if ($sidebar.length) {
                    var sOffset = $sidebar.offset();
                    if (sOffset.left > 100 && sOffset.left < winWidth) {
                        rightLimit = sOffset.left;
                    }
                }

                if (targetLeft + colWidth > rightLimit - 10) {
                    targetLeft = rightLimit - 10 - colWidth;
                }
                if (targetLeft < 15) {
                    targetLeft = 15;
                }

                $col.css({
                    'left': Math.round(targetLeft) + 'px',
                    'top': Math.round(btnOffset.top + btnHeight + 4) + 'px',
                    'right': 'auto',
                    'position': 'absolute',
                    'z-index': '99999'
                });
            }
        }

        $(document).on('click', '.buttons-colvis, .buttons-collection', function() {
            setTimeout(adjustCollectionPosition, 10);
            setTimeout(adjustCollectionPosition, 50);
        });

        if (window.MutationObserver) {
            var dtObserver = new MutationObserver(function(mutations) {
                for (var i = 0; i < mutations.length; i++) {
                    if (mutations[i].addedNodes.length) {
                        var $col = $('div.dt-button-collection:visible');
                        if ($col.length) {
                            adjustCollectionPosition();
                            break;
                        }
                    }
                }
            });
            dtObserver.observe(document.body, { childList: true, subtree: false });
        }

        $(window).on('resize scroll', function() {
            if ($('div.dt-button-collection:visible').length) {
                adjustCollectionPosition();
            }
        });
    });

    $('#exportexample').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    });


    function handleAjaxError(data) {
        if (data.status === 500) {
            my_toaster('هناك خطأ ما', 'error');
            return;
        }
        if (data.status === 422) {
            var res = data.responseJSON || (data.responseText ? $.parseJSON(data.responseText) : {});
            var hasErrors = false;
            if (res.errors) {
                if (Array.isArray(res.errors)) {
                    $.each(res.errors, function(k, v) { my_toaster(v, 'error'); hasErrors = true; });
                } else if ($.isPlainObject(res.errors)) {
                    $.each(res.errors, function(key, val) {
                        if (Array.isArray(val)) {
                            $.each(val, function(i, msg) { my_toaster(msg, 'error'); hasErrors = true; });
                        } else if (typeof val === 'string') {
                            my_toaster(val, 'error');
                            hasErrors = true;
                        }
                    });
                }
            }
            if (!hasErrors && res.message) {
                my_toaster(res.message, 'error');
            }
        }
    }

    $(document).off('click', '#addBtn').on('click', '#addBtn', function () {
        if (!createUrlPattern) return;
        $('#Modal').modal('show');
        if (createFormCache) {
            $('#form-load').html(createFormCache);
            initModalPlugins();
        } else {
            $('#form-load').html(loader);
            $('#form-load').load(createUrlPattern, function (response) {
                createFormCache = response;
                initModalPlugins();
            });
        }
    });

    window.currentSubmitAction = 'save';
    $(document).on('click', '#submitAndPrint', function () {
        window.currentSubmitAction = 'save_and_print';
    });
    $(document).on('click', '#submit', function () {
        window.currentSubmitAction = 'save';
    });

    $(document).off('submit', 'form#form').on('submit', 'form#form', function (e) {
        e.preventDefault();
        var $form = $(this);

        if ($form.data('is_submitting')) {
            return false;
        }
        $form.data('is_submitting', true);

        var $btn = $('#submit');
        var $printBtn = $('#submitAndPrint');
        var formData = new FormData(this);
        var url = $form.attr('action');
        var submitAction = window.currentSubmitAction || 'save';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#global-loader').addClass('active');
                $btn.prop('disabled', true);
                $printBtn.prop('disabled', true);
            },
            complete: function () {
            },
            success: function (data) {
                window.setTimeout(function () {
                    $('#global-loader').removeClass('active');
                    $btn.prop('disabled', false);
                    $printBtn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    $('#Modal').modal('hide');
                    my_toaster(data.message);

                    if (submitAction === 'save_and_print' && (data.receipt_url || data.voucher_url)) {
                        window.open(data.receipt_url || data.voucher_url, '_blank');
                    }
                    window.currentSubmitAction = 'save';

                    createFormCache = null;
                    preloadCreateForm();
                    if (window.reloadAfterModalSave) {
                        window.reloadAfterModalSave = false;
                        location.reload();
                    } else {
                        $('#exportexample').DataTable().ajax.reload(null, false);
                    }
                }, 100);
            },
            error: function (data) {
                $('#global-loader').removeClass('active');
                $btn.prop('disabled', false);
                $printBtn.prop('disabled', false);
                $form.data('is_submitting', false);
                $('#form-load > .linear-background').hide(loader);
                $btn.html('حفظ');
                $('#form').show();
                handleAjaxError(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var id = $(this).data('id') || $(this).closest('.delete').data('id');
        if (!id) return;

        var swalInstance = window.Swal || window.swal;
        var executeSingleDelete = function () {
            if (!destroyUrlPattern) return;
            var url = destroyUrlPattern.replace(':id', id);
            $.ajax({
                url: url,
                type: 'DELETE',
                beforeSend: function(){
                    $('#global-loader').addClass('active');
                },
                success: function (data) {
                    window.setTimeout(function() {
                        $('#global-loader').removeClass('active');
                        my_toaster(data.message || 'تم الحذف بنجاح', 'info');
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#exportexample')) {
                            $('#exportexample').DataTable().ajax.reload(null, false);
                        }
                    }, 300);
                },
                error: function (data) {
                    $('#global-loader').removeClass('active');
                    handleAjaxError(data);
                }
            });
        };

        if (swalInstance && typeof swalInstance.fire === 'function') {
            swalInstance.fire({
                title: "هل أنت متأكد من الحذف؟",
                text: "لا يمكنك التراجع بعد إتمام العملية!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#94a3b8",
                confirmButtonText: "نعم، حذف",
                cancelButtonText: "إلغاء",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    executeSingleDelete();
                }
            });
        } else {
            if (confirm("هل أنت متأكد من الحذف؟")) {
                executeSingleDelete();
            }
        }
    });

    $(document).on('click', '#multiDeleteBtn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var allVals = [];
        $(".sub_chk:checked").each(function() {
            var val = $(this).attr('data-id') || $(this).data('id') || $(this).val();
            if (val) {
                allVals.push(val);
            }
        });

        if (allVals.length <= 0) {
            my_toaster("يجب تحديد عنصر واحد على الأقل للحذف", 'warning');
            return;
        }

        var swalInstance = window.Swal || window.swal;
        var executeBulkDelete = function () {
            if (!multiDeleteUrlPattern) return;
            var url = multiDeleteUrlPattern;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    ids: allVals,
                    id: allVals
                },
                beforeSend: function () {
                    $('#global-loader').addClass('active');
                },
                success: function (data) {
                    window.setTimeout(function () {
                        $('#global-loader').removeClass('active');
                        my_toaster(data.message || 'تم حذف العناصر المحددة بنجاح', 'info');
                        $('#master').prop('checked', false);
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#exportexample')) {
                            $('#exportexample').DataTable().ajax.reload(null, false);
                        }
                    }, 200);
                },
                error: function (data) {
                    $('#global-loader').removeClass('active');
                    handleAjaxError(data);
                }
            });
        };

        if (swalInstance && typeof swalInstance.fire === 'function') {
            swalInstance.fire({
                title: "هل أنت متأكد من الحذف الجماعي؟",
                text: "سيتم حذف " + allVals.length + " عنصر، لا يمكنك التراجع بعد ذلك!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#94a3b8",
                confirmButtonText: "نعم، حذف المحدد",
                cancelButtonText: "إلغاء",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    executeBulkDelete();
                }
            });
        } else {
            if (confirm("هل أنت متأكد من حذف " + allVals.length + " عنصر؟")) {
                executeBulkDelete();
            }
        }
    });

    $(document).off('click', '#editBtn').on('click', '#editBtn', function () {
        if (!editUrlPattern) return;
        var id = $(this).data('id');
        $('#form-load').html(loader);
        $('#Modal').modal('show');

        var url = editUrlPattern.replace(':id', id);
        $('#form-load').load(url, function () {
            initModalPlugins();
        });
    });

    $(document).on('change', 'input[type="file"]', function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $parent = $(input).closest('.form-group, .col-sm-6, .col-sm-12, div');
                var $img = $parent.find('img.preview-img, img').first();
                if ($img.length) {
                    $img.attr('src', e.target.result);
                } else {
                    $parent.append('<div class="mt-2"><img src="' + e.target.result + '" class="preview-img rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #e2e8f0;" alt="preview"></div>');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    $(document).on('click', 'table td img', function (e) {
        var parentLink = $(this).closest('a');
        if (!parentLink.length && this.src) {
            window.open(this.src, '_blank');
        }
    });

</script>
