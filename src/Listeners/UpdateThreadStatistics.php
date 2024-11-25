<?php

namespace BalajiDharma\LaravelForum\Listeners;

use Balajidharma\LaravelForum\Models\Thread;
use Illuminate\Support\Facades\Config;


class UpdateThreadStatistics
{
    public function handle($event)
    {
        if ($event->comment->commentable && $event->comment->commentable instanceof (Config::get('forum.models.thread', Thread::class))) {
            $event->comment->commentable->updateStatistics();
        }
    }
}