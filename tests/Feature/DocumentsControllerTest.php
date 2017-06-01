<?php

namespace Test\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @var  User */
    protected $user;

    public function testCanCreateDocument()
    {
        /** @var Response|TestResponse $response */
        $response = $this->actingAs($this->user)
            ->call('POST', '/documents', [
                'name' => str_random() . '.pdf'
            ], [], [
                'document' => UploadedFile::fake()->create('file.pdf', 1000)
            ]);
dump($response->content());
        $this->assertTrue($response->isOk());
        $this->assertViewHas(['document']);
    }

    protected function setUp()
    {
        parent::setUp();

        Storage::fake("local");
        $this->artisan("db:seed");

        $this->user = User::first();
    }
}