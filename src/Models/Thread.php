<?php

namespace Balajidharma\LaravelForum\Models;

use Balajidharma\LaravelComment\Traits\HasComments;
use BalajiDharma\LaravelCategory\Traits\HasCategories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, HasComments, HasCategories, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'author_type',
        'author_id',
        'content',
        'status',
        'updated_at',
        'created_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->setSlug();
        });
    }

    /**
     * The user who posted the thread.
     */
    public function author()
    {
        return $this->morphTo();
    }

    public function refreshCommentCount(): static
    {
        $this->comment_count = $this->comments()->count();

        return $this;
    }

    public function setSlug()
    {
        $slug = $this->slug ?? $this->title;
        $slug = \Str::slug($slug);

        if ($this->id) {
            $similarSlugs = Thread::where(function (Builder $q) use ($slug) {
                $q->where('slug', '=', $slug)
                    ->where('id', '!=', $this->id);
            })->where(function (Builder $q) use ($slug) {
                $q->where('id', '!=', $this->id)
                    ->orWhereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'");
            })->select('slug')->get();
        } else {
            $similarSlugs = Thread::where(function (Builder $q) use ($slug) {
                $q->where('slug', '=', $slug)
                    ->orWhereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'");
            })->select('slug')->get();
        }

        if ($similarSlugs->count()) {
            $valid = 0;
            $i = 1;
            do {
                $newSlug = $slug.'-'.$i;
                if ($similarSlugs->firstWhere('slug', $newSlug)) {
                    $i++;
                } else {
                    $valid = 1;
                    $slug = $newSlug;
                }
            } while ($valid < 1);
        }
        $this->slug = $slug;
    }
}