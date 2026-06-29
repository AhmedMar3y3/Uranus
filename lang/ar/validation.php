<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'يجب قبول حقل :attribute.',
    'accepted_if'          => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url'           => 'حقل :attribute يجب أن يكون رابط URL صحيح.',
    'after'                => 'يجب أن يكون حقل :attribute تاريخاً بعد :date.',
    'after_or_equal'       => 'يجب أن يكون حقل :attribute تاريخاً بعد أو يساوي :date.',
    'alpha'                => 'يجب أن يحتوي حقل :attribute على أحرف فقط.',
    'alpha_dash'           => 'يجب أن يحتوي حقل :attribute على أحرف، أرقام، شرطات وشرطات سفلية فقط.',
    'alpha_num'            => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام فقط.',
    'array'                => 'يجب أن يكون حقل :attribute مصفوفة.',
    'ascii'                => 'يجب أن يحتوي حقل :attribute على رموز وأحرف لاتينية أحادية البايت فقط.',
    'before'               => 'يجب أن يكون حقل :attribute تاريخاً قبل :date.',
    'before_or_equal'      => 'يجب أن يكون حقل :attribute تاريخاً قبل أو يساوي :date.',
    'between'              => [
        'array'   => 'يجب أن يحتوي حقل :attribute على عدد من العناصر بين :min و :max.',
        'file'    => 'يجب أن يكون حجم ملف :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute بين :min و :max.',
        'string'  => 'يجب أن يكون طول نص :attribute بين :min و :max حرفاً.',
    ],
    'boolean'              => 'يجب أن يكون حقل :attribute صحيح أو خطأ.',
    'can'                  => 'يحتوي حقل :attribute على قيمة غير مصرح بها.',
    'confirmed'            => 'تأكيد حقل :attribute غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => 'يجب أن يكون حقل :attribute تاريخاً صحيحاً.',
    'date_equals'          => 'يجب أن يكون حقل :attribute تاريخاً يساوي :date.',
    'date_format'          => 'يجب أن يطابق حقل :attribute الصيغة :format.',
    'decimal'              => 'يجب أن يحتوي حقل :attribute على :decimal منازل عشرية.',
    'declined'             => 'يجب رفض حقل :attribute.',
    'declined_if'          => 'يجب رفض حقل :attribute عندما يكون :other هو :value.',
    'different'            => 'يجب أن يكون حقل :attribute و :other مختلفين.',
    'digits'               => 'يجب أن يحتوي حقل :attribute على :digits أرقام.',
    'digits_between'       => 'يجب أن يكون عدد أرقام حقل :attribute بين :min و :max.',
    'dimensions'           => 'أبعاد صورة :attribute غير صالحة.',
    'distinct'             => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with'      => 'يجب ألا ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'doesnt_start_with'    => 'يجب ألا يبدأ حقل :attribute بأحد القيم التالية: :values.',
    'email'                => 'يجب أن يكون حقل :attribute بريد إلكتروني صحيح.',
    'ends_with'            => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'enum'                 => 'القيمة المختارة في :attribute غير صالحة.',
    'exists'               => 'القيمة المختارة في :attribute غير صالحة.',
    'extensions'           => 'يجب أن يحتوي حقل :attribute على أحد الامتدادات التالية: :values.',
    'file'                 => 'يجب أن يكون حقل :attribute ملفاً.',
    'filled'               => 'يجب تعبئة حقل :attribute.',
    'gt'                   => [
        'array'   => 'يجب أن يحتوي حقل :attribute على أكثر من :value عنصر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute أكبر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من :value.',
        'string'  => 'يجب أن يكون طول نص :attribute أكبر من :value حرفاً.',
    ],
    'gte'                  => [
        'array'   => 'يجب أن يحتوي حقل :attribute على :value عنصر أو أكثر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من أو تساوي :value.',
        'string'  => 'يجب أن يكون طول نص :attribute أكبر من أو يساوي :value حرفاً.',
    ],
    'hex_color'            => 'يجب أن يكون حقل :attribute لوناً سداسياً صحيحاً.',
    'image'                => 'يجب أن يكون حقل :attribute صورة.',
    'in'                   => 'القيمة المختارة في :attribute غير صالحة.',
    'in_array'             => 'حقل :attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون حقل :attribute عدداً صحيحاً.',
    'ip'                   => 'يجب أن يكون حقل :attribute عنوان IP صحيح.',
    'ipv4'                 => 'يجب أن يكون حقل :attribute عنوان IPv4 صحيح.',
    'ipv6'                 => 'يجب أن يكون حقل :attribute عنوان IPv6 صحيح.',
    'json'                 => 'يجب أن يكون حقل :attribute نص JSON صحيح.',
    'lowercase'            => 'يجب أن يكون حقل :attribute بأحرف صغيرة.',
    'lt'                   => [
        'array'   => 'يجب أن يحتوي حقل :attribute على أقل من :value عنصر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute أقل من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من :value.',
        'string'  => 'يجب أن يكون طول نص :attribute أقل من :value حرفاً.',
    ],
    'lte'                  => [
        'array'   => 'يجب ألا يحتوي حقل :attribute على أكثر من :value عنصر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute أقل من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من أو تساوي :value.',
        'string'  => 'يجب أن يكون طول نص :attribute أقل من أو يساوي :value حرفاً.',
    ],
    'mac_address'          => 'يجب أن يكون حقل :attribute عنوان MAC صحيح.',
    'max'                  => [
        'array'   => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عنصر.',
        'file'    => 'يجب ألا يكون حجم ملف :attribute أكبر من :max كيلوبايت.',
        'numeric' => 'يجب ألا تكون قيمة حقل :attribute أكبر من :max.',
        'string'  => 'يجب ألا يكون طول نص :attribute أكبر من :max حرفاً.',
    ],
    'max_digits'           => 'يجب ألا يحتوي حقل :attribute على أكثر من :max رقم.',
    'mimes'                => 'يجب أن يكون ملف :attribute من نوع: :values.',
    'mimetypes'            => 'يجب أن يكون ملف :attribute من نوع: :values.',
    'min'                  => [
        'array'   => 'يجب أن يحتوي حقل :attribute على الأقل :min عنصر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute على الأقل :min كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute على الأقل :min.',
        'string'  => 'يجب أن يكون طول نص :attribute على الأقل :min حرفاً.',
    ],
    'min_digits'           => 'يجب أن يحتوي حقل :attribute على الأقل :min رقم.',
    'missing'              => 'يجب أن يكون حقل :attribute مفقوداً.',
    'missing_if'           => 'يجب أن يكون حقل :attribute مفقوداً عندما يكون :other هو :value.',
    'missing_unless'       => 'يجب أن يكون حقل :attribute مفقوداً إلا إذا كان :other هو :value.',
    'missing_with'         => 'يجب أن يكون حقل :attribute مفقوداً عند وجود :values.',
    'missing_with_all'     => 'يجب أن يكون حقل :attribute مفقوداً عند وجود :values.',
    'multiple_of'          => 'يجب أن تكون قيمة حقل :attribute من مضاعفات :value.',
    'not_in'               => 'القيمة المختارة في :attribute غير صالحة.',
    'not_regex'            => 'صيغة حقل :attribute غير صحيحة.',
    'numeric'              => 'يجب أن يكون حقل :attribute رقماً.',
    'password'             => [
        'letters'       => 'يجب أن يحتوي حقل :attribute على حرف واحد على الأقل.',
        'mixed'         => 'يجب أن يحتوي حقل :attribute على حرف كبير وحرف صغير على الأقل.',
        'numbers'       => 'يجب أن يحتوي حقل :attribute على رقم واحد على الأقل.',
        'symbols'       => 'يجب أن يحتوي حقل :attribute على رمز واحد على الأقل.',
        'uncompromised' => 'تم العثور على :attribute في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present'              => 'يجب تقديم حقل :attribute.',
    'present_if'           => 'يجب تقديم حقل :attribute عندما يكون :other هو :value.',
    'present_unless'       => 'يجب تقديم حقل :attribute إلا إذا كان :other هو :value.',
    'present_with'         => 'يجب تقديم حقل :attribute عند وجود :values.',
    'present_with_all'     => 'يجب تقديم حقل :attribute عند وجود :values.',
    'prohibited'           => 'حقل :attribute ممنوع.',
    'prohibited_if'        => 'حقل :attribute ممنوع عندما يكون :other هو :value.',
    'prohibited_unless'    => 'حقل :attribute ممنوع إلا إذا كان :other في :values.',
    'prohibits'            => 'حقل :attribute يمنع وجود :other.',
    'regex'                => 'صيغة حقل :attribute غير صحيحة.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_array_keys'  => 'يجب أن يحتوي حقل :attribute على مدخلات لـ: :values.',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عندما يتم قبول :other.',
    'required_unless'      => 'حقل :attribute مطلوب إلا إذا كان :other في :values.',
    'required_with'        => 'حقل :attribute مطلوب عند وجود :values.',
    'required_with_all'    => 'حقل :attribute مطلوب عند وجود :values.',
    'required_without'     => 'حقل :attribute مطلوب عند عدم وجود :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من :values.',
    'same'                 => 'يجب أن يتطابق حقل :attribute مع :other.',
    'size'                 => [
        'array'   => 'يجب أن يحتوي حقل :attribute على :size عنصر.',
        'file'    => 'يجب أن يكون حجم ملف :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute :size.',
        'string'  => 'يجب أن يكون طول نص :attribute :size حرفاً.',
    ],
    'starts_with'          => 'يجب أن يبدأ حقل :attribute بأحد القيم التالية: :values.',
    'string'               => 'يجب أن يكون حقل :attribute نصاً.',
    'timezone'             => 'يجب أن يكون حقل :attribute منطقة زمنية صحيحة.',
    'unique'               => 'قيمة :attribute مستخدمة من قبل.',
    'uploaded'             => 'فشل في تحميل :attribute.',
    'uppercase'            => 'يجب أن يكون حقل :attribute بأحرف كبيرة.',
    'url'                  => 'يجب أن يكون حقل :attribute رابط URL صحيح.',
    'ulid'                 => 'يجب أن يكون حقل :attribute ULID صحيح.',
    'uuid'                 => 'يجب أن يكون حقل :attribute UUID صحيح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'choices'    => [
            'size' => 'يجب أن يكون هناك 3 خيارات بالضبط',
        ],
        'attachment' => [
            'mimes' => 'نوع الملف المرفق غير مدعوم. المسموح: pdf, doc, docx, zip, jpg, jpeg, png',
            'max'   => 'حجم الملف لا يجب أن يتجاوز 5 ميجابايت',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes'           => [
        'name'                             => 'الاسم',
        'email'                            => 'البريد الإلكتروني',
        'password'                         => 'كلمة المرور',
        'password_confirmation'            => 'تأكيد كلمة المرور',
        'first_name'                       => 'الاسم الأول',
        'last_name'                        => 'الاسم الأخير',
        'phone'                            => 'رقم الهاتف',
        'address'                          => 'العنوان',
        'city'                             => 'المدينة',
        'state'                            => 'المقاطعة',
        'zip_code'                         => 'الرمز البريدي',
        'dob'                              => 'تاريخ الميلاد',
        'major_id'                         => 'التخصص',
        'image'                            => 'صورة الملف الشخصي',
        'gender'                           => 'الجنس',
        'country_id'                       => 'محقق الدولة',
        'bio'                              => 'السيرة الذاتية',
        'hour_price'                       => 'سعر الساعة',
        'is_available'                     => 'متاح',
        'is_active'                        => 'نشط',
        'city_id'                          => 'معرف المدينة',
        'school_id'                        => 'معرف المدرسة',
        'exam_date'                        => 'تاريخ الامتحان',
        'age'                              => 'العمر',
        'section'                          => 'الشعبة',

        'attempt_id'                       => 'معرف المحاولة',
        'question_id'                      => 'معرف السؤال',
        'choice_id'                        => 'معرف الخيار',
        'questionnaire'                    => 'الاستبيان',
        'text'                             => 'النص',
        'difficulty'                       => 'مستوى الصعوبة',
        'kind'                             => 'نوع السؤال',
        'points'                           => 'النقاط',
        'choices'                          => 'الخيارات',
        'choices.*.text'                   => 'نص الخيار',
        'choices.*.is_correct'             => 'الإجابة الصحيحة',
        'question'                         => 'نص السؤال',
        'explanation'                      => 'شرح السؤال',
        'result'                           => 'النتيجة',
        'questions'                        => 'الأسئلة',
        'questions.*.question'             => 'نص السؤال',
        'questions.*.difficulty'           => 'مستوى الصعوبة',
        'questions.*.kind'                 => 'نوع السؤال',
        'questions.*.image'                => 'صورة السؤال',
        'questions.*.explanation'          => 'شرح السؤال',
        'questions.*.result'               => 'النتيجة',
        'questions.*.choices'              => 'خيارات السؤال',
        'questions.*.choices.*.answer'     => 'نص الخيار',
        'questions.*.choices.*.is_correct' => 'الإجابة الصحيحة',

        // Recipe attributes
        'title'                            => 'عنوان الوصفة',
        'type'                             => 'نوع الوصفة',
        'course_type'                      => 'نوع الطبق',
        'prep_time'                        => 'وقت التحضير',
        'calories'                         => 'السعرات الحرارية',
        'protein'                          => 'البروتين',
        'carbs'                            => 'الكربوهيدرات',
        'fat'                              => 'الدهون',
        'ingredients'                      => 'المكونات',
        'steps'                            => 'خطوات التحضير',
        'portion_size_grams'               => 'حجم الحصة (جرام)',
        'allergies'                        => 'الحساسيات',
        'allergies.*'                      => 'الحساسية',
        'diet_plans'                       => 'خطط التغذية',
        'diet_plans.*'                     => 'خطة التغذية',

        'lesson'                           => [
            'name'       => 'اسم الدرس',
            'key'        => 'المفتاح',
            'difficulty' => 'مستوى الصعوبة',
            'kind'       => 'نوع الدرس',
            'image'      => 'صورة الدرس',
        ],
    ],

    'lesson'               => [
        'name'       => [
            'required' => 'اسم الدرس مطلوب',
            'max'      => 'اسم الدرس يجب ألا يتجاوز 255 حرف',
        ],
        'key'        => [
            'required' => 'المفتاح مطلوب',
            'unique'   => 'هذا المفتاح مستخدم بالفعل',
        ],
        'difficulty' => [
            'required' => 'مستوى الصعوبة مطلوب',
            'in'       => 'مستوى الصعوبة يجب أن يكون: سهل، متوسط، أو صعب',
        ],
        'kind'       => [
            'required' => 'نوع الدرس مطلوب',
            'in'       => 'نوع الدرس يجب أن يكون: لفظي أو كمي',
        ],
        'image'      => [
            'mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
            'max'   => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ],
    ],

    'recipe'               => [
        'title'              => [
            'required' => 'عنوان الوصفة مطلوب',
        ],
        'image'              => [
            'required' => 'صورة الوصفة مطلوبة',
            'image'    => 'يجب أن يكون الملف صورة',
        ],
        'type'               => [
            'required' => 'نوع الوصفة مطلوب',
        ],
        'course_type'        => [
            'required' => 'نوع الطبق مطلوب',
        ],
        'prep_time'          => [
            'required' => 'وقت التحضير مطلوب',
        ],
        'calories'           => [
            'required' => 'السعرات الحرارية مطلوبة',
        ],
        'protein'            => [
            'required' => 'البروتين مطلوب',
        ],
        'carbs'              => [
            'required' => 'الكربوهيدرات مطلوبة',
        ],
        'fat'                => [
            'required' => 'الدهون مطلوبة',
        ],
        'allergies'          => [
            'exists' => 'الحساسية المحددة غير موجودة',
        ],
        'diet_plans'         => [
            'exists' => 'خطة التغذية المحددة غير موجودة',
        ],
    ],

];
