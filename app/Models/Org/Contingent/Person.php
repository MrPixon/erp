<?php

namespace App\Models\Org\Contingent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Illuminate\Support\Str;
use Orchid\Attachment\Attachable;

class Person extends Model
{
    use AsSource, Attachable;

    protected $table = 'persons';

    protected $fillable = [
        'uuid',
        'user_id',
        'lastname',
        'firstname',
        'patronymic',
        'email',
        'corp_email',
        'tel',
        'birthdate',
        'snils',
        'inn',
        'hin',
        'sex',
        'workplace_id',
        'position_id',
        'photo_id',
    ];

    static $sexs = [
        1 => 'Мужской',
        2 => 'Женский',
    ];

    // Блок аксессоров
    public function getFullnameAttribute() {
        return "{$this -> lastname} {$this -> firstname} {$this -> patronymic}";
    }

    public function getCorpEmailAttribute($value) {
        return !empty($value) ? $this -> value : 'Не выдан';
    }

    public function getBirthdateAttribute($value) {
        return !empty($value) ? Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y') : 'Не указана';
    }
}
