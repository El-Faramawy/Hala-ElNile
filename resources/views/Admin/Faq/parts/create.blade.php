<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('faqs.store') }}">
    @csrf
    <div class="row mt-0">
        <x-form.input class="col-sm-12" title="السؤال" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="السؤال" name="question"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="الإجابة" :required="true">
            <textarea class="form-control form-control-solid" rows="4" placeholder="الإجابة" name="answer"></textarea>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الترتيب" :required="false">
            <input type="number" class="form-control form-control-solid numbersOnly" placeholder="الترتيب" name="sort_order" value="0"/>
        </x-form.input>

        <x-form.input class="col-sm-6" title="الحالة" :required="true">
            <select name="is_active" class="form-control form-control-solid">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </x-form.input>
    </div>
</form>
