@extends('layouts.admin.app')
@section('page_title') الإعدادات العامة @endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
    <style>
        .cke_notification_warning,
        .cke_notification {
            display: none !important;
        }

        .cke_contents {
            min-height: 240px !important;
            height: 240px !important;
        }
    </style>
@endpush

@section('content')
    <x-list.card>
        <x-list.card-header name="إعدادات النظام والشركة" :add_button="false" :delete_button="false"></x-list.card-header>
        <div class="card-body">
            <form id="settingsForm" action="{{ route('settings.update', $setting->id) }}" class="setting_form my_form"
                method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Nav tabs -->
                <ul class="nav settings-nav-tabs" id="settingTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                            aria-controls="general" aria-selected="true">
                            <i class="fe fe-sliders ml-2"></i>الإعدادات العامة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" aria-selected="false">
                            <i class="fe fe-phone-call ml-2"></i>الاتصال والتراخيص الرسمية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab"
                            aria-controls="social" aria-selected="false">
                            <i class="fe fe-share-2 ml-2"></i>منصات التواصل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab"
                            aria-controls="stats" aria-selected="false">
                            <i class="fe fe-bar-chart-2 ml-2"></i>إحصائيات الموقع
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pages-tab" data-toggle="tab" href="#pages" role="tab"
                            aria-controls="pages" aria-selected="false">
                            <i class="fe fe-file-text ml-2"></i>الصفحات التعريفية والسياسات
                        </a>
                    </li>
                </ul>

                <!-- Tab contents -->
                <div class="tab-content" id="settingTabContent">
                    <!-- Tab 1: General Settings -->
                    <div class="tab-pane fade show active p-2" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <x-form.input class="col-md-12 col-sm-12" title="اسم الشركة / الموقع" :required="true">
                                <input type="text" class="form-control form-control-solid" placeholder="اسم الشركة"
                                    name="name" value="{{ $setting->name }}" required />
                            </x-form.input>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <x-form.file name="الشعار (اللوجو)">
                                    <input type="file" class="dropify" name="logo"
                                        data-default-file="{{ get_file($setting->logo) }}" data-height="200" />
                                </x-form.file>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <x-form.file name="صورة المتصفح (Favicon)">
                                    <input type="file" class="dropify" name="fav_icon"
                                        data-default-file="{{ get_file($setting->fav_icon) }}" data-height="200" />
                                </x-form.file>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Contact & Official Licensing -->
                    <div class="tab-pane fade p-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12" title="رقم الهاتف الأساسي" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="01018899770"
                                    name="phone" value="{{ $setting->phone }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="رقم واتساب الاستقدام" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="201018899770"
                                    name="whatsapp" value="{{ $setting->whatsapp }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="البريد الإلكتروني الرسمي" :required="false">
                                <input type="email" class="form-control form-control-solid" placeholder="info@halanile.com"
                                    name="email" value="{{ $setting->email }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="مواعيد العمل الرسمية" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="السبت - الخميس: 9:00 ص - 6:00 م"
                                    name="working_hours" value="{{ $setting->working_hours }}" />
                            </x-form.input>

                            <x-form.input class="col-md-4 col-sm-12" title="رقم ترخيص وزارة العمل" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="912 شركات (فئة أ)"
                                    name="license_number" value="{{ $setting->license_number }}" />
                            </x-form.input>

                            <x-form.input class="col-md-4 col-sm-12" title="رقم السجل التجاري" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="104522 مكتب استثمار القاهرة"
                                    name="commercial_record" value="{{ $setting->commercial_record }}" />
                            </x-form.input>

                            <x-form.input class="col-md-4 col-sm-12" title="رقم البطاقة الضريبية" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="429-881-209"
                                    name="tax_card" value="{{ $setting->tax_card }}" />
                            </x-form.input>

                            <x-form.input class="col-12" title="عنوان مقر الشركة" :required="false">
                                <textarea class="form-control form-control-solid" rows="2" placeholder="مصر - القاهرة..."
                                    name="address">{{ $setting->address }}</textarea>
                            </x-form.input>
                        </div>
                    </div>

                    <!-- Tab 3: Social Media Links -->
                    <div class="tab-pane fade p-2" id="social" role="tabpanel" aria-labelledby="social-tab">
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12" title="رابط فيسبوك (Facebook)" :required="false">
                                <input type="url" class="form-control form-control-solid dir-ltr" placeholder="https://facebook.com/..."
                                    name="facebook" value="{{ $setting->facebook }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="رابط تويتر / إكس (Twitter)" :required="false">
                                <input type="url" class="form-control form-control-solid dir-ltr" placeholder="https://x.com/..."
                                    name="twitter" value="{{ $setting->twitter }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="رابط قناة تليجرام (Telegram)" :required="false">
                                <input type="url" class="form-control form-control-solid dir-ltr" placeholder="https://t.me/..."
                                    name="telegram" value="{{ $setting->telegram }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="رابط يوتيوب (YouTube)" :required="false">
                                <input type="url" class="form-control form-control-solid dir-ltr" placeholder="https://youtube.com/..."
                                    name="youtube" value="{{ $setting->youtube }}" />
                            </x-form.input>
                        </div>
                    </div>

                    <!-- Tab 4: Homepage Counter Statistics -->
                    <div class="tab-pane fade p-2" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                        <div class="row">
                            <x-form.input class="col-md-6 col-sm-12" title="عداد: مسافر استقر بإيطاليا" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="+1,450"
                                    name="stats_travelers" value="{{ $setting->stats_travelers }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="عداد: نسبة التوثيق والاعتماد" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="100%"
                                    name="stats_contracts" value="{{ $setting->stats_contracts }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="عداد: شركة ومزرعة إيطالية شريكة" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="+68"
                                    name="stats_companies" value="{{ $setting->stats_companies }}" />
                            </x-form.input>

                            <x-form.input class="col-md-6 col-sm-12" title="عداد: سنوات الريادة والخبرة" :required="false">
                                <input type="text" class="form-control form-control-solid" placeholder="14 عاماً"
                                    name="stats_experience" value="{{ $setting->stats_experience }}" />
                            </x-form.input>
                        </div>
                    </div>

                    <!-- Tab 5: Static Pages and Policies -->
                    <div class="tab-pane fade p-2" id="pages" role="tabpanel" aria-labelledby="pages-tab">
                        <div class="card card-body mb-4 shadow-sm"
                            style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0"
                                    style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-info font-size-16"></i>
                                </div>
                                من نحن (About Us)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-12 mb-3" title="من نحن" :required="false">
                                    <textarea class="form-control" id="about_us" name="about_us" rows="10"
                                        placeholder="اكتب نبذة عن الشركة...">{{ $setting->about_us }}</textarea>
                                </x-form.input>
                            </div>
                        </div>

                        <div class="card card-body mb-4 shadow-sm"
                            style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0"
                                    style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-check-circle font-size-16"></i>
                                </div>
                                الشروط والأحكام (Terms & Conditions)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-12 mb-3" title="الشروط والأحكام" :required="false">
                                    <textarea class="form-control" id="terms_conditions" name="terms_conditions"
                                        rows="10"
                                        placeholder="الشروط والأحكام...">{{ $setting->terms_conditions }}</textarea>
                                </x-form.input>
                            </div>
                        </div>

                        <div class="card card-body mb-3 shadow-sm"
                            style="background: #ffffff; border: 1px solid #D8F2B5; border-radius: 12px;">
                            <h5 class="font-weight-bold mb-3 d-flex align-items-center" style="color: #2E5A1C;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle ml-2 flex-shrink-0"
                                    style="width: 34px; height: 34px; background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5;">
                                    <i class="fe fe-lock font-size-16"></i>
                                </div>
                                سياسة الخصوصية (Privacy Policy)
                            </h5>
                            <div class="row">
                                <x-form.input class="col-12 mb-3" title="سياسة الخصوصية" :required="false">
                                    <textarea class="form-control" id="privacy_policy" name="privacy_policy" rows="10"
                                        placeholder="سياسة الخصوصية...">{{ $setting->privacy_policy }}</textarea>
                                </x-form.input>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer mt-3 bg-white px-0 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 font-weight-bold ml-2 save_setting_btn">
                        <i class="fe fe-save ml-1"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </x-list.card>
@endsection

@push('admin_js')
    @include('layouts.admin.inc.my-form')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script>
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.config.versionCheck = false;
        }

        $(document).ready(function () {
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.config.versionCheck = false;
            }

            var editors = [
                { id: 'about_us', dir: 'rtl', lang: 'ar' },
                { id: 'terms_conditions', dir: 'rtl', lang: 'ar' },
                { id: 'privacy_policy', dir: 'rtl', lang: 'ar' }
            ];

            editors.forEach(function (item) {
                if ($('#' + item.id).length) {
                    CKEDITOR.replace(item.id, {
                        versionCheck: false,
                        contentsLangDirection: item.dir,
                        language: item.lang,
                        height: 240,
                        allowedContent: true,
                        toolbar: 'Full'
                    });
                }
            });

            $('#settingsForm').on('submit', function () {
                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        if (CKEDITOR.instances.hasOwnProperty(instance)) {
                            CKEDITOR.instances[instance].updateElement();
                        }
                    }
                }
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        if (CKEDITOR.instances.hasOwnProperty(instance)) {
                            CKEDITOR.instances[instance].resize('100%', 240);
                        }
                    }
                }
            });
        });
    </script>
@endpush
