<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'uuid',
        'name',
        'url',
        'whatsapp',
        'email',
        'phone',
        'facebook',
        'instagram',
        'youtube'
    ];

    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCompanies(string $filter = '')
    {
        return $this->with('category')
            ->where(function ($query) use ($filter) {
                if (!empty($filter)) {
                    $query->where('name', 'LIKE', "%{$filter}%")
                                    ->orWhere('email', $filter)
                                    ->orWhere('phone', $filter);
                }
            })
            ->paginate();
    }
}
