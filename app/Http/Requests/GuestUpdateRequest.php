<?php

namespace App\Http\Requests;

use App\Rules\NineCharacters;
use App\Rules\TwelveCharacters;
use App\Guest;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GuestUpdateRequest extends FormRequest
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
        $guest = Guest::findOrFail($this->id);
        return [
            'name' => 'required',
            'surname' => 'required|string',
            'email' => ['email','required',Rule::unique('guests')->ignore($guest->id)],
            'city' => 'required',
            'p_id' => ['required','string', new NineCharacters(),Rule::unique('guests')->ignore($guest->id)],
            'jmbg' => ['required','string', new TwelveCharacters(), Rule::unique('guests')->ignore($guest->id)],
        ];
    }
}
