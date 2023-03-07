<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\Org\Contingent\Person;
use App\Models\System\Relations\AdministativeDocumentsLinks;
use App\Models\System\Relations\StudentsLink;
use App\Models\System\Repository\AdministrativeDocument;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Illuminate\Support\Str;

class Group extends Model
{
    use AsSource;

    protected $fillable = [
        'training_period',
        'specialty_id',
        'shortname',
        'department_id',
        'curator_id',
        'archived',
    ];

    public function orders() {
        return $this -> morphToMany(AdministrativeDocument::class, 'signed', 'poly_administrative_documents', 'signed_id', 'administrative_document_id');
    }

    public function order() {
        return $this -> orders() -> orderBy('date_at', 'desc') -> limit(1);
    }

    public function name() {
        $period = $this -> getActualPeriod();
        $period = $period <= $this -> training_period ? $period : $this -> training_period;
        
        return Str::replace('#', $period, $this -> shortname);
    }

    public function getActualPeriod() {
        return Carbon::createFromFormat(
            'd.m.Y',
            $this
                -> enrollment_date
            )
        -> diffInYears(Carbon::now()) + 1;
    }

    public function students() {
        return $this -> hasManyThrough(Person::class, StudentsLink::class, 'group_id', 'id', 'id', 'person_id');
    }

    public function getEnrollmentDateAttribute() {
        return Carbon::createFromFormat('d.m.Y', $this -> order -> first() -> date_at) -> format('d.m.Y');
    }
}
