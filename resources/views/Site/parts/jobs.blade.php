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
      <button class="job-filter-btn active" data-filter="all">
        <i class="fa-solid fa-border-all me-1"></i> كافة الوظائف (الكل)
      </button>
      <button class="job-filter-btn" data-filter="hospitality">
        <i class="fa-solid fa-utensils me-1"></i> مطاعم وفندقة
      </button>
      <button class="job-filter-btn" data-filter="agriculture">
        <i class="fa-solid fa-seedling me-1"></i> عمالة زراعية
      </button>
      <button class="job-filter-btn" data-filter="construction">
        <i class="fa-solid fa-trowel-bricks me-1"></i> تشييد وبناء
      </button>
      <button class="job-filter-btn" data-filter="logistics">
        <i class="fa-solid fa-truck-fast me-1"></i> نقل ولوجستيات
      </button>
      <button class="job-filter-btn" data-filter="technical">
        <i class="fa-solid fa-wrench me-1"></i> صيانة ومهن حرفية
      </button>
    </div>

    <!-- Live Search Box -->
    <div class="job-search-box">
      <input type="text" id="jobSearchInput" class="form-control" placeholder="ابحث باسم المهنة أو المدينة (مثلاً: شيف، زراعة، ميلانو، روما...)">
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>

    <!-- Dynamic Jobs Grid -->
    <div class="row g-4" id="jobsContainer">
      <!-- Rendered dynamically by jobs-data.js & main.js -->
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
          <a href="https://wa.me/201018899770?text=استفسار%20عن%20مهنة%20جديدة%20في%20إيطاليا" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-lg px-4 py-2 rounded-pill">
            <i class="fa-brands fa-whatsapp me-1"></i> استفسر عبر واتساب
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
