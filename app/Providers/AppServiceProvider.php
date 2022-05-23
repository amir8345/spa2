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
            'user' => 'App\Models\MainUser',
            'contributor' => 'App\Models\MainContributor',
            'shelf' => 'App\Models\Shelf',
            'publisher' => 'App\Models\MainPublisher',
            'post' => 'App\Models\Post',
            'comment' => 'App\Models\Comment',
        ]);
    }
}
