<?php

namespace App\Models;

use App\Observers\ArticleObserver;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $status
 * @property string $title
 * @property string $preview
 * @property string $content
 * @property int|null $author_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article wherePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withoutTrashed()
 * @mixin \Eloquent
 */
class Article extends Eloquent
{
    use SoftDeletes, Searchable, EsSearchable;

    protected $table = 'articles';

    public static function boot()
    {
        parent::boot();
        self::observe(ArticleObserver::class);
    }

    // 定义索引里面的type
    public function searchableAs()
    {
        return 'articles';
    }

    // 定义有哪些字段需要搜索
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function shouldBeSearchable()
    {
        return true;
    }

    // -------------- relations ------------------
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}