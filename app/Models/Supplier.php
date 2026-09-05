<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'telegram',
        'whatsapp',
        'payment_details',
        'status',
        'comment',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }


}
