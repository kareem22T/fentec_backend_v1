<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Invetation_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\SavePhotoTrait;
use App\Traits\SendEmailTrait;
use Carbon\Carbon;
class RegisterController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;
    use SendEmailTrait;

    public function register(Request $request)
    {
        $lang = $request->lang ? $request->lang :  'en';
        $error_msgs = [
            "email_required" => [
                "en" => "Please enter your email address.",
                "fr" => "Veuillez entrer votre adresse e-mail.",
                "ar" => "الرجاء إدخال عنوان البريد الإلكتروني الخاص بك.",
            ],
            "email_email" => [
                "en" => "Please enter a valid email address.",
                "fr" => "S'il vous plaît, mettez une adresse email valide.",
                "ar" => "يرجى إدخال عنوان بريد إلكتروني صالح.",
            ],
            "phone_required" => [
                "en" => "Please enter your phone number.",
                "fr" => "Veuillez entrer votre numéro de téléphone.",
                "ar" => "يرجى إدخال رقم الهاتف الخاص بك.",
            ],
            "password_required" => [
                "en" => "Please enter a password.",
                "fr" => "Veuillez entrer un mot de passe.",
                "ar" => "الرجاء إدخال كلمة المرور.",
            ],
            "password_min" => [
                "en" => "Password should be at least 8 characters long.",
                "fr" => "Le mot de passe doit comporter au moins 8 caractères.",
                "ar" => "يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.",
            ],
            "email_unique" => [
                "en" => "This email address already exists.",
                "fr" => "Cette adresse email existe déja.",
                "ar" => "عنوان البريد الإلكتروني هذا موجود من قبل.",
            ],
            "phone_unique" => [
                "en" => "This phone number already exists.",
                "fr" => "Ce numéro de téléphone existe déjà.",
                "ar" => "رقم الهاتف هذا موجود بالفعل.",
            ],
            "register_successfuly" => [
                "en" => "Register successfuly",
                "fr" => "Inscrivez-vous avec succès",
                "ar" => "تم التسجيل بنجاح",
            ],
        ];

        if (!$request->sign_up_type) :

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'unique:users,email', 'email'],
            'phone' => 'required|unique:users,phone',
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => $error_msgs["email_required"][$lang],
            'email.email' => $error_msgs["email_email"][$lang],
            'phone.required' => $error_msgs["phone_required"][$lang],
            'email.unique' => $error_msgs["email_unique"][$lang],
            'phone.unique' => $error_msgs["phone_unique"][$lang],
            'password.required' => $error_msgs["password_required"][$lang],
            'password.min' => $error_msgs["password_min"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        $createUser = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($createUser) :
            $token = $createUser->createToken('token')->plainTextToken;
            return
                $this->jsonData(
                    true,
                    $createUser->verify,
                    $error_msgs["register_successfuly"][$lang],
                    [],
                    [
                        'id' => $createUser->id,
                        'email' => $createUser->email,
                        'phone' => $createUser->phone,
                        'token' => $token
                    ]
                );
        endif;
        endif;
    }

    public function testAccountExists(Request $request) {
        $lang = $request->lang ? $request->lang :  'en';
        $error_msgs = [
            "email_required" => [
                "en" => "Please enter your email address.",
                "fr" => "Veuillez entrer votre adresse e-mail.",
                "ar" => "الرجاء إدخال عنوان البريد الإلكتروني الخاص بك.",
            ],
            "not_google" => [
                "en" => "This account is not registered with google.",
                "fr" => "Ce compte n'est pas enregistré sur Google.",
                "ar" => "هذا الحساب ليس مسجل بواسطة جوجل.",
            ],
            "not_exists" => [
                "en" => "This account is not registered.",
                "fr" => "Ce compte n'est pas enregistré.",
                "ar" => "هذا الحساب ليس مسجل .",
            ],
            "email_email" => [
                "en" => "Please enter a valid email address.",
                "fr" => "S'il vous plaît, mettez une adresse email valide.",
                "ar" => "يرجى إدخال عنوان بريد إلكتروني صالح.",
            ],
            "phone_required" => [
                "en" => "Please enter your phone number.",
                "fr" => "Veuillez entrer votre numéro de téléphone.",
                "ar" => "يرجى إدخال رقم الهاتف الخاص بك.",
            ],
            "password_required" => [
                "en" => "Please enter a password.",
                "fr" => "Veuillez entrer un mot de passe.",
                "ar" => "الرجاء إدخال كلمة المرور.",
            ],
            "password_min" => [
                "en" => "Password should be at least 8 characters long.",
                "fr" => "Le mot de passe doit comporter au moins 8 caractères.",
                "ar" => "يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.",
            ],
            "email_unique" => [
                "en" => "This email address already exists.",
                "fr" => "Cette adresse email existe déja.",
                "ar" => "عنوان البريد الإلكتروني هذا موجود من قبل.",
            ],
            "phone_unique" => [
                "en" => "This phone number already exists.",
                "fr" => "Ce numéro de téléphone existe déjà.",
                "ar" => "رقم الهاتف هذا موجود بالفعل.",
            ],
            "register_successfuly" => [
                "en" => "Register successfuly",
                "fr" => "Inscrivez-vous avec succès",
                "ar" => "تم التسجيل بنجاح",
            ],
        ];

        $user = User::where('email', $request->email)->first();
        if ($user) {
            return $this->jsondata(false, null, '', $error_msgs["email_unique"][$lang], []);
        }
    }

    public function regWithGoogle(Request $request) {
        $lang = $request->lang ? $request->lang :  'en';
        $error_msgs = [
            "email_required" => [
                "en" => "Please enter your email address.",
                "fr" => "Veuillez entrer votre adresse e-mail.",
                "ar" => "الرجاء إدخال عنوان البريد الإلكتروني الخاص بك.",
            ],
            "email_email" => [
                "en" => "Please enter a valid email address.",
                "fr" => "S'il vous plaît, mettez une adresse email valide.",
                "ar" => "يرجى إدخال عنوان بريد إلكتروني صالح.",
            ],
            "phone_required" => [
                "en" => "Please enter your phone number.",
                "fr" => "Veuillez entrer votre numéro de téléphone.",
                "ar" => "يرجى إدخال رقم الهاتف الخاص بك.",
            ],
            "password_required" => [
                "en" => "Please enter a password.",
                "fr" => "Veuillez entrer un mot de passe.",
                "ar" => "الرجاء إدخال كلمة المرور.",
            ],
            "password_min" => [
                "en" => "Password should be at least 8 characters long.",
                "fr" => "Le mot de passe doit comporter au moins 8 caractères.",
                "ar" => "يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.",
            ],
            "email_unique" => [
                "en" => "This email address already exists.",
                "fr" => "Cette adresse email existe déja.",
                "ar" => "عنوان البريد الإلكتروني هذا موجود من قبل.",
            ],
            "phone_unique" => [
                "en" => "This phone number already exists.",
                "fr" => "Ce numéro de téléphone existe déjà.",
                "ar" => "رقم الهاتف هذا موجود بالفعل.",
            ],
            "register_successfuly" => [
                "en" => "Register successfuly",
                "fr" => "Inscrivez-vous avec succès",
                "ar" => "تم التسجيل بنجاح",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'phone' => 'required|unique:users,phone',
        ], [
            'email.required' => $error_msgs["email_required"][$lang],
            'email.email' => $error_msgs["email_email"][$lang],
            'phone.required' => $error_msgs["phone_required"][$lang],
            'email.unique' => $error_msgs["email_unique"][$lang],
            'phone.unique' => $error_msgs["phone_unique"][$lang],
            'password.required' => $error_msgs["password_required"][$lang],
            'password.min' => $error_msgs["password_min"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        if ($request->sign_up_type && $request->sign_up_type === "Google") :
            if ($request->email) {
                $userExistis = User::where("email", $request->email)->where("join_type", "Google")->first();
                if ($userExistis) {
                    if (filter_var( $userExistis->email, FILTER_VALIDATE_EMAIL)) {
                        $credentials = ['email' => $userExistis->email, 'password' => "Google"];
                    } else {
                        $credentials = ['phone' => $userExistis->email, 'password' => "Google"];
                    }

                    if (Auth::attempt($credentials)) {
                        $user = Auth::user();
                        $token = $user->createToken('token')->plainTextToken;
                        return $this->jsonData(true, $user->verify, 'Successfully Operation', [], ['token' => $token]);
                    }
                }
            }

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'unique:users,email', 'email'],
                'phone' => 'required|unique:users,phone',
            ], [
                'email.required' => $error_msgs["email_required"][$lang],
                'email.email' => $error_msgs["email_email"][$lang],
                'phone.required' => $error_msgs["phone_required"][$lang],
                'email.unique' => $error_msgs["email_unique"][$lang],
                'phone.unique' => $error_msgs["phone_unique"][$lang],
                'password.required' => $error_msgs["password_required"][$lang],
                'password.min' => $error_msgs["password_min"][$lang],
            ]);
                if ($validator->fails()) {
                return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
            }

            $createUser = User::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'verify' => true,
                'password' => Hash::make("Google"),
                "join_type" => "Google"
            ]);

            if ($createUser) :
                $token = $createUser->createToken('token')->plainTextToken;
                return
                    $this->jsonData(
                        true,
                        $createUser->verify,
                        $error_msgs["register_successfuly"][$lang],
                        [],
                        [
                            'id' => $createUser->id,
                            'email' => $createUser->email,
                            'phone' => $createUser->phone,
                            'token' => $token
                        ]
                    );
            endif;
        endif;
    }

    public function loginWithGoogle(Request $request) {
        $lang = $request->lang ? $request->lang :  'en';
        $error_msgs = [
            "email_required" => [
                "en" => "Please enter your email address.",
                "fr" => "Veuillez entrer votre adresse e-mail.",
                "ar" => "الرجاء إدخال عنوان البريد الإلكتروني الخاص بك.",
            ],
            "not_google" => [
                "en" => "This account is not registered with google.",
                "fr" => "Ce compte n'est pas enregistré sur Google.",
                "ar" => "هذا الحساب ليس مسجل بواسطة جوجل.",
            ],
            "not_exists" => [
                "en" => "This account is not registered.",
                "fr" => "Ce compte n'est pas enregistré.",
                "ar" => "هذا الحساب ليس مسجل .",
            ],
            "email_email" => [
                "en" => "Please enter a valid email address.",
                "fr" => "S'il vous plaît, mettez une adresse email valide.",
                "ar" => "يرجى إدخال عنوان بريد إلكتروني صالح.",
            ],
            "phone_required" => [
                "en" => "Please enter your phone number.",
                "fr" => "Veuillez entrer votre numéro de téléphone.",
                "ar" => "يرجى إدخال رقم الهاتف الخاص بك.",
            ],
            "password_required" => [
                "en" => "Please enter a password.",
                "fr" => "Veuillez entrer un mot de passe.",
                "ar" => "الرجاء إدخال كلمة المرور.",
            ],
            "password_min" => [
                "en" => "Password should be at least 8 characters long.",
                "fr" => "Le mot de passe doit comporter au moins 8 caractères.",
                "ar" => "يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.",
            ],
            "email_unique" => [
                "en" => "This email address already exists.",
                "fr" => "Cette adresse email existe déja.",
                "ar" => "عنوان البريد الإلكتروني هذا موجود من قبل.",
            ],
            "phone_unique" => [
                "en" => "This phone number already exists.",
                "fr" => "Ce numéro de téléphone existe déjà.",
                "ar" => "رقم الهاتف هذا موجود بالفعل.",
            ],
            "register_successfuly" => [
                "en" => "Register successfuly",
                "fr" => "Inscrivez-vous avec succès",
                "ar" => "تم التسجيل بنجاح",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required'],
        ], [
            'email.required' => $error_msgs["email_required"][$lang],
            'email.email' => $error_msgs["email_email"][$lang],
            'phone.required' => $error_msgs["phone_required"][$lang],
            'email.unique' => $error_msgs["email_unique"][$lang],
            'phone.unique' => $error_msgs["phone_unique"][$lang],
            'password.required' => $error_msgs["password_required"][$lang],
            'password.min' => $error_msgs["password_min"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        if ($request->sign_up_type && $request->sign_up_type === "Google") :
            if ($request->email) {
                $userExistis = User::where("email", $request->email)->where("join_type", "Google")->first();
                $isUser = User::where("email", $request->email)->first();
                if ($userExistis) {
                    if (filter_var( $userExistis->email, FILTER_VALIDATE_EMAIL)) {
                        $credentials = ['email' => $userExistis->email, 'password' => "Google"];
                    } else {
                        $credentials = ['phone' => $userExistis->email, 'password' => "Google"];
                    }

                    if (Auth::attempt($credentials)) {
                        $user = Auth::user();
                        $token = $user->createToken('token')->plainTextToken;
                        return $this->jsonData(true, $user->verify, 'Successfully Operation', [], ['token' => $token]);
                    }
                }
                if ($isUser)
                    return $this->jsonData(false, null, 'Registration failed', [$error_msgs['not_google'][$lang]], []);
            }
        endif;

        return $this->jsonData(false, null, 'Successfully Operation', [$error_msgs['not_exists'][$lang]], []);
    }

    public function register2(Request $request) {
        $sort = $request->input('lang', 'en');

        $error_msgs = [
            "name_required" => [
                "en" => "Please enter your name.",
                "fr" => "S'il vous plaît entrez votre nom.",
                "ar" => "من فضلك أدخل إسمك.",
            ],
            "name_regex" => [
                "en" => "Please enter a valid name, at least your first two names.",
                "fr" => "Veuillez saisir un nom valide, au moins vos deux prénoms.",
                "ar" => "الرجاء إدخال اسم صالح، على الأقل الاسمين الأولين.",
            ],
            "dob_required" => [
                "en" => "Date of birth is required.",
                "fr" => "La date de naissance est requise.",
                "ar" => "تاريخ الميلاد مطلوب.",
            ],
            "dob_date" => [
                "en" => "Invalid date format.",
                "fr" => "Format de date invalide.",
                "ar" => "تنسيق التاريخ غير صالح.",
            ],
            "dob_before_or_equal" => [
                "en" => "You must be at least 15 years old.",
                "fr" => "Vous devez avoir au moins 15 ans.",
                "ar" => "يجب أن يكون عمرك 15 عامًا على الأقل.",
            ],
            "dob_after_or_equal" => [
                "en" => "You must be at most 70 years old.",
                "fr" => "Vous devez avoir au plus 70 ans.",
                "ar" => "يجب أن يكون عمرك 70 عامًا كحد أقصى.",
            ],
            "identity_required" => [
                "en" => "Please upload your identity photo.",
                "fr" => "Veuillez télécharger votre photo d'identité.",
                "ar" => "يرجى تحميل صورة هويتك.",
            ],
            "identity_regex" => [
                "en" => "Unsupported extension.",
                "fr" => "Extension non prise en charge.",
                "ar" => "امتداد غير مدعوم.",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z ]+$/',
            'dob' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(15)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(70)->format('Y-m-d'),
            ],
            'identity' => 'required'
        ], [
            'name.required' => $error_msgs["name_required"][$sort],
            'name.regex' => $error_msgs["name_regex"][$sort],
            'dob.required' => $error_msgs["dob_required"][$sort],
            'dob.date' => $error_msgs["dob_date"][$sort],
            'dob.before_or_equal' => $error_msgs["dob_before_or_equal"][$sort],
            'dob.after_or_equal' => $error_msgs["dob_after_or_equal"][$sort],
            'identity.required' => $error_msgs["identity_required"][$sort],
            'identity.regex' => $error_msgs["identity_regex"][$sort],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();
        $user->name = $request->name;
        $user->dob = $request->dob;
        $user->approved = 0;
        $user->rejected = 0;
        $user->rejection_reason = null;
        $user->approving_msg_seen = 0;

        if ($request->photo) :
            $disk = 'public';

            // Specify the path to the image within the storage disk
            $path = 'images/uploads/' . $user->photo_path;
            if (Storage::disk($disk)->exists($path))
                Storage::disk($disk)->delete($path);

            $profile_pic = $this->saveImg($request->photo, 'images/uploads', 'profile' . $user->id . "_" . time());
            $user->photo_path = $profile_pic;
        endif;

        if($request->identity) :
            $disk = 'public';

            // Specify the path to the image within the storage disk
            $path = 'images/uploads/' . $user->identity_path;
            if (Storage::disk($disk)->exists($path))
                Storage::disk($disk)->delete($path);

            $identity_pic = $this->saveImg($request->identity, 'images/uploads', 'identity' . $user->id . "_" . time());
            $user->identity_path = $identity_pic;
        endif;
        $user->save();

        if ($user) :
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $email = $admin->email;
                $msg_title = 'New Registeration Request';
                $msg_body =
                    $user->name . " is waiting for review their account. <a href=''>Show request</a>" . "<br>" . "<br>" .
                    "<strong>User Information: </strong>" . "<br>" .
                    "Name: " . $user->name . "<br>" .
                    "Name: " . $user->email . "<br>" .
                    "Name: " . $user->phone . "<br>";

                $this->sendEmail($email, $msg_title, $msg_body);
            }
            return
                $this->jsonData(
                    true,
                    $user->verify,
                    'Registered successfuly',
                    [],
                    [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ]
                );
        endif;

    }

    public function collectPoints(Request $request)  {
        $validator = Validator::make($request->all(), [
            'choice' => 'required',
            'code' => 'required_id:choice,3',
        ], [
            'code.required_if' => 'please enter your friend invetation code',
            'choice.required' => 'please chose where did you knew about the app.',
        ]);

        if ($request->choice == 3) {
            $code = Invetation_code::where('code', $request->code)->first();
            if (!$code)
                return $this->jsondata(false, null, 'invalid invetation code', ['Invaled invetation code'], []);

            $request->user()->coins = ((int) $request->user()->coins) + 10;
            $request->user()->save();
            $user_owner = User::find($code->user_owner->id);
            $user_owner->coins = ((int) $user_owner->coins) + 10;
            $user_owner->save();

            return
                $this->jsonData(
                    true,
                    true,
                    'You have won 10 points',
                    [],
                    []
                );

        } else {
            $request->user()->coins = ((int) $request->user()->coins) + 10;
            $request->user()->save();

            return
                $this->jsonData(
                    true,
                    true,
                    'You have won 10 points',
                    [],
                    []
                );
        }

    }

    public function getChargesHistory(Request $request) {
        if ($request->user()) :
            $history = User::with('chargeProcess')->find($request->user()->id);
            return $this->jsonData(true, $request->user()->verify, '', [], $history);
        else :
            return $this->jsonData(false, null, 'Account Not Found', [], []);
        endif;
    }

    public function login(Request $request)
    {
        $lang = $request->lang ? $request->lang :  'en';

        $error_msgs = [
            "email_required" => [
                "en" => "Please enter your email or phone number",
                "fr" => "Veuillez entrer votre email ou votre numéro de téléphone",
                "ar" => "الرجاء إدخال البريد الإلكتروني الخاص بك أو رقم الهاتف",
            ],
            "password_required" => [
                "en" => "Please enter your password",
                "fr" => "s'il vous plait entrez votre mot de passe",
                "ar" => "من فضلك أدخل رقمك السري",
            ],
            "incorrect_info" => [
                "en" => "Your email/phone number or password are incorrect",
                "fr" => "s'ilVotre nom d'utilisateur ou mot de passe sont incorrects",
                "ar" => "اسم المستخدم أو كلمة المرور غير صحيحة",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'emailorphone' => 'required',
            'password' => 'required|min:8',
        ], [
            'emailorphone.required' => $error_msgs["email_required"][$lang],
            'password.required' => $error_msgs["password_required"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        if (filter_var($request->input('emailorphone'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->input('emailorphone'), 'password' => $request->input('password')];
        } else {
            $credentials = ['phone' => $request->input('emailorphone'), 'password' => $request->input('password')];
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return $this->jsonData(true, $user->verify, 'Successfully Operation', [], ['token' => $token]);
        }
        return $this->jsonData(false, null, 'Faild Operation', [$error_msgs["incorrect_info"][$lang]], []);
    }

    public function sendVerfication(Request $request)
    {
        $code = rand(100000, 999999);

        $user = $request->user();
        $user->last_code = $code;
        $user->last_code_created_at = Carbon::now();
        $user->save();

        $email = $user->email;
        $msg_title = 'Verfication code';
        $msg_body = 'Your email verfication code is: <b>' . $code . '</b>';

        $this->sendEmail($email, $msg_title, $msg_body);

        if ($user) :
            return
                $this->jsonData(
                    true,
                    $user->verify,
                    'We have sent you a verification code on your email',
                    [],
                    [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ]
                );
        endif;
    }

    public function sendForgotCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Activation failed', [$validator->errors()->first()], []);
        }

        $code = rand(100000, 999999);

        $user = User::where("email", $request->email)->first();

        if ($user) :
            $user->last_code = $code;
            $user->last_code_created_at = Carbon::now();
            $user->save();

            $email = $user->email;
            $msg_title = 'Verfication code';
            $msg_body = 'Your email verfication code is: <b>' . $code . '</b>';

            $this->sendEmail($email, $msg_title, $msg_body);

            if ($user) :
                return
                    $this->jsonData(
                        true,
                        $user->verify,
                        'We have sent you a verification code on your email',
                        [],
                        [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                        ]
                    );
            endif;
        else:
            return
                $this->jsonData(
                    false,
                    false,
                    'Account faild',
                    ['There is no user with this email'],
                    []
                );
        endif;

    }

    public function activeAccount(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric|digits:6',
        ], [
            'code.required' => 'Please enter your verification code',
            'code.numeric' => 'The code must be a number',
            'code.digits' => 'The code must be a 6 digits',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Activation failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();

        if (Carbon::parse($user->last_code_created_at)->addMinutes(10)->isPast())
            $this->jsonData(
                false,
                $user->verify,
                'Account faild',
                ['verfication code has expired'],
                [
                ]
            );

        if ($request->code == $user->last_code) {
            $user->verify = true;
            $user->save();

            if ($user) :
                return
                    $this->jsonData(
                        true,
                        $user->verify,
                        'Account has been verified successfuly',
                        [],
                        [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                        ]
                    );
            endif;
        } else {
            return
                $this->jsonData(
                    false,
                    $user->verify,
                    'Account faild',
                    ['The code you entered is not correct, check your email again or click resend'],
                    [
                    ]
                );
        }
    }

    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric|digits:6',
            'password' => ['required', 'min:8', 'confirmed'],
            'email' => ['required', 'email'],
        ], [
            'code.required' => 'Please enter your verification code',
            'code.numeric' => 'The code must be a number',
            'code.digits' => 'The code must be a 6 digits',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password should be at least 8 characters long.',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Activation failed', [$validator->errors()->first()], []);
        }

        $user = User::where("email", $request->email)->first();

        if ($user):
            if (Carbon::parse($user->last_code_created_at)->addMinutes(10)->isPast())
                $this->jsonData(
                    false,
                    $user->verify,
                    'Account faild',
                    ['verfication code has expired'],
                    [
                    ]
                );

            if ($request->code == $user->last_code) {
                $user->password = Hash::make($request->password);
                $user->save();

                if ($user) :
                    return
                        $this->jsonData(
                            true,
                            $user->verify,
                            'Password has been changed successfuly',
                            [],
                            []
                        );
                endif;
            } else {
                return
                    $this->jsonData(
                        false,
                        $user->verify,
                        'Account faild',
                        ['The code you entered is not correct, check your email again or click resend'],
                        []
                    );
                }
        else:
            return
                $this->jsonData(
                    false,
                    false,
                    'Account faild',
                    ['There is no user with this email'],
                    []
                );
        endif;
    }

    public function changePassword(Request $request) {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, $user->verify, 'Change password failed', [$validator->errors()->first()], []);
        }

        $currentPassword = $request->old_password;

        if (!Hash::check($currentPassword, $user->password)) {
            return $this->jsondata(false, $user->verify, 'Change password', ['Incorrect old password'], []);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($user)
            return $this->jsondata(true, $user->verify, 'You have changed your password successfuly', [], []);

    }

    public function editEmail(Request $request) {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email',
        ],[
            'new_email.required' => 'please write an valid email',
            'new_email.email' => 'please write an valid email'
        ]);

        if ($validator->fails())
            return $this->jsondata(false, $user->verify, 'Change email failed', [$validator->errors()->first()], []);

        if ($user->email !== $request->new_email):
            $user->email = $request->new_email;
            $user->verify = 0;
            $user->save();
        else:
            return $this->jsondata(false, $user->verify, 'Change email failed', ["You have Enterd the old email"], []);
        endif;

        if ($user)
            return
                $this->jsondata(
                    true,
                    $user->verify, '
                    Your email has changed successfully!',
                    [],
                    [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                    ]
                );

    }

    public function editPhone(Request $request) {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'new_phone' => 'required',
        ],[
            'new_phone.required' => 'please write an valid phone number'
        ]);

        if ($validator->fails())
            return $this->jsondata(false, $user->verify, 'Change phone failed', [$validator->errors()->first()], []);

        if ($user->phone !== $request->new_phone):
            $user->phone = $request->new_phone;
            $user->save();
        endif;

        if ($user)
            return
                $this->jsondata(
                    true,
                    $user->verify,
                    'Your phone number has changed successfully!',
                    [],
                    [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                    ]
                );

    }
    public function editProfilePic(Request $request) {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'profile_img' => 'required',
        ],[
            'profile_img.required' => 'please upload profile pic'
        ]);

        if ($validator->fails())
            return $this->jsondata(false, $user->verify, 'Change profile pic failed', [$validator->errors()->first()], []);

        if ($request->profile_img) :
            $disk = 'public';

            // Specify the path to the image within the storage disk
            $path = 'images/uploads/' . $user->photo_path;
            if (Storage::disk($disk)->exists($path))
                Storage::disk($disk)->delete($path);

            $profile_pic = $this->saveImg($request->profile_img, 'images/uploads', 'profile' . $user->id . "_" . time());
            $user->photo_path = $profile_pic;
            $user->save();
        endif;

        if ($user)
            return
                $this->jsondata(
                    true,
                    $user->verify,
                    'Your profile pic changed successfully!',
                    [],
                    [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                    ]
                );

    }

    public function getUser(Request $request)
    {
        if ($request->user()) :
            if ($request->notification_token) :
                $request->user()->notification_token = $request->notification_token;
                $request->user()->save();
            endif;
            return $this->jsonData(true, $request->user()->verify, '', [], ['user' => $request->user()]);
        else :
            return $this->jsonData(false, null, 'Account Not Found', [], []);
        endif;
    }

    public function setNotificationToken(Request $request)
    {
        if ($request->user()) :
            if ($request->notification_token) :
                $request->user()->notification_token = $request->notification_token;
                $request->user()->save();
            endif;
            return $this->jsonData(true, $request->user()->verify, '', [], []);
        endif;
    }

    public function getNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        $user = User::find($request->user_id);

        if ($validator->fails())
            return $this->jsondata(false, $user->verify, 'Get Notification failed', [$validator->errors()->first()], []);

        $notifications = Notification::latest()->where('user_id', $request->user_id)
        ->orWhereNull('user_id')
        ->where("created_at", '>=', $user->created_at)
        ->paginate(15);

        $user = User::find("user_id");
        if ($user) :
            $user->has_unseened_notifications = false;
            $user->save();
        endif;

        return $notifications;

    }

    public function seenApprovingMsg(Request $request)
    {
        if ($request->user()) :
                $request->user()->approving_msg_seen = true;
                $request->user()->save();
            return $this->jsonData(true, $request->user()->verify, '', [], []);
        endif;
    }

    public function useCoupon(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails())
            return $this->jsondata(false, null, 'Use Coupon failed', [$validator->errors()->first()], []);

        $coupon = Coupon::where("code", $request->code)->first();

        if (!$coupon)
            return $this->jsondata(false, null, 'Use Coupon failed', ["Invalid Coupon"], []);

        if ($coupon->hasExpired())
            return $this->jsondata(false, null, 'Use Coupon failed', ["Coupon Expired"], []);

        if ($coupon->hasExpired())
            return $this->jsondata(false, null, 'Use Coupon failed', ["Coupon Expired"], []);

        if ($coupon->hasNotStarted())
            return $this->jsondata(false, null, 'Use Coupon failed', ["Coupon Will start in " . $coupon->start_in], []);

        $user = $request->user();

        // Check if the user already has the coupon attached
        if (!$user->coupons->contains($coupon->id)) {
            // If the coupon is not already attached, attach it
            $user->coupons()->attach([$coupon->id]);
            $user->coins = (int) $user->coins + (int) $coupon->gift;
            $user->save();
            // Return a response
            return $this->jsondata(true, null, 'You win ' . $coupon->gift . ' Coins', [], [$coupon->gift]);
        } else {
            // If the coupon is already attached, return a response indicating that
            return $this->jsondata(false, 'You have use this coupon before', null, [], []);
        }
    }

    public function logout (Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        if ($user)
            return $this->jsonData(true, 0, 'Logged out successfuly', [], []);
        else
            return $this->jsonData(false, null, 'could not logout', ['Server error try again later'], []);


    }
}
