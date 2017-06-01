<?php

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // empty storage directory before adding new docs
        Storage::deleteDirectory(config('app.document_storage_path'));

        factory(Document::class, 20)->create();
    }
}
