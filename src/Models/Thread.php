<?php

namespace BalajiDharma\LaravelForum\Models;

use BalajiDharma\LaravelCategory\Traits\HasCategories;
use BalajiDharma\LaravelComment\Traits\HasComments;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Oddvalue\LaravelDrafts\Concerns\HasDrafts;

class Thread extends Model
{
    use HasCategories, HasComments, HasDrafts, HasFactory, SoftDeletes;

    public $commentable_key = 'uuid';

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
        $this->comment_count = $this->approvedComments()->count();

        return $this;
    }

    public function updateStatistics()
    {
        $this->refreshCommentCount()->save();
    }

    public function setSlug()
    {
        $slug = $this->slug ?? $this->title;
        $slug = \Str::slug($slug);

        $regexOperators = [
            'mysql' => 'RLIKE',
            'pgsql' => '~',
            'sqlite' => 'REGEXP',
        ];

        $driver = DB::connection()->getDriverName();
        $regexOperator = $regexOperators[$driver] ?? 'mysql';

        if ($this->id) {
            $similarSlugs = Thread::where(function (Builder $q) use ($slug) {
                $q->where('slug', '=', $slug)
                    ->where('id', '!=', $this->id);
            })->where(function (Builder $q) use ($slug, $regexOperator) {
                $q->where('id', '!=', $this->id)
                    ->orWhereRaw("slug {$regexOperator} '^{$slug}(-[0-9]+)?$'");
            })->select('slug')->get();
        } else {
            $similarSlugs = Thread::where(function (Builder $q) use ($slug, $regexOperator) {
                $q->where('slug', '=', $slug)
                    ->orWhereRaw("slug {$regexOperator} '^{$slug}(-[0-9]+)?$'");
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
