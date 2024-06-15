<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepoHasProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'repo_has_products';
}
