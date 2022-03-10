<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Course
 *
 * @property int $id
 * @property string $subject
 * @property int $awarding_body_id
 * @property int $course_level_id
 * @property int $exam_session_id
 * @property CourseStatus $status
 * @property int $tutor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AwardingBody $awardingBody
 * @property-read \App\Models\CourseLevel $courseLevel
 * @property-read \App\Models\ExamSession $examSession
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Material[] $materials
 * @property-read int|null $materials_count
 * @property-read \App\Models\User $tutor
 * @method static \Illuminate\Database\Eloquent\Builder|Course gridFilter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereAwardingBodyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tutor', 'awardingBody', 'courseLevel', 'examSession'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => CourseStatus::class
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class);
    }

    public function awardingBody()
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function scopeGridFilter($query, Array $filters)
    {
        return $query
            ->when($filters['awardingBody'] ?? false, fn($query, $awardingBody) => $query->where('awarding_body_id', $awardingBody->getKey()))
            ->when($filters['courseLevel'] ?? false, fn($query, $courseLevel) => $query->where('course_level_id', $courseLevel->getKey()))
            ->when($filters['examSession'] ?? false, fn($query, $examSession) => $query->where('exam_session_id', $examSession->getKey()))
            ->when($filters['courseStatus'] ?? false, fn($query, $courseStatus) => $query->where('status', $courseStatus));
    }
}
