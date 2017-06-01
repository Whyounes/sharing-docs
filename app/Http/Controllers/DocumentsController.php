<?php

namespace App\Http\Controllers;

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

        return view('documents', [
            'documents.index' => $authUser->documents
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $documentPath = $this->storeUserDocument($request, $document);
        $document->path = $documentPath;
        $document->size = Storage::size($documentPath);
        $document->mime_type = Storage::mimeType($documentPath);
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
        $storagePath = config('app.document_storage_path');

        if ($request->file('document') && $request->file('document')->isValid()) {
            // If updating, we should delete old file
            if ($document && $document->path) {
                Storage::delete($storagePath . DIRECTORY_SEPARATOR . $document->user_id . DIRECTORY_SEPARATOR . $document->path);
            }

            $uploadedFile = $request->file('document');
            $fileName = str_random(10) . "." . $uploadedFile->extension();

            Storage::put(
                $storagePath . "/" . $document->user_id . "/" . $fileName,
                file_get_contents($uploadedFile->getRealPath())
            );

            return $fileName;
        }

        return $document ? $document->path : '';
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        // Load shares of the document
        $document->load(['shares']);

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
     * @param  \Illuminate\Http\Request $request
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $document->fill($request->intersect(['name']));

        $documentPath = $this->storeUserDocument($request, $document);
        $document->path = $documentPath;
        $document->size = Storage::size($documentPath);
        $document->mime_type = Storage::mimeType($documentPath);
        $document->save();

        return redirect()->back()->with('messages', [Lang::get('general.document_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->shares()->delete();

        $storagePath = config('app.document_storage_path');
        Storage::delete($storagePath . "/" . $document->user_id . "/" . $document->path);
        $document->delete();

        return redirect()->route('documents.index')->with('messages', [Lang::get('general.document_deleted')]);
    }
}