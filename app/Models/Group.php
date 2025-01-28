<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];



    /**
     * Get all of the comments for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupDates(): HasMany
    {
        return $this->hasMany(GroupDate::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }


    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function user_group(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }
}
