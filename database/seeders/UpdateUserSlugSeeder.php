<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UpdateUserSlugSeeder extends Seeder
{
    public function run(): void
    {
        User::whereNull('slug')->get()->each(function ($user) {

            $slug = Str::slug($user->name);

            $count = User::where('slug', 'like', "{$slug}%")->count();

            $user->slug = $count ? "{$slug}-{$count}" : $slug;

            $user->save();
        });
    }
}