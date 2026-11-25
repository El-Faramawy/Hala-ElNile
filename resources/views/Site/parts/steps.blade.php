<!-- ==========================================================================
     Timeline & Steps to Italy (Decreto Flussi Roadmap)
     ========================================================================== -->
<section class="py-5 bg-soft" id="steps">
  <div class="container py-lg-4">
    <div class="section-header text-center reveal-on-scroll">
      <span class="section-subtitle"><i class="fa-solid fa-route me-1"></i> خطوات السفر</span>
      <h2 class="section-title">إجراءات السفر إلى إيطاليا خطوة بخطوة</h2>
      <p class="section-desc">
        تعرف على الخطوات القانونية الرسمية الخمس المعتمدة لدى وزارة العمل المصرية والسفارة الإيطالية:
      </p>
    </div>

    <div class="row g-4 mt-2">
      <!-- Step 1 -->
      <x-site.step-card
        :step="1"
        :stagger="1"
        icon="fa-solid fa-id-card-clip"
        title="فحص الملف والأوراق"
        description="مراجعة جواز السفر، الفيش الجنائي، شهادات الخبرة أو قياس المهارة، والتأكد من مطابقة شروط كوتة العمل الإيطالية."
      />

      <!-- Step 2 -->
      <x-site.step-card
        :step="2"
        :stagger="2"
        icon="fa-solid fa-laptop-code"
        title="التسجيل بنظام فلوتسي"
        description="يقوم شريكنا وصاحب العمل الإيطالي برفع طلب الاستقدام في بوابة وزارة الداخلية الإيطالية (Click Day)."
      />

      <!-- Step 3 -->
      <x-site.step-card
        :step="3"
        :stagger="3"
        icon="fa-solid fa-certificate"
        title="صدور الـ Nulla Osta"
        description="صدور الموافقة الأمنية الرسمية وتصريح العمل المبدئي من مديرية أمن المحافظة الإيطالية (Prefettura)."
      />

      <!-- Step 4 -->
      <x-site.step-card
        :step="4"
        :stagger="4"
        icon="fa-solid fa-passport"
        title="بصمة وتأشيرة السفارة"
        description="حجز موعد السفارة الإيطالية بالقاهرة لتقديم الأوراق الأصلية، أخذ البصمة البيومترية، وإصدار تأشيرة العمل (Visto D)."
      />

      <!-- Step 5 -->
      <x-site.step-card
        :step="5"
        :stagger="5"
        icon="fa-solid fa-plane-departure"
        title="السفر والاستقبال بإيطاليا"
        description="حجز التذكرة، تسليمك العقد المعتمد، واستقبالك في مطار روما أو ميلانو ونقلك للسكن لبدء العمل واستخراج الإقامة."
      />
    </div>
  </div>
</section>
