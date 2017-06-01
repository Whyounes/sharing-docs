<?php

namespace App\Http\Requests\Documents;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteRequest extends FormRequest
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
        return [];
    }
}
