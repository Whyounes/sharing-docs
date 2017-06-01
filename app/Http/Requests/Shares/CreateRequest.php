<?php

namespace App\Http\Requests\Shares;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'user' => 'required|exists:users,id', // to share a document, you must specify the user to be shared with.
        ];
    }
}
