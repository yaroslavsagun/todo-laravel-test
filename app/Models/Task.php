<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int    id
 * @property int    user_id
 * @property int    parent_id
 * @property string title
 * @property string description
 * @property int    status
 * @property int    priority
 * @property string completed_at
 * @property string created_at
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "parent_id", "title", "description", "status", "priority", "completed_at"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'parent_id', 'id');
    }
}
