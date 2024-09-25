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
use Illuminate\Support\Str;

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
            return $this->jsondata(false, null, '', [$error_msgs["email_unique"][$lang]], []);
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
            'name' => 'required',
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

    public function getOrCreateInvitationCode(Request $request)
    {
        $user = $request->user();
        // Check if the user already has an invitation code
        $existingCode = Invetation_code::where('user_id', $user->id)->first();

        if ($existingCode) {
            // Return the existing invitation code
            return response()->json([
                'invitation_code' => $existingCode->code,
            ]);
        }

        // Generate a unique code
        do {
            $newCode = Str::random(8); // Generates a random 8-character code
        } while (Invetation_code::where('code', $newCode)->exists());

        // Save the new code with the user ID
        $invitationCode = Invetation_code::create([
            'user_id' => $user->id,
            'code' => $newCode,
        ]);

        // Return the newly created code
        return response()->json([
            'invitation_code' => $invitationCode->code,
        ]);
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

            $request->user()->coins = ((float) $request->user()->coins) + 10;
            $request->user()->save();
            $user_owner = User::find($code->user_owner->id);
            $user_owner->coins = ((float) $user_owner->coins) + 10;
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
            $request->user()->coins = ((float) $request->user()->coins) + 10;
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
            $history = User::with(['chargeProcess' => function($q) {
                $q->latest();
            }])->find($request->user()->id);
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
            "success" => [
                "en" => "Successfuly operation",
                "fr" => "Opération avec succès",
                "ar" => "تمت العملية بنجاح",
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
            return $this->jsonData(true, $user->verify, $error_msgs["success"][$lang], [], ['token' => $token]);
        }
        return $this->jsonData(false, null, 'Faild Operation', [$error_msgs["incorrect_info"][$lang]], []);
    }

    public function sendVerfication(Request $request)
    {
        $lang = $request->lang ? $request->lang :  'en';

        $error_msgs = [
            "msg" => [
                "en" => "The verification code has been sent to your E-MAIL",
                "fr" => "Le code de vérification a été envoyé à votre adresse E-MAIL",
                "ar" => "لقد تم ارسال رمزاالثباتالى بريدك االلكتروني",
            ],
        ];

        $code = rand(100000, 999999);

        $user = $request->user();
        $user->last_code = $code;
        $user->last_code_created_at = Carbon::now();
        $user->save();

        $email = $user->email;
        $msg_title = 'Verfication code';
        $msg_body = 'عزيزي المستخدم';
        $msg_body .= '<br>';
        $msg_body .= 'شكرا لاختيارك FenTec Mobility للتنقل الذكي والصديق للبيئة';
        $msg_body .= '<br>';
        $msg_body .= 'رمز الاثبات الخاص بك هو: <b>' . $code . '</b>';
        $msg_body .= '<br>';
        $msg_body .= 'استمتع بيومك و حافظ على مركبتك';
        $msg_body .= '<br>';
        $msg_body .= 'Dear user';
        $msg_body .= '<br>';
        $msg_body .= 'Thank you for choosing FenTec Mobility for Smart and environmentally friendly mobility';
        $msg_body .= '<br>';
        $msg_body .= 'Your verification code is: <b>' . $code . '</b>';
        $msg_body .= '<br>';
        $msg_body .= 'Enjoy Your Day and maintain YOUR SCOOTER';

        $this->sendEmail($email, $msg_title, $msg_body);

        if ($user) :
            return
                $this->jsonData(
                    true,
                    $user->verify,
                    $error_msgs['msg'][$lang],
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
        $lang = $request->lang ? $request->lang :  'en';

        $error_msgs = [
            "code_sent" => [
                "en" => "'We have sent you a verification code on your email'",
                "fr" => "Nous vous avons envoyé un code de vérification sur votre e-mail",
                "ar" => "لقد أرسلنا لك رمز التحقق على بريدك الإلكتروني",
            ],
        ];

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
            $msg_body = 'عزيزي المستخدم';
            $msg_body .= '<br>';
            $msg_body .= 'شكرا لاختيارك FenTec Mobility للتنقل الذكي والصديق للبيئة';
            $msg_body .= '<br>';
            $msg_body .= 'رمز الاثبات الخاص بك هو: <b>' . $code . '</b>';
            $msg_body .= '<br>';
            $msg_body .= 'استمتع بيومك و حافظ على مركبتك';
            $msg_body .= '<br>';
            $msg_body .= 'Dear user';
            $msg_body .= '<br>';
            $msg_body .= 'Thank you for choosing FenTec Mobility for Smart and environmentally friendly mobility';
            $msg_body .= '<br>';
            $msg_body .= 'Your verification code is: <b>' . $code . '</b>';
            $msg_body .= '<br>';
            $msg_body .= 'Enjoy Your Day and maintain YOUR SCOOTER';

            $this->sendEmail($email, $msg_title, $msg_body);

            if ($user) :
                return
                    $this->jsonData(
                        true,
                        $user->verify,
                        $error_msgs['code_sent'][$lang],
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
        $lang = $request->lang ? $request->lang : 'en';

        $error_msgs = [
            "code_req" => [
                "en" => "Please enter your verification code",
                "fr" => "Veuillez entrer votre code de vérification",
                "ar" => "الرجاء إدخال رمز التحقق الخاص بك",
            ],
            "code_numeric" => [
                "en" => "The code must be a number",
                "fr" => "Le code doit être un nombre",
                "ar" => "يجب أن يكون الرمز رقماً",
            ],
            "code_digits" => [
                "en" => "The code must be 6 digits",
                "fr" => "Le code doit comporter 6 chiffres",
                "ar" => "يجب أن يتكون الرمز من 6 أرقام",
            ],
            "password_required" => [
                "en" => "Please enter a password.",
                "fr" => "Veuillez entrer un mot de passe.",
                "ar" => "يرجى إدخال كلمة مرور.",
            ],
            "password_min" => [
                "en" => "Password should be at least 8 characters long.",
                "fr" => "Le mot de passe doit comporter au moins 8 caractères.",
                "ar" => "يجب أن تكون كلمة المرور مكونة من 8 أحرف على الأقل.",
            ],
            "activation_failed" => [
                "en" => "Activation failed",
                "fr" => "L'activation a échoué",
                "ar" => "فشل التفعيل",
            ],
            "verification_expired" => [
                "en" => "Verification code has expired",
                "fr" => "Le code de vérification a expiré",
                "ar" => "انتهت صلاحية رمز التحقق",
            ],
            "password_changed" => [
                "en" => "Password has been changed successfully",
                "fr" => "Le mot de passe a été modifié avec succès",
                "ar" => "تم تغيير كلمة المرور بنجاح",
            ],
            "code_incorrect" => [
                "en" => "The code you entered is not correct, check your email again or click resend",
                "fr" => "Le code que vous avez entré est incorrect, vérifiez votre email ou cliquez sur renvoyer",
                "ar" => "الرمز الذي أدخلته غير صحيح، تحقق من بريدك الإلكتروني مرة أخرى أو انقر على إعادة الإرسال",
            ],
            "no_user" => [
                "en" => "There is no user with this email",
                "fr" => "Il n'y a pas d'utilisateur avec cet e-mail",
                "ar" => "لا يوجد مستخدم بهذا البريد الإلكتروني",
            ],
            "password_confirmation" => [
                "en" => "Password and its confirmation do not match!",
                "fr" => "Confirmation du mot de passe ne correspond pas!",
                "ar" => "تأكيد كلمة المرور غير متطابق.!",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric|digits:6',
            'password' => ['required', 'min:8', 'confirmed'],
            'email' => ['required', 'email'],
        ], [
            'code.required' => $error_msgs["code_req"][$lang],
            'code.numeric' => $error_msgs["code_numeric"][$lang],
            'code.digits' => $error_msgs["code_digits"][$lang],
            'password.required' => $error_msgs["password_required"][$lang],
            'password.min' => $error_msgs["password_min"][$lang],
            'password.confirmed' => $error_msgs["password_confirmation"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, $error_msgs["activation_failed"][$lang], [$validator->errors()->first()], []);
        }

        $user = User::where("email", $request->email)->first();

        if ($user) {
            if (Carbon::parse($user->last_code_created_at)->addMinutes(10)->isPast()) {
                return $this->jsonData(
                    false,
                    $user->verify,
                    $error_msgs["activation_failed"][$lang],
                    [$error_msgs["verification_expired"][$lang]],
                    []
                );
            }

            if ($request->code == $user->last_code) {
                $user->password = Hash::make($request->password);
                $user->save();

                if ($user) {
                    return $this->jsonData(
                        true,
                        $user->verify,
                        $error_msgs["password_changed"][$lang],
                        [],
                        []
                    );
                }
            } else {
                return $this->jsonData(
                    false,
                    $user->verify,
                    $error_msgs["activation_failed"][$lang],
                    [$error_msgs["code_incorrect"][$lang]],
                    []
                );
            }
        } else {
            return $this->jsonData(
                false,
                false,
                $error_msgs["activation_failed"][$lang],
                [$error_msgs["no_user"][$lang]],
                []
            );
        }
    }

    public function changePassword(Request $request) {
        $user = $request->user();
        $lang = $request->lang ? $request->lang : 'en';

        $error_msgs = [
            "old_password_required" => [
                "en" => "Please enter your old password",
                "fr" => "Veuillez entrer votre ancien mot de passe",
                "ar" => "يرجى إدخال كلمة المرور القديمة",
            ],
            "new_password_required" => [
                "en" => "Please enter a new password",
                "fr" => "Veuillez entrer un nouveau mot de passe",
                "ar" => "يرجى إدخال كلمة مرور جديدة",
            ],
            "new_password_min" => [
                "en" => "New password should be at least 8 characters long",
                "fr" => "Le nouveau mot de passe doit comporter au moins 8 caractères",
                "ar" => "يجب أن تكون كلمة المرور الجديدة مكونة من 8 أحرف على الأقل",
            ],
            "change_password_failed" => [
                "en" => "Change password failed",
                "fr" => "Échec du changement de mot de passe",
                "ar" => "فشل تغيير كلمة المرور",
            ],
            "incorrect_old_password" => [
                "en" => "Incorrect old password",
                "fr" => "Ancien mot de passe incorrect",
                "ar" => "كلمة المرور القديمة غير صحيحة",
            ],
            "password_changed" => [
                "en" => "You have changed your password successfully",
                "fr" => "Vous avez changé votre mot de passe avec succès",
                "ar" => "لقد قمت بتغيير كلمة المرور بنجاح",
            ],
            "password_confirmation" => [
                "en" => "Password and its confirmation do not match!",
                "fr" => "Confirmation du mot de passe ne correspond pas!",
                "ar" => "تأكيد كلمة المرور غير متطابق.!",
            ],
        ];

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'old_password.required' => $error_msgs["old_password_required"][$lang],
            'new_password.required' => $error_msgs["new_password_required"][$lang],
            'new_password.min' => $error_msgs["new_password_min"][$lang],
            'password.confirmed' => $error_msgs["password_confirmation"][$lang],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, $user->verify, $error_msgs["change_password_failed"][$lang], [$validator->errors()->first()], []);
        }

        $currentPassword = $request->old_password;

        if (!Hash::check($currentPassword, $user->password)) {
            return $this->jsondata(false, $user->verify, $error_msgs["change_password_failed"][$lang], [$error_msgs["incorrect_old_password"][$lang]], []);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($user) {
            return $this->jsondata(true, $user->verify, $error_msgs["password_changed"][$lang], [], []);
        }
    }
    public function translate($key, $lang = 'en') {
        $translations = [
            'en' => [
                'change_email_failed' => 'Change email failed',
                'old_email_error' => 'You have entered the old email',
                'email_changed_success' => 'Your email has changed successfully!',
                'change_phone_failed' => 'Change phone failed',
                'phone_changed_success' => 'Your phone number has changed successfully!',
                'change_profile_pic_failed' => 'Change profile pic failed',
                'profile_pic_changed_success' => 'Your profile pic changed successfully!',
                'required_email' => 'Please write a valid email',
                'invalid_email' => 'Please write a valid email',
                'required_phone' => 'Please write a valid phone number',
                'required_profile_pic' => 'Please upload a profile picture',
            ],
            'fr' => [
                'change_email_failed' => 'Échec du changement d\'email',
                'old_email_error' => 'Vous avez saisi l\'ancien email',
                'email_changed_success' => 'Votre email a été changé avec succès!',
                'change_phone_failed' => 'Échec du changement de numéro de téléphone',
                'phone_changed_success' => 'Votre numéro de téléphone a été changé avec succès!',
                'change_profile_pic_failed' => 'Échec du changement de photo de profil',
                'profile_pic_changed_success' => 'Votre photo de profil a été changée avec succès!',
                'required_email' => 'Veuillez saisir un email valide',
                'invalid_email' => 'Veuillez saisir un email valide',
                'required_phone' => 'Veuillez saisir un numéro de téléphone valide',
                'required_profile_pic' => 'Veuillez télécharger une photo de profil',
            ],
            'ar' => [
                'change_email_failed' => 'فشل تغيير البريد الإلكتروني',
                'old_email_error' => 'لقد أدخلت البريد الإلكتروني القديم',
                'email_changed_success' => 'تم تغيير بريدك الإلكتروني بنجاح!',
                'change_phone_failed' => 'فشل تغيير رقم الهاتف',
                'phone_changed_success' => 'تم تغيير رقم هاتفك بنجاح!',
                'change_profile_pic_failed' => 'فشل تغيير صورة الملف الشخصي',
                'profile_pic_changed_success' => 'تم تغيير صورة الملف الشخصي بنجاح!',
                'required_email' => 'يرجى كتابة بريد إلكتروني صالح',
                'invalid_email' => 'يرجى كتابة بريد إلكتروني صالح',
                'required_phone' => 'يرجى كتابة رقم هاتف صالح',
                'required_profile_pic' => 'يرجى تحميل صورة الملف الشخصي',
            ]
        ];

        return $translations[$lang][$key] ?? $translations['en'][$key];
    }

    public function editEmail(Request $request) {
        $user = $request->user();
        $lang = $request->get('lang', 'en');

        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email',
        ], [
            'new_email.required' => $this->translate('required_email', $lang),
            'new_email.email' => $this->translate('invalid_email', $lang),
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, $user->verify, $this->translate('change_email_failed', $lang), [$validator->errors()->first()], []);
        }

        if ($user->email !== $request->new_email) {
            $user->email = $request->new_email;
            $user->verify = 0;
            $user->save();
        } else {
            return $this->jsondata(false, $user->verify, $this->translate('change_email_failed', $lang), [$this->translate('old_email_error', $lang)], []);
        }

        return $this->jsondata(
            true,
            $user->verify,
            $this->translate('email_changed_success', $lang),
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
        $lang = $request->get('lang', 'en');

        $validator = Validator::make($request->all(), [
            'new_phone' => 'required',
        ], [
            'new_phone.required' => $this->translate('required_phone', $lang),
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, $user->verify, $this->translate('change_phone_failed', $lang), [$validator->errors()->first()], []);
        }

        if ($user->phone !== $request->new_phone) {
            $user->phone = $request->new_phone;
            $user->save();
        }

        return $this->jsondata(
            true,
            $user->verify,
            $this->translate('phone_changed_success', $lang),
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
        $lang = $request->get('lang', 'en');

        $validator = Validator::make($request->all(), [
            'profile_img' => 'required',
        ], [
            'profile_img.required' => $this->translate('required_profile_pic', $lang),
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, $user->verify, $this->translate('change_profile_pic_failed', $lang), [$validator->errors()->first()], []);
        }

        if ($request->profile_img) {
            $disk = 'public';

            // Specify the path to the image within the storage disk
            $path = 'images/uploads/' . $user->photo_path;
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }

            $profile_pic = $this->saveImg($request->profile_img, 'images/uploads', 'profile' . $user->id . "_" . time());
            $user->photo_path = $profile_pic;
            $user->save();
        }

        return $this->jsondata(
            true,
            $user->verify,
            $this->translate('profile_pic_changed_success', $lang),
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
            $user->coins = (float) $user->coins + (float) $coupon->gift;
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
