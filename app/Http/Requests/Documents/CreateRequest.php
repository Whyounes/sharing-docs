<?php

namespace App\Http\Requests\Documents;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // everyone is authorized to upload
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var User $user */
        $user = Auth::user();

        return [
            'name' => [
                'required',
                Rule::unique('documents')->where(function (Builder $query) use($user) {
                    $query->where('user_id', $user->id);
                })
            ], // document name must be unique for the user
            'document' => 'required|file|max:10000' // document must be a file and max size is 10M
        ];
    }
}
