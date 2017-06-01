<?php

namespace App\Http\Requests\Shares;

use App\Models\Document;
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
        // get the document from the URL parameter
        /** @var Document $document */
        $document = $this->route('document');

        // only authorize if the document is owned by the current authenticated user
        return (int)Auth::user()->id === (int)$document->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return []; // no parameter for deleting a share
    }
}
