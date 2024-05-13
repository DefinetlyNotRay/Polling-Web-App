<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'choices';
    protected $fillable = [
        "choice",
        "poll_id"
     

    ];
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}