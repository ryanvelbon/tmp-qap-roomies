<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;
    use HasFactory;

    public const SEX_SELECT = [
        [
            'label' => 'male',
            'value' => 'm',
        ],
        [
            'label' => 'female',
            'value' => 'f',
        ],
    ];

    public const ETHNICITY_SELECT = [
        [
            'label' => 'Malay',
            'value' => 'malay',
        ],
        [
            'label' => 'Chinese',
            'value' => 'chinese',
        ],
        [
            'label' => 'Indian',
            'value' => 'indian',
        ],
        [
            'label' => 'Other',
            'value' => 'other',
        ],
    ];

    public const RELIGION_SELECT = [
        [
            'label' => 'Muslim',
            'value' => 'muslim',
        ],
        [
            'label' => 'Christian',
            'value' => 'christian',
        ],
        [
            'label' => 'Buddhist',
            'value' => 'buddhist',
        ],
        [
            'label' => 'Hindu',
            'value' => 'hindu',
        ],
        [
            'label' => 'Atheist',
            'value' => 'atheist',
        ],
        [
            'label' => 'Other',
            'value' => 'other',
        ],
    ];

    public $table = 'profiles';

    protected $appends = [
        'sex_label',
        'religion_label',
        'ethnicity_label',
    ];

    protected $dates = [
        'dob',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $orderable = [
        'id',
        'user.email',
        'dob',
        'sex',
        'religion',
        'ethnicity',
        'first_name',
        'last_name',
        'about',
    ];

    protected $filterable = [
        'id',
        'user.email',
        'dob',
        'sex',
        'religion',
        'ethnicity',
        'first_name',
        'last_name',
        'about',
    ];

    protected $fillable = [
        'user_id',
        'dob',
        'sex',
        'religion',
        'ethnicity',
        'first_name',
        'last_name',
        'about',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDobAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getSexLabelAttribute()
    {
        return collect(static::SEX_SELECT)->firstWhere('value', $this->sex)['label'] ?? '';
    }

    public function getReligionLabelAttribute()
    {
        return collect(static::RELIGION_SELECT)->firstWhere('value', $this->religion)['label'] ?? '';
    }

    public function getEthnicityLabelAttribute()
    {
        return collect(static::ETHNICITY_SELECT)->firstWhere('value', $this->ethnicity)['label'] ?? '';
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
