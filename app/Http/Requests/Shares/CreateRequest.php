<?php

namespace App\Http\Requests\Shares;

use App\Models\Document;
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
        // get the document from the URL parameter
        /** @var Document $document */
        $document = $this->route('document');

        // only authorize if the document is owned by the current authenticated user
        return (int)$this->user()->id === (int)$document->user_id;
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
            'user' => [
                'required',
                Rule::exists('users', 'email')->where(function(Builder $query) use($user) {
                    $query->where('id', '!=', $user->id);
                })
            ], // to share a document, you must specify the user to be shared with.
        ];
    }
}
