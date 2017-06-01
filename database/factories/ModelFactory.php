<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    $user = User::inRandomOrder()->first();
    $file = UploadedFile::fake()->create(str_random() . '.pdf', 20000);
    $storagePath = config('app.document_storage_path') . DIRECTORY_SEPARATOR . $user->id;
    $storedFilePath = Storage::putFileAs($storagePath, $file, $file->name);

    return [
        'name' => $file->name,
        'path' => $storedFilePath,
        'mime_type' => Storage::mimeType($storedFilePath),
        'size' => Storage::size($storedFilePath),
        'user_id' => $user->id,
    ];
});
