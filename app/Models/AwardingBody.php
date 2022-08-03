<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class AwardingBody extends Model
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
        'name',
    ];

    public function examSessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }

    public function levels(): HasManyThrough
    {
        return $this->hasManyThrough(Level::class, ExamSession::class);
    }

    public function subjects(): HasManyDeep
    {
        return $this->hasManyDeep(Subject::class, [ExamSession::class, Level::class]);
    }

    public function courses(): HasManyDeep
    {
        return $this->hasManyDeep(Course::class, [ExamSession::class, Level::class, Subject::class]);
    }
}
