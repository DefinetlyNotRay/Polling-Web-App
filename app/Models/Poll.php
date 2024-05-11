<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'polls';
    protected $fillable = [
        "title",
        "description",
        "deadline",
        "created_by",
        "deleted_at"

    ];
    // In Poll model
public function choices()
{
    return $this->hasMany(Choice::class);
}
}