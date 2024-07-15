<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use App\Traits\SendEmailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;
    use SendEmailTrait;

    public function putSurvey(Request $request) {
        $validator = Validator::make($request->all(), [
            'reaction' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }


        $user = $request->user();

        $sur = Survey::create([
            "user_id" => $user->id,
            "reaction" => $request->reaction,
            "comment" => $request->comment ??  null,
        ]);

        if ($sur)
            return $this->jsondata(true, $sur, 'Survey created successfully', [], []);

    }

    public function getRates() {
        $trips = Survey::with(["user"])->paginate(15);
        return  $this->jsondata(true, null, 'Successful Operation', [], $trips);
    }
}
