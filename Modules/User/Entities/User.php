<?php

namespace Modules\User\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Modules\Employee\Entities\Employee;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login',
        "phone"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guard_name = 'api';


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime'
    ];

      /*
	 * Get full name
	 */
    public function getName()
    {
        return ($this->name ? $this->name : '') . ' ' . ($this->last_name ? $this->last_name : '');
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    }

    public function rolesWithoutPivot()
    {
        return $this->roles->map(function ($role) {
            return $role->only(['id', 'name', 'display_name', 'created_at']);
        });
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->contains(function ($role) {
            return $role->name === config('app.admin_role');
        });
    }
    public function isManager(): bool
    {
        return $this->roles->contains(function ($role) {
            return $role->name === 'manager';
        });
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
}
