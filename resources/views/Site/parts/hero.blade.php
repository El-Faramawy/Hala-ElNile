<!-- ==========================================================================
     Hero Slider Section (Swiper.js)
     ========================================================================== -->
<section class="hero-slider-section" id="hero">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">

      <!-- SLIDE 1: Welcome & About Hala Al-Nile -->
      <x-site.hero-slide
        :image="asset('Site/assets/images/hero/slide-agency.jpg')"
        badgeIcon="fa-solid fa-shield-halved text-warning"
        badgeText="ترخيص وزارة القوى العاملة رقم 912 شركات • معتمد رسمياً"
        description="شركة هلا النيل لإلحاق العمالة المصرية بالخارج. نضمن لك عقود عمل حكومية موثقة من القنصلية الإيطالية ووزارة العمل، مع توفير السكن القانوني، التأمين الطبي الشامل، واستقبال المطار في روما وميلانو."
      >
        <x-slot:title>
          بوابتك القانونية الموثوقة للعمل والإقامة الرسمية في <span class="text-gold">إيطاليا</span>
        </x-slot:title>

        <x-slot:actions>
          <a href="#jobs" class="btn btn-gold btn-lg px-4 py-3 rounded-pill pulse-btn">
            <i class="fa-solid fa-briefcase"></i> استعرض الوظائف وعقود العمل المتاحة
          </a>
          <a href="#about" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
            <i class="fa-solid fa-circle-info"></i> تعرف على شركتنا وتراخيصنا
          </a>
        </x-slot:actions>

        <x-slot:trustItems>
          <x-site.hero-trust-item icon="fa-solid fa-file-shield" text="عقود معتمدة بنظام فلوتسي (Decreto Flussi)" />
          <x-site.hero-trust-item icon="fa-solid fa-building-circle-check" text="سكن عمالي مسجل في البلديات الإيطالية" />
          <x-site.hero-trust-item icon="fa-solid fa-ban text-danger" text="بدون وسطاء أو سماسرة" />
        </x-slot:trustItems>
      </x-site.hero-slide>

      <!-- SLIDE 2: Real Travelers in Italy (Colosseum, Milan, Rome) -->
      <x-site.hero-slide
        :image="asset('Site/assets/images/hero/slide-travelers-italy.jpg')"
        badgeClass="bg-success bg-opacity-75"
        badgeIcon="fa-solid fa-camera text-white"
        badgeText="قصص نجاح حقيقية • مسافرونا يتحدثون من إيطاليا"
        description="صور حقيقية لشبابنا في روما وميلانو وتوسكانا وبولونيا. استلموا عقودهم الرسمية وسافروا عبر مطار القاهرة الدولي وصولاً لمقرات عملهم وسكنهم المجهز. انضم إليهم وحقق مستقبلك بأمان قانوني تام!"
      >
        <x-slot:title>
          أكثر من <span class="text-gold">3,850 شاب مصري</span> حققوا حلم السفر والعمل في مدن إيطاليا
        </x-slot:title>

        <x-slot:actions>
          <a href="#travelers" class="btn btn-success btn-lg px-4 py-3 rounded-pill">
            <i class="fa-solid fa-images"></i> شاهد معرض صور وقصص مسافرينا
          </a>
          <a href="#contact" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
            <i class="fa-solid fa-paper-plane"></i> احجز موعد مقابلتك الشخصية
          </a>
        </x-slot:actions>

        <x-slot:trustItems>
          <x-site.hero-trust-item icon="fa-solid fa-plane-arrival" text="استقبال من مندوبنا في مطارات إيطاليا" />
          <x-site.hero-trust-item icon="fa-solid fa-id-card" text="مساعدة فورية في استخراج كود الضرائب والإقامة" />
        </x-slot:trustItems>
      </x-site.hero-slide>

      <!-- SLIDE 3: Direct Jobs CTA (Available Jobs in Italy) -->
      <x-site.hero-slide
        :image="asset('Site/assets/images/hero/slide-jobs-italy.jpg')"
        badgeClass="bg-danger bg-opacity-75"
        badgeIcon="fa-solid fa-bolt text-warning"
        badgeText="فتح باب التقديم الفوري لعقود العمل 2024 - 2026"
        description="نطلب فوراً لكبرى المطاعم، المزارع، شركات البناء والمقاولات، والمراكز اللوجستية في إيطاليا: مساعدي شيفات، عمال زراعة، سائقي شاحنات، بنائين، وفنيي صيانة. عقود شاملة السكن والتأمين."
      >
        <x-slot:title>
          فرص عمل فورية برواتب تبدأ من <span class="text-gold">€1,400 حتى €3,000</span> شهرياً
        </x-slot:title>

        <x-slot:actions>
          <a href="#jobs" class="btn btn-gold btn-lg px-5 py-3 rounded-pill pulse-btn fs-5">
            <i class="fa-solid fa-arrow-down-long me-2"></i> استكشف الوظائف المتاحة الآن في إيطاليا
          </a>
          <button class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#quickApplyModal" onclick="prepareApplyModal('تقديم سريع على عقد عمل إيطاليا')">
            <i class="fa-solid fa-file-arrow-up"></i> تقديم طلب فوري
          </button>
        </x-slot:actions>

        <x-slot:trustItems>
          <x-site.hero-trust-item icon="fa-solid fa-euro-sign" text="رواتب مجزية تُحول لحسابك البنكي بإيطاليا" />
          <x-site.hero-trust-item icon="fa-solid fa-clock-rotate-left" text="إجراءات سريعة ومتابعة دقيقة لملفك" />
        </x-slot:trustItems>
      </x-site.hero-slide>

      <!-- SLIDE 4: Decreto Flussi & Legal Framework -->
      <x-site.hero-slide
        :image="asset('Site/assets/images/hero/slide-flussi.jpg')"
        badgeClass="bg-primary bg-opacity-75"
        badgeIcon="fa-solid fa-scale-balanced text-warning"
        badgeText="نظام فلوتسي الرسمي Decreto Flussi"
        description="نعمل مباشرة عبر البوابة الرسمية لوزارة الداخلية الإيطالية (Portale Servizi DLCI). تصريح العمل يصدر باسمك الشخصي من مديرية الأمن الإيطالية (Prefettura) ويُصدق بالقنصلية الإيطالية بالقاهرة."
      >
        <x-slot:title>
          طريقك القانوني الآمن دون مخاطرة: <span class="text-gold">النولا أوستا (Nulla Osta)</span> باسمك
        </x-slot:title>

        <x-slot:actions>
          <a href="#steps" class="btn btn-success btn-lg px-4 py-3 rounded-pill">
            <i class="fa-solid fa-list-check"></i> مراحل وخطوات السفر
          </a>
          <a href="#tracker" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
            <i class="fa-solid fa-magnifying-glass"></i> استعلم عن حالة معاملتك
          </a>
        </x-slot:actions>

        <x-slot:trustItems>
          <x-site.hero-trust-item icon="fa-solid fa-stamp" text="تصديق وزارة الخارجية والقوى العاملة" />
          <x-site.hero-trust-item icon="fa-solid fa-check-double" text="عقد عمل رسمي يحفظ كامل حقوقك القانونية" />
        </x-slot:trustItems>
      </x-site.hero-slide>

    </div>

    <!-- Slider Controls -->
    <div class="swiper-button-next d-none d-md-flex"></div>
    <div class="swiper-button-prev d-none d-md-flex"></div>
    <div class="swiper-pagination"></div>
  </div>
</section>
