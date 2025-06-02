<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'category',
        'rank',
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product')->where('publish_id', 1);
    }

    public function sub_category()
    {
        return $this->hasMany('App\Models\SubCategory');
    }
}
