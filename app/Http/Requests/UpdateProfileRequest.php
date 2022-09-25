<?php

namespace App\Http\Requests;

use App\Models\Profile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('profile_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'integer',
                'exists:users,id',
                'required',
            ],
            'dob' => [
                'date_format:' . config('project.date_format'),
                'nullable',
            ],
            'sex' => [
                'nullable',
                'in:' . implode(',', Arr::pluck(Profile::SEX_SELECT, 'value')),
            ],
            'religion' => [
                'nullable',
                'in:' . implode(',', Arr::pluck(Profile::RELIGION_SELECT, 'value')),
            ],
            'ethnicity' => [
                'nullable',
                'in:' . implode(',', Arr::pluck(Profile::ETHNICITY_SELECT, 'value')),
            ],
        ];
    }
}
