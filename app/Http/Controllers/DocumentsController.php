<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\CreateRequest;
use App\Http\Requests\Documents\DeleteRequest;
use App\Http\Requests\Documents\ShowRequest;
use App\Http\Requests\Documents\UpdateRequest;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return view('documents.index', [
            'documents' => $authUser->documents,
            'sharedDocuments' => $authUser->shares,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        /**
         * @var User $authUser
         * @var Document $document
         */
        $authUser = Auth::user();
        $document = new Document([
            'name' => $request->get('name'),
            'user_id' => $authUser->id,
        ]);

        $storagePath = config('app.document_storage_path');
        $documentPath = $this->storeUserDocument($request, $document);

        $document->path = $storagePath . DIRECTORY_SEPARATOR . $authUser->id . DIRECTORY_SEPARATOR . $documentPath;
        $document->size = Storage::size($document->path);
        $document->mime_type = Storage::mimeType($document->path);

        $document->save();

        return redirect()->route('documents.index')
            ->with('messages', [Lang::get('general.document_created')]);
    }

    /**
     * Upload the document
     *
     * @param Request $request
     * @param Document $document
     * @return string
     */
    protected function storeUserDocument(Request $request, Document $document)
    {
        /*
         * storeUserDocument pour enrigistrer le fichier envoyé
           il retourne le chemin du fichier
           apres en utilize ce chemin pour modifier les attributes du model $document
           et en enregistre a la fin et en redirect
        */
        $storagePath = config('app.document_storage_path');

        if ($request->file('document') && $request->file('document')->isValid()) {
            // If updating, we should delete old file
            if ($document && $document->path) {
                Storage::delete($document->path);
            }

            $uploadedFile = $request->file('document');
            $fileName = str_random(10) . "." . $uploadedFile->extension();

            Storage::put(
                $storagePath . DIRECTORY_SEPARATOR . $document->user_id . DIRECTORY_SEPARATOR . $fileName,
                file_get_contents($uploadedFile->getRealPath())
            );

            return $fileName;
        }

        return $document ? $document->path : '';
    }

    /**
     * Display the specified resource.
     *
     * @param ShowRequest $request
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Document $document)
    {
        // Load shares of the document
        $document->load(['shares']);
        
        // ncrement views count

        return view('documents.show', ['document' => $document]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    
    public function edit(Document $document)
    {
        return view('documents.show', ['document' => $document]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest|Request $request
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Document $document)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $document->fill($request->intersect(['name']));

        $storagePath = config('app.document_storage_path');
        $documentPath = $this->storeUserDocument($request, $document);

        $document->path = $storagePath . DIRECTORY_SEPARATOR . $authUser->id . DIRECTORY_SEPARATOR . $documentPath;
        $document->size = Storage::size($document->path);
        $document->mime_type = Storage::mimeType($document->path);

        $document->save();

        return redirect()->back()->with('messages', [Lang::get('general.document_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRequest|Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Document $document)
    {
        $document->shares()->delete();

        Storage::delete($document->path);
        $document->delete();

        return redirect()->route('documents.index')->with('messages', [Lang::get('general.document_deleted')]);
    }
}
