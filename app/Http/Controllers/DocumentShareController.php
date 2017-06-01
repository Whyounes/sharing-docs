<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\DeleteRequest;
use App\Http\Requests\Shares\CreateRequest;
use App\Models\Document;
use App\Models\DocumentShare;
use Illuminate\Support\Facades\Lang;

class DocumentShareController extends Controller
{
    public function share(CreateRequest $request, Document $document)
    {
        /** @var DocumentShare $share */
        $share = DocumentShare::query()->create([
            'document_id' => $document->id,
            'user_id' => $request->get('user'),
            'views_count' => 0,
        ]);

        return redirect()->route('documents.show', [$share->document])
            ->with('messages', [Lang::get('general.document_shared')]);
    }

    public function delete(DeleteRequest $request, DocumentShare $share)
    {
        $share->delete();

        return redirect()->back()
            ->with('messages', [Lang::get('general.document_share_deleted')]);
    }
}
