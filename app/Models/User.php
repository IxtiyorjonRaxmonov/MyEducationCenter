<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;



class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }


    public function user_group(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    public function rate(): HasMany
    {
        return $this->hasMany(Rates::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups')
            ->where('user_groups.active', true)
            ->with(['groupDates.rates','subject']);
    }
}
