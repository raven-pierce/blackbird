<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class ExamSession extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRelationships;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'session',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'session' => 'datetime',
    ];

    public function awardingBody(): BelongsTo
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class);
    }

    public function subjects(): HasManyThrough
    {
        return $this->hasManyThrough(Subject::class, Level::class);
    }

    public function courses(): HasManyDeep
    {
        return $this->hasManyDeep(Course::class, [Level::class, Subject::class]);
    }
}
