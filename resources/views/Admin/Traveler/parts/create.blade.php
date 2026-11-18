<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('travelers.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-md-6 col-sm-12" title="اسم المسافر" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: أحمد صابر عبد السلام" name="name" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المحافظة بمصر" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: محافظة الغربية" name="governorate"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المسمى الوظيفي بإيطاليا" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: فني تشييد وبناء بروما" name="job_title" required/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="المعلم أو المدينة الإيطالية (Location Chip)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: روما - الكولوسيوم" name="location"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-12" title="شارة العقد بالكارد (Verified Seal)" :required="false">
            <input type="text" class="form-control form-control-solid" placeholder="مثال: عقد دائم موثق / شيف مطعم" name="badge"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="كود العقد الموثق" :required="false">
            <input type="text" class="form-control form-control-solid font-monospace dir-ltr" placeholder="مثال: HS-5120" name="contract_code"/>
        </x-form.input>

        <x-form.input class="col-md-4 col-sm-6" title="التقييم بالنجوم (1 إلى 5)" :required="false">
            <select name="rating" class="form-control form-control-solid">
                <option value="5" selected>5 نجوم (ممتاز)</option>
                <option value="4">4 نجوم (جيد جداً)</option>
                <option value="3">3 نجوم (جيد)</option>
            </select>
        </x-form.input>

        <x-form.input class="col-12" title="قصة وتجربة السفر مع هلا النيل (Quote)" :required="true">
            <textarea class="form-control form-control-solid" rows="3" placeholder="الحمد لله سافرت عن طريق شركة هلا النيل... السكن محترم والراتب بالبنك..." name="story" required></textarea>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="صورة المعلم الإيطالي الكبيرة (Landmark Image)" :required="false">
            <input type="file" class="form-control form-control-solid" name="landmark_image" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-12" title="الصورة الشخصية للمسافر (Avatar)" :required="false">
            <input type="file" class="form-control form-control-solid" name="avatar" accept="image/*"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="0" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-md-6 col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
