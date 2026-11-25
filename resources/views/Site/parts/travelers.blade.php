<!-- ==========================================================================
     Travelers in Italy Gallery & Success Stories
     ========================================================================== -->
<section class="py-5 bg-white" id="travelers">
  <div class="container py-lg-4">
    <div class="section-header text-center reveal-on-scroll">
      <span class="section-subtitle"><i class="fa-solid fa-users me-1"></i> مسافرونا في إيطاليا</span>
      <h2 class="section-title">تجارب وقصص نجاح من قلب إيطاليا</h2>
      <p class="section-desc">
        أبطالنا الذين سافروا عبر عقودنا الرسمية متصورين في أشهر معالم إيطاليا (الكولوسيوم بروما، كاتدرائية ميلانو، مزارع توسكانا، وبولونيا وفينيسيا). تجارب حقيقية تمنحك الثقة الكاملة:
      </p>
    </div>

    <!-- Travelers Swiper Slider -->
    <div class="position-relative px-md-3">
      <div class="swiper travelers-swiper">
        <div class="swiper-wrapper">
          @if(isset($travelers) && $travelers->isNotEmpty())
            @foreach($travelers as $traveler)
              @php
                $landmarkImg = $traveler->effective_landmark_image;
                $landmarkUrl = (file_exists(public_path($landmarkImg)) || str_starts_with($landmarkImg, 'Site/')) ? asset($landmarkImg) : asset('storage/'.$landmarkImg);
                $avatarImg = $traveler->avatar ? (file_exists(public_path($traveler->avatar)) ? asset($traveler->avatar) : asset('storage/'.$traveler->avatar)) : asset('Site/assets/images/travelers/person1.jpg');
                $locationText = $traveler->effective_location;
              @endphp
              <x-site.traveler-card
                :image="$landmarkUrl"
                :alt="$traveler->name"
                :location="$locationText"
                locationIcon="fa-solid fa-location-dot me-1"
                :seal="$traveler->badge ?? 'عقد دائم موثق'"
                :avatar="$avatarImg"
                :name="$traveler->name"
                :subtitle="($traveler->governorate ? $traveler->governorate . ' • ' : '') . ($traveler->job_title ?? '')"
                :contractCode="'كود العقد: ' . ($traveler->contract_code ?? 'HS-5120')"
                :quote="$traveler->story"
                :rating="(int) ($traveler->rating ?? 5)"
              />
            @endforeach
          @else
            <!-- Slide 1: Rome -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-colosseum.jpg')"
              alt="مسافرون في روما الكولوسيوم"
              location="روما - الكولوسيوم"
              locationIcon="fa-solid fa-landmark me-1"
              seal="عقد دائم موثق"
              :avatar="asset('Site/assets/images/travelers/person1.jpg')"
              name="أحمد صابر عبد السلام"
              subtitle="محافظة الغربية • فني تشييد وبناء بروما"
              contractCode="كود العقد: HS-5120"
              quote='"الحمد لله سافرت عن طريق شركة هلا النيل في مارس 2024. كل كلمة قالوهالي في المكتب بالقاهرة لقيتها في روما حرفياً: السكن محترم، الراتب 1,850 يورو بينزل في البنك، والمندوب استقبلني من المطار."'
            />

            <!-- Slide 2: Milan -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-milan.jpg')"
              alt="مسافرون في ساحة دومو ميلانو"
              location="ميلانو - ساحة الدومو"
              locationIcon="fa-solid fa-city me-1"
              seal="شيف مطعم إيطالي"
              :avatar="asset('Site/assets/images/travelers/person2.jpg')"
              name="محمود السيد رجب"
              subtitle="محافظة الشرقية • مساعد شيف بميلانو"
              contractCode="كود العقد: HS-6430"
              quote='"الموضوع كان بالنسبة لي حلم، وكنت خايف من السماسرة. هلا النيل شركة محترمة جداً ومرخصة، استلمت النولا أوستا في يدي وبصمت في السفارة بجاردن سيتي وطلعت التأشيرة رسمية 100%."'
            />

            <!-- Slide 3: Florence / Tuscany -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-florence.jpg')"
              alt="مسافرون في توسكانا"
              location="توسكانا - فلورنسا"
              locationIcon="fa-solid fa-wheat-awn me-1"
              seal="عمالة زراعية فلوتسي"
              :avatar="asset('Site/assets/images/travelers/person3.jpg')"
              name="إسلام عبد العزيز المنياوي"
              subtitle="محافظة المنيا • مزارع عنب وزيتون"
              contractCode="كود العقد: HS-4890"
              quote='"سافرت ضمن كوتة الفلوتسي الزراعية بتوسكانا. المعاملة هناك ممتازة والسكن داخل المزرعة رائع ومكيف ومعانا شباب مصريين كتير. حولت إقامتي بعد الموسم لعقد دائم بفضل الله ثم هلا النيل."'
            />

            <!-- Slide 4: Bologna -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-bologna.jpg')"
              alt="مسافرون في بولونيا"
              location="بولونيا - إميليا رومانيا"
              locationIcon="fa-solid fa-truck-fast me-1"
              seal="سائق شاحنات مهني"
              :avatar="asset('Site/assets/images/travelers/person4.jpg')"
              name="سامح فتحي الدسوقي"
              subtitle="محافظة الدقهلية • نقل لوجستي ببولونيا"
              contractCode="كود العقد: HS-7210"
              quote='"معادلة الرخصة المهنية الإيطالية تمت بكل سهولة بمساعدة محامي الشركة في بولونيا. المرتب مجزي جداً ويتجاوز 2,200 يورو شهرياً مع بدلات السفر. شكراً لإدارة هلا النيل على أمانتهم."'
            />

            <!-- Slide 5: Venice -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-venice.jpg')"
              alt="مسافرون في فينيسيا البندقية"
              location="فينيسيا - البندقية"
              locationIcon="fa-solid fa-hotel me-1"
              seal="طاقم ضيافة وفنادق"
              :avatar="asset('Site/assets/images/travelers/person5.jpg')"
              name="طارق نبيل القاضي"
              subtitle="محافظة كفر الشيخ • ضيافة سياحية بفينيسيا"
              contractCode="كود العقد: HS-8055"
              quote='"العمل في قطاع الفندقة في فينيسيا كان تجربة غيرت مجرى حياتي. الشركة وفرت لي الإقامة والوجبات كاملة داخل الفندق، واستلمت بطاقة الإقامة والتأمين الصحي في أول أسبوعين."'
            />

            <!-- Slide 6: Turin -->
            <x-site.traveler-card
              :image="asset('Site/assets/images/travelers/travelers-turin.jpg')"
              alt="مسافرون في تورينو"
              location="تورينو - بييمونتي"
              locationIcon="fa-solid fa-wrench me-1"
              seal="فني تمديدات كهربائية"
              :avatar="asset('Site/assets/images/travelers/person6.jpg')"
              name="محمد حسن عشري"
              subtitle="محافظة الجيزة • فني كهرباء بتورينو"
              contractCode="كود العقد: HS-9140"
              quote='"عقد عمل غير موسمي رسمي ومصدق، الراتب في ميعاده ومقر السكن مجهز بكل ما يحتاجه الفرد. هلا النيل أفضل شركة تعاملت معها، التزام بالمواعيد ومصداقية كاملة من أول مقابلة."'
            />
          @endif
        </div>

        <!-- Pagination -->
        <div class="travelers-pagination text-center mt-4"></div>
      </div>

      <!-- Custom Navigation Arrows -->
      <div class="travelers-prev swiper-nav-custom prev d-none d-md-flex" title="السابق">
        <i class="fa-solid fa-angle-right"></i>
      </div>
      <div class="travelers-next swiper-nav-custom next d-none d-md-flex" title="التالي">
        <i class="fa-solid fa-angle-left"></i>
      </div>
    </div>
  </div>
</section>
