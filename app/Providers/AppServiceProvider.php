<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'book' => 'App\Models\MainBook',
            'user' => 'App\Models\User',
            'contributor' => 'App\Models\Contributor',
            'shelf' => 'App\Models\Shelf',
            'publisher' => 'App\Models\Publisher',
            'post' => 'App\Models\Post',
            'comment' => 'App\Models\Comment',
        ]);
    }
}
