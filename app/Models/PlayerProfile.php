<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modality',
        'gender',
        'position',
        'age',
        'birth_date',
        'height_cm',
        'weight_kg',
        'current_club',
        'institution_type',
        'institution_name',
        'city',
        'state',
        'country',
        'dominant_foot',
        'profile_photo_path',
        'bio',
        'characteristics',
        'is_student',
        'school_name',
        'school_grade',
        'is_federated',
        'has_awards',
        'awards_description',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_student' => 'boolean',
            'is_federated' => 'boolean',
            'has_awards' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(PlayerVideo::class);
    }

    public function photos()
    {
        return $this->hasMany(PlayerPhoto::class);
    }

    public function stats()
    {
        return $this->hasMany(PlayerStat::class);
    }

    public function getModalityLabelAttribute(): ?string
    {
        return match ($this->modality) {
            'campo' => 'Futebol de Campo',
            'futsal' => 'Futsal',
            'fut7' => 'Fut 7',
            default => null,
        };
    }

    public function getGenderLabelAttribute(): ?string
    {
        return match ($this->gender) {
            'male' => 'Masculino',
            'female' => 'Feminino',
            default => null,
        };
    }

    public function getInstitutionTypeLabelAttribute(): ?string
    {
        return match ($this->institution_type) {
            'clube' => 'Clube',
            'projeto' => 'Projeto',
            'escolinha' => 'Escolinha',
            default => null,
        };
    }
}
