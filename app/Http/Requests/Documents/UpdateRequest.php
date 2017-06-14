<?php

namespace App\Http\Requests\Documents;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /**
         * @var User $user
         * @var Document $document
         */
        $user = Auth::user();
        $document = $this->route('document');

        return (int)$user->id === (int)$document->user_id;
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
            'name' => 'required_if:document|unique:documents,id,user_id,'.$user->id, // document name must be unique for the user
            'document' => 'sometimes|file|max:100000' // document must be a file and max size is 10M
        ];
    }
}
