<?php

namespace BalajiDharma\LaravelForum\Listeners;

use Balajidharma\LaravelForum\Models\Thread;
use Illuminate\Support\Facades\Config;


class UpdateThreadStatistics
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->comment->commentable_type == Config::get('forum.models.thread', Thread::class)) {
            $event->comment->commentable->updateStatistics();
        }
    }
}