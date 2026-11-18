@extends('layouts.admin.app')

@section('page_title')
    لوحة التحكم | شركة هلا النيل لإلحاق العمالة بالخارج
@endsection

@push('admin_css')
    @include('Admin.parts.index-css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Hero Welcome Banner -->
            <div class="dashboard-hero">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="hero-badge">
                                <span class="hero-pulse"></span>
                                لوحة المتابعة المباشرة
                            </span>
                            <span class="text-muted small">{{ now()->translatedFormat('l، d F Y') }}</span>
                        </div>
                        <h1 class="hero-title">
                            مرحباً بك، {{ auth('admin')->user()->name ?? 'مدير النظام' }} 👋
                        </h1>
                        <p class="hero-subtitle">
                            نظرة شاملة على مؤشرات الأداء، طلبات التوظيف، وتتبع عقود السفر لإيطاليا - شركة هلا النيل (ترخيص 912)
                        </p>
                    </div>
                    <div class="d-flex flex-wrap align-items-center mt-3 mt-md-0" style="gap: 8px;">
                        <a href="{{ route('job-categories.index') }}" class="quick-action-btn quick-action-primary">
                            <i class="fe fe-grid"></i>
                            <span>أقسام الوظائف</span>
                        </a>
                        <a href="{{ route('cities.index') }}" class="quick-action-btn quick-action-light">
                            <i class="fe fe-map"></i>
                            <span>المحافظات والمدن</span>
                        </a>
                        <a href="{{ route('faqs.index') }}" class="quick-action-btn quick-action-light">
                            <i class="fe fe-help-circle"></i>
                            <span>الأسئلة الشائعة</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="quick-action-btn quick-action-light">
                            <i class="fe fe-settings"></i>
                            <span>الإعدادات</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 4 KPI Cards -->
    <div class="row">
        <!-- Total Jobs -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">الوظائف المتاحة بإيطاليا</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($stats['total_jobs']) }}
                        </div>
                        <p class="kpi-subtext">
                            <i class="fe fe-check-circle text-success"></i>
                            <span>عقود عمل موسمية ودائمة</span>
                        </p>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(46, 90, 28, 0.1); color: #2E5A1C;">
                        <i class="fe fe-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">طلبات التوظيف المستلمة</div>
                        <div class="kpi-value text-success">
                            {{ number_format($stats['total_applications']) }}
                        </div>
                        <p class="kpi-subtext">
                            <i class="fe fe-file-text text-primary"></i>
                            <span>التقديم السريع من الموقع</span>
                        </p>
                    </div>
                    <div class="kpi-icon-wrap icon-bookings">
                        <i class="fe fe-clipboard"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contract Trackers -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">معاملات تتبع العقود</div>
                        <div class="kpi-value text-warning">
                            {{ number_format($stats['total_contracts']) }}
                        </div>
                        <p class="kpi-subtext">
                            <i class="fe fe-shield text-warning"></i>
                            <span>تأشيرات ونظام فلوتسي</span>
                        </p>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(255, 171, 0, 0.1); color: #ffab00;">
                        <i class="fe fe-search"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Travelers -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">مسافرونا في إيطاليا</div>
                        <div class="kpi-value text-info">
                            {{ number_format($stats['total_travelers']) }}
                        </div>
                        <p class="kpi-subtext">
                            <i class="fe fe-star text-warning"></i>
                            <span>قصص وتجارب موثقة</span>
                        </p>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(23, 162, 184, 0.1); color: #17a2b8;">
                        <i class="fe fe-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="row mt-3">
        <!-- Job Categories -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted">تصنيفات الوظائف</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_job_categories'] }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-light text-primary">
                        <i class="fe fe-grid fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cities -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted">المحافظات المصرية</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_cities'] }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-light text-success">
                        <i class="fe fe-map fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Inquiries -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted">رسائل التواصل</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_messages'] }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-light text-info">
                        <i class="fe fe-message-square fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted">المشرفين</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_admins'] }}</h4>
                    </div>
                    <div class="p-2 rounded-circle bg-light text-secondary">
                        <i class="fe fe-user-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables Row -->
    <div class="row mt-4">
        <!-- Recent Applications -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fe fe-file-text text-primary me-1"></i> أحدث طلبات التوظيف المستلمة
                    </h6>
                    <span class="badge bg-soft-success text-success">آخر الطلبات</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>المتقدم</th>
                                    <th>الوظيفة المطلوبة</th>
                                    <th>المحافظة</th>
                                    <th>موقف الجواز</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_applications'] as $app)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $app->name }}</div>
                                            <small class="text-muted dir-ltr">{{ $app->phone }}</small>
                                        </td>
                                        <td>{{ $app->job_title ?? ($app->job?->title ?? '-') }}</td>
                                        <td>{{ $app->city?->name ?? '-' }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $app->passport_situation ?? 'غير محدد' }}</span></td>
                                        <td>
                                            @if($app->status === 'accepted')
                                                <span class="badge bg-success">مقبول</span>
                                            @elseif($app->status === 'contacted')
                                                <span class="badge bg-info">تم التواصل</span>
                                            @elseif($app->status === 'rejected')
                                                <span class="badge bg-danger">مرفوض</span>
                                            @else
                                                <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد طلبات تقديم حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Contact Messages -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fe fe-mail text-success me-1"></i> أحدث استفسارات العملاء
                    </h6>
                    <span class="badge bg-soft-info text-info">رسائل الموقع</span>
                </div>
                <div class="card-body p-3">
                    @forelse($stats['recent_messages'] as $msg)
                        <div class="p-3 mb-2 rounded bg-light border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">{{ $msg->name }}</span>
                                <small class="text-muted">{{ $msg->created_at?->diffForHumans() }}</small>
                            </div>
                            <div class="small text-muted mb-1">
                                <i class="fe fe-phone me-1"></i> {{ $msg->phone }}
                                @if($msg->city)
                                    <span class="ms-2">({{ $msg->city->name }})</span>
                                @endif
                            </div>
                            <div class="small text-secondary">{{ Str::limit($msg->message, 90) }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">لا توجد رسائل واردة حالياً.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
