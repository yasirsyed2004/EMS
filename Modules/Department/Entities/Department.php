<?php

namespace Modules\Department\Entities;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Entities\Employee;

class Department extends Model
{
    use HasFactory;
    // protected static $logAttributes = ["*"];
    // protected static $logOnlyDirty = true;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'department_id');
    }

}
