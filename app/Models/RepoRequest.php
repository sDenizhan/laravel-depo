<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepoRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'repo_requests';

    protected $casts = [
        'data' => 'json',
    ];

    public function getProductIdAttribute()
    {
        return $this->data['product_id'];
    }

    public function getQuantityAttribute()
    {
        return $this->data['quantity'];
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function repo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Repo::class, 'repo_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
