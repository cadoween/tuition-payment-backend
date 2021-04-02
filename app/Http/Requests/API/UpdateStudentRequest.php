<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('student')->user->id,
            'class_id' => 'required',
            'tuition_id' => 'required',
            'nisn' => 'required|max:255',
            'nis' => 'required|max:255',
            'address' => 'required|string',
            'telp_number' => 'required|max:255',
        ];
    }
}
