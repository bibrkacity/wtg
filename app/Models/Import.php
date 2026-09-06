<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'supplier_id',
        'external_import_id',
        'sent_at',
        'status',
        'total_offers',
        'processed_offers',
        'error',
        'completed_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'supplier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'total_offers' => 'integer',
            'processed_offers' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function toShowArray(): array
    {
        $fields = $this->fillable;
        $fields[0] = 'supplier';

        foreach ($fields as $field) {
            $array[$field] = $this->$field;
        }
        $array['created_at'] = $this->created_at;
        return $array;

    }

    /**
     * Get the supplier that owns the import.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the supplier name.
     */
    public function getSupplierAttribute(): ?string
    {
        return $this->supplier()->value('name');
    }
}
