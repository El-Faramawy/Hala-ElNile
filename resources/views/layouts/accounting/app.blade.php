<!doctype html>
<html lang="ar" dir="rtl">

<head>
    @include('layouts.admin.css')
    <style>
        .fs-11 { font-size: 11px !important; }
        .fs-12 { font-size: 12px !important; }
        .fs-13 { font-size: 13px !important; }
        .fs-14 { font-size: 14px !important; }
        .fs-16 { font-size: 16px !important; }
        .fs-18 { font-size: 18px !important; }
        .fs-20 { font-size: 20px !important; }
        .fs-22 { font-size: 22px !important; }
        .fs-26 { font-size: 26px !important; }
        .gap-1 { gap: 4px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 16px; }

        /* Form Controls & Filter Styles */
        .form-control-solid {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.2s ease-in-out;
        }
        .form-control-solid:focus {
            background-color: #ffffff;
            border-color: #008c45;
            box-shadow: 0 0 0 3px rgba(0, 140, 69, 0.12);
        }

        /* Responsive Table & Container Adjustments */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto !important;
            border-radius: 8px;
        }

        /* Custom Scrollbar for smooth touch & desktop experience */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Card & Header Responsive Polish */
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(11, 34, 61, 0.05);
        }

        @media (max-width: 767.98px) {
            .app-content {
                padding-top: 15px !important;
            }
            .page-title {
                font-size: 18px !important;
            }
            .page-header {
                margin-bottom: 15px !important;
            }
            .number-font.fs-22 {
                font-size: 18px !important;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }
            .card-header .btn-group,
            .card-header .d-flex {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: right !important;
                margin-bottom: 10px;
            }
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: center !important;
                margin-top: 10px;
            }
            .dt-buttons {
                float: none !important;
                text-align: center !important;
                margin-bottom: 10px;
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
        }
    </style>
</head>

<body class="app sidebar-mini">

<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <div class="gl-box">
        <img src="{{ asset('Admin/imgs/loader.webp') }}" alt="جاري التحميل" class="gl-plane-img">
        <div class="gl-dots">
            <div class="gl-dot"></div>
            <div class="gl-dot"></div>
            <div class="gl-dot"></div>
        </div>
        <p class="gl-text">جاري تحميل البيانات المالية...</p>
        <p class="gl-sub">يرجى الانتظار</p>
    </div>
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        @include('layouts.accounting.sidebar')
        <!-- Header -->
        @include('layouts.accounting.header')

        <!--Content-area open-->
        <div class="app-content">
            <div class="side-app">
                <!-- PAGE-HEADER -->
                @hasSection('hide_page_header')
                @else
                <div class="page-header">
                    <div>
                        <h1 class="page-title">@yield('page_title')</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('accounting.dashboard') }}"><i class="fe fe-dollar-sign ml-1"></i>المنظومة المالية</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page_title')</li>
                        </ol>
                    </div>
                </div>
                @endif
                <!-- PAGE-HEADER END -->

                @yield('content')

            </div><!-- End Page -->
        </div>
        <!-- CONTAINER END -->
    </div>

    <!-- FOOTER -->
    @include('layouts.admin.footer')
    <!-- FOOTER END -->

</div>

@include('layouts.admin.js')
@stack('admin_js')

</body>
</html>
