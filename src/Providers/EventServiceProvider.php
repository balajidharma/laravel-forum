<?php

namespace BalajiDharma\LaravelForum\Providers;

use BalajiDharma\LaravelComment\Events\CommentCreated;
use BalajiDharma\LaravelComment\Events\CommentDeleted;
use BalajiDharma\LaravelComment\Events\CommentUpdated;
use BalajiDharma\LaravelForum\Listeners\UpdateThreadStatistics;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentCreated::class => [
            UpdateThreadStatistics::class,
        ],
        CommentUpdated::class => [
            UpdateThreadStatistics::class,
        ],
        CommentDeleted::class => [
            UpdateThreadStatistics::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
