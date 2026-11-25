<!-- ==========================================================================
     FAQ Section (Frequently Asked Questions)
     ========================================================================== -->
<section class="py-5 bg-white" id="faq">
  <div class="container py-lg-4">
    <div class="section-header text-center reveal-on-scroll">
      <span class="section-subtitle"><i class="fa-solid fa-circle-question me-1"></i> الأسئلة الشائعة</span>
      <h2 class="section-title">إجابات واضحة لكافة الاستفسارات حول إيطاليا</h2>
      <p class="section-desc">
        كل ما تحتاج معرفته عن قانون فلوتسي، تكاليف السفر، شروط التأشيرة، وحقوق العامل في إيطاليا:
      </p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion reveal-on-scroll" id="faqAccordion">
          @if(isset($faqs) && $faqs->isNotEmpty())
            @foreach($faqs as $faqIndex => $faq)
              <x-site.faq-item
                :id="'faq'.$faq->id"
                :show="$faqIndex === 0"
                :question="$faq->question"
              >
                {!! nl2br(e($faq->answer)) !!}
              </x-site.faq-item>
            @endforeach
          @else
            <!-- FAQ 1 -->
            <x-site.faq-item
              id="faq1"
              :show="true"
              question="ما هو قانون ديكريتو فلوتسي (Decreto Flussi) وكيف أضمن أن العقد حقيقي؟"
            >
              قانون "ديكريتو فلوتسي" هو مرسوم رسمي سنوي تصدره الحكومة الإيطالية لتحديد أعداد العمالة الأجنبية المصرح لها بدخول إيطاليا. الضمان الوحيد لصحة العقد هو صدور تصريح عمل رسمي يُسمى <strong>(Nulla Osta al lavoro)</strong> من المحافظة الإيطالية (Prefettura) برقم مرجعي مسجل على موقع وزارة الداخلية الإيطالية. شركة هلا النيل تسلمك نسخة أصلية موثقة قبل تحديد موعد السفارة.
            </x-site.faq-item>

            <!-- FAQ 2 -->
            <x-site.faq-item
              id="faq2"
              question="هل يشترط إتقان اللغة الإيطالية للتقديم على الوظائف؟"
            >
              بالنسبة للعمالة الزراعية والإنشائية والمهن الحرفية، لا تشترط السفارة أو أصحاب العمل إتقان اللغة الإيطالية في البداية. أما في مجالات المطاعم والضيافة، فيكفي الإلمام بأساسيات ومصطلحات المهنة. نحن في شركة هلا النيل نوفر للمسافر دورة إيطالية مصغرة مجانية لتجهيزه للتعامل اليومي في إيطاليا.
            </x-site.faq-item>

            <!-- FAQ 3 -->
            <x-site.faq-item
              id="faq3"
              question="هل السكن والتأمين الصحي موفرين في العقد؟"
            >
              نعم، ينص القانون الإيطالي صراحة على أن صاحب العمل ملزم بتوفير إثبات سكن ملائم ومسجل بالبلدية (Idoneità Alloggiativa) للعامل. كما يتم تسجيل العامل فور وصوله في التأمين الصحي الإيطالي الحكومي (SSN) ليحصل على بطاقة الرعاية الصحية (Tessera Sanitaria) مجاناً.
            </x-site.faq-item>

            <!-- FAQ 4 -->
            <x-site.faq-item
              id="faq4"
              question="كم يستغرق استخراج التأشيرة وسفر العامل؟"
            >
              تستغرق الإجراءات منذ تقديم الملف وصدور الـ Nulla Osta ما بين 60 إلى 90 يوماً حسب سرعة المعالجة في المحافظة الإيطالية والقنصلية بالقاهرة. نتابع الملف أسبوعياً ونحيط العميل بكافة المستجدات عبر بوابة التتبع ورسائل الواتساب.
            </x-site.faq-item>

            <!-- FAQ 5 -->
            <x-site.faq-item
              id="faq5"
              question="هل يمكن تحويل العقد الموسمي إلى إقامة دائمة أو استقدام الزوجة؟"
            >
              نعم، يتيح قانون العمل الإيطالي للعامل الموسمي الذي يثبت التزامه تحويل تصريح إقامته (Permesso di Soggiorno) من موسمي (Stagionale) إلى تصريح عمل غير موسمي دائم (Lavoro Subordinato). وبعد استقرار الوضع المالي والسكن، يحق للعامل التقدم بطلب لم شمل أسرته قانونياً (Ricongiungimento Familiare).
            </x-site.faq-item>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
