<!-- ==========================================================================
     Jobs & Contracts Section (Interactive Filter, Search & Details)
     ========================================================================== -->
<section class="py-5" id="jobs">
  <div class="container py-lg-4">
    <div class="section-header text-center reveal-on-scroll">
      <span class="section-subtitle"><i class="fa-solid fa-briefcase me-1"></i> الوظائف المتاحة</span>
      <h2 class="section-title">عقود العمل الحالية والمتاحة في إيطاليا</h2>
      <p class="section-desc">
        استكشف أحدث الوظائف المعتمدة مع كبرى الشركات الإيطالية. رواتب مجزية باليورو، سكن موفر، وتأمين صحي كامل. اختر مهنتك وقدّم طلبك فوراً!
      </p>
    </div>

    <!-- Filter Buttons -->
    <div class="job-filter-nav reveal-on-scroll">
      <x-site.job-filter-btn filter="all" :active="true" icon="fa-solid fa-border-all me-1" label="كافة الوظائف (الكل)" />
      @if(isset($categories) && $categories->isNotEmpty())
        @foreach($categories as $category)
          <x-site.job-filter-btn
            :filter="(string) $category->id"
            :icon="($category->icon ?: 'fa-solid fa-briefcase') . ' me-1'"
            :label="$category->name"
          />
        @endforeach
      @else
        <x-site.job-filter-btn filter="hospitality" icon="fa-solid fa-utensils me-1" label="مطاعم وفندقة" />
        <x-site.job-filter-btn filter="agriculture" icon="fa-solid fa-seedling me-1" label="عمالة زراعية" />
        <x-site.job-filter-btn filter="construction" icon="fa-solid fa-trowel-bricks me-1" label="تشييد وبناء" />
        <x-site.job-filter-btn filter="logistics" icon="fa-solid fa-truck-fast me-1" label="نقل ولوجستيات" />
        <x-site.job-filter-btn filter="technical" icon="fa-solid fa-wrench me-1" label="صيانة ومهن حرفية" />
      @endif
    </div>

    <!-- Live Search Box -->
    <div class="job-search-box">
      <input type="text" id="jobSearchInput" class="form-control" placeholder="ابحث باسم المهنة أو المدينة (مثلاً: شيف، زراعة، ميلانو، روما...)">
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>

    <!-- Dynamic Jobs Grid -->
    <div class="row g-4" id="jobsContainer">
      @if(isset($jobs) && $jobs->isNotEmpty())
        @foreach($jobs as $index => $job)
          @php
            $salaryDisplay = $job->salary_text ?: ($job->salary_min ? '€'.number_format($job->salary_min).' - €'.number_format($job->salary_max) : 'راتب مجزي');
            $housingWords = explode(' ', (string) ($job->housing ?? 'سكن متوفر'));
            $housingDisplay = ($housingWords[0] ?? 'سكن') . ' ' . ($housingWords[1] ?? 'متوفر');
            $hoursDisplay = $job->working_hours ?: '8 ساعات يومياً';
            $badgeDisplay = $job->badge ?: 'متاح للتقديم';
            $categoryNameDisplay = $job->category?->name ?: 'عام';
            $typeDisplay = $job->contract_type ?: 'عقد عمل معتمد';
            $jobImg = $job->image ? asset('storage/'.$job->image) : asset('Site/assets/images/hero/slide-jobs-italy.jpg');
          @endphp
          <div class="col-lg-4 col-md-6 mb-4 job-card-enter" style="--i: {{ min($index, 8) }}">
            <div class="card h-100 job-card border-0 shadow-sm rounded-4 overflow-hidden position-relative">
              <div class="job-card-img-wrapper position-relative">
                <img src="{{ $jobImg }}" class="card-img-top job-img" alt="{{ $job->title }}" loading="lazy" decoding="async">
                <div class="job-overlay-gradient"></div>
                <span class="badge bg-gold text-dark fw-bold position-absolute top-0 end-0 m-3 shadow-sm px-3 py-2 rounded-pill">
                  <i class="fa-solid fa-star me-1"></i> {{ $badgeDisplay }}
                </span>
                <div class="job-city-tag position-absolute bottom-0 start-0 m-3 text-white fw-bold d-flex align-items-center gap-2">
                  <i class="fa-solid fa-location-dot text-danger"></i>
                  <span>{{ $job->city ?? 'إيطاليا' }}</span>
                </div>
              </div>
              
              <div class="card-body p-4 d-flex flex-column">
                <div class="job-badges-header d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                  <span class="badge bg-light text-primary border px-3 py-1 rounded-pill fw-semibold job-category-badge">
                    {{ $categoryNameDisplay }}
                  </span>
                  <span class="badge bg-soft-success text-success border-success-subtle px-2 py-1 rounded-2 small job-type-badge">
                    {{ $typeDisplay }}
                  </span>
                </div>

                <h5 class="card-title fw-bold text-dark my-2 job-title">
                  {{ $job->title }}
                </h5>

                <p class="card-text text-muted small line-clamp-2 mb-3">
                  {{ $job->description }}
                </p>

                <div class="job-meta-grid my-3 p-3 bg-light rounded-3">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small"><i class="fa-solid fa-sack-dollar text-warning me-2"></i> الراتب الشهري:</span>
                    <span class="fw-bold text-success font-monospace dir-ltr">{{ $salaryDisplay }}</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small"><i class="fa-solid fa-house-chimney text-info me-2"></i> السكن:</span>
                    <span class="fw-semibold text-dark small">{{ $housingDisplay }}</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="fa-solid fa-clock text-secondary me-2"></i> ساعات العمل:</span>
                    <span class="fw-semibold text-dark small">{{ $hoursDisplay }}</span>
                  </div>
                </div>

                <div class="mt-auto pt-2 d-flex gap-2 job-card-actions">
                  <button class="btn btn-outline-primary w-50 py-2 rounded-pill fw-bold" onclick="viewJobDetails('{{ $job->id }}')">
                    <i class="fa-solid fa-circle-info me-1"></i> التفاصيل
                  </button>
                  <button class="btn btn-primary w-50 py-2 rounded-pill fw-bold btn-apply" onclick="prepareApplyModal('{{ $job->title }}', '{{ $job->id }}')" data-bs-toggle="modal" data-bs-target="#quickApplyModal">
                    <i class="fa-solid fa-paper-plane me-1"></i> قدّم الآن
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>

    <!-- General Apply Callout -->
    <div class="mt-5 p-4 p-md-5 rounded-4 bg-primary text-white text-center shadow-lg position-relative overflow-hidden">
      <div class="position-relative z-1">
        <h3 class="fw-bold mb-2">لم تجد مهنتك أو تخصصك في القائمة؟</h3>
        <p class="text-white-50 max-w-700 mx-auto mb-4">
          تصلنا كوتات وعقود عمل جديدة بشكل أسبوعي من مختلف المحافظات الإيطالية. سجّل بياناتك وسيرتك الذاتية في قاعدة بيانات هلا النيل وسنتصل بك بمجرد فتح الشواغر المطابقة لمهنتك.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <button class="btn btn-gold btn-lg px-4 py-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#quickApplyModal" onclick="prepareApplyModal('طلب تسجيل عام في قاعدة بيانات إيطاليا')">
            <i class="fa-solid fa-user-plus me-1"></i> سجّل في قاعدة بيانات المستقدمين
          </button>
          @if(!empty($settings?->whatsapp))
            @php $waNum = preg_replace('/[^0-9]/', '', $settings->whatsapp); @endphp
            <a href="https://wa.me/{{ $waNum }}?text=استفسار%20عن%20مهنة%20جديدة%20في%20إيطاليا" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-lg px-4 py-2 rounded-pill">
              <i class="fa-brands fa-whatsapp me-1"></i> استفسر عبر واتساب
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
