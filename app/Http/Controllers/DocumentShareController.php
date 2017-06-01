<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shares\DeleteRequest;
use App\Http\Requests\Shares\CreateRequest;
use App\Models\Document;
use App\Models\DocumentShare;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentShareController extends Controller
{
    public function share(CreateRequest $request, Document $document)
    {
        /** @var User $user */
        $user = User::where('email', $request->get('user'))->firstOrFail();

        DocumentShare::query()->create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'view_count' => 1, // because we are redirecting to show the document
        ]);

        return redirect()->route('documents.show', [$document])
            ->with('messages', [Lang::get('general.document_shared')]);
    }

    public function delete(DeleteRequest $request, Document $document, DocumentShare $share)
    {
        $share->delete();

        return redirect()->back()
            ->with('messages', [Lang::get('general.document_share_deleted')]);
    }
}
