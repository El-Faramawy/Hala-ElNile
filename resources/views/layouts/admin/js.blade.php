<!-- JQUERY JS -->
<script src="{{url('Admin')}}/assets/js/jquery-3.4.1.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/popper.min.js"></script>

<!-- EVA-ICONS JS -->
<script src="{{url('Admin')}}/assets/iconfonts/eva.min.js"></script>

<!-- INPUT MASK JS -->
<script src="{{url('Admin')}}/assets/plugins/input-mask/jquery.mask.min.js"></script>

<!-- SIDE-MENU JS -->
<script src="{{url('Admin')}}/assets/plugins/sidemenu/sidemenu.js"></script>

<!-- PERFECT SCROLLBAR JS -->
<script src="{{url('Admin')}}/assets/plugins/p-scroll/perfect-scrollbar.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/sidemenu/sidemenu-scroll-rtl.js"></script>

<!-- CUSTOM SCROLLBAR JS -->
<script src="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- CHART LIBRARIES -->
<script src="{{url('Admin')}}/assets/plugins/chart/Chart.bundle.js"></script>
<script src="{{url('Admin')}}/assets/plugins/chart/utils.js"></script>
<script src="{{url('Admin')}}/assets/js/apexcharts.js"></script>

<!-- SIDEBAR JS -->
<script src="{{url('Admin')}}/assets/plugins/sidebar/sidebar-rtl.js"></script>

<!-- CUSTOM THEME JS -->
<script src="{{url('Admin')}}/assets/js/custom.js"></script>

<!-- SWEETALERT2 -->
<script src="{{url('Admin')}}/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    if (typeof window.Swal === 'undefined' && typeof window.sweetAlert !== 'undefined') {
        window.Swal = window.sweetAlert;
    }
    if (typeof window.swal === 'undefined' && typeof window.Swal !== 'undefined') {
        window.swal = window.Swal;
    }
</script>

<!-- TOASTIFY -->
<script type="text/javascript" src="{{url('Admin')}}/assets/plugins/toastify/toastify.js"></script>
@include('layouts.admin.inc.toaster')

<!-- INTERNAL DATA TABLE JS -->
<script src="{{url('Admin')}}/assets/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/dataTables.buttons.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/jszip.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/pdfmake.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/vfs_fonts.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.html5.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.print.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.colVis.min.js"></script>

<!-- SELECT2 JS -->
<script src="{{url('Admin')}}/assets/plugins/select2/select2.full.min.js"></script>
<script>
    function initFilterSelect2(scope) {
        if (!$.fn.select2) return;
        $(scope || document).find('select.select2-filter').each(function () {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
            $select.select2({
                width: '100%',
                dir: 'rtl'
            });
            syncFilterHighlight($select);
        });
    }

    function syncFilterHighlight($select) {
        var val = $select.val();
        var isActive = val !== null && val !== undefined && String(val).trim() !== '';
        $select.toggleClass('filter-select-active', isActive);
        var $s2 = $select.next('.select2-container');
        if ($s2.length) {
            $s2.toggleClass('filter-select-active', isActive);
        }
    }

    $(document).ready(function () {
        initFilterSelect2(document);

        $(document).on('shown.bs.collapse', '.collapse', function () {
            initFilterSelect2(this);
        });

        $(document).on('change', 'select.select2-filter, #expenseFilterForm select, #revenueFilterForm select, #scheduledFilterForm select', function () {
            syncFilterHighlight($(this));
        });
    });
</script>

<!-- GLOBAL ADMIN UTILITIES -->
<script>
    // Global CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Numbers Only Input Validator
    $(document).on('keyup', '.numbersOnly', function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    // Generic Modal Close Handler (Both 'غلق' button and 'X' close button)
    $(document).on('click', '#close_model, #close_model_x, .btn-close-modal, [data-dismiss="modal"]', function (e) {
        e.preventDefault();
        $('#Modal').modal('hide');
        $(this).closest('.modal').modal('hide');
        $('.modal').modal('hide');
    });

    // Master Checkbox Toggle for DataTables
    $(document).on('change click', '#master', function () {
        var isChecked = $(this).is(':checked');
        $(".sub_chk").prop('checked', isChecked);
    });

    // Uncheck master if any sub_chk is unchecked
    $(document).on('change', '.sub_chk', function () {
        if (!$(this).is(':checked')) {
            $('#master').prop('checked', false);
        } else {
            var total = $('.sub_chk').length;
            var checked = $('.sub_chk:checked').length;
            if (total > 0 && total === checked) {
                $('#master').prop('checked', true);
            }
        }
    });

    // Toggle card selection state for switches and feature/branch/image cards
    $(document).on('change', '.switch-btn-input, .feature-input, .branch-input, .image-delete-input', function () {
        $(this).closest('label').toggleClass('is-checked', this.checked);
    });
</script>

<!-- PAGE SPECIFIC SCRIPTS -->
@stack('admin_js')
