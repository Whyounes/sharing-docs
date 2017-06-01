<?php

namespace App\Http\Requests\Documents;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowRequest extends FormRequest
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

        return (int)$user->id === $document->user_id || // to show document, you must be the owner OR
            $document->shares()->where('user_id', $user->id)->count(); // the document must be shared with you
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
