<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'name',
        'contact_name',
        'email',
        'zip',
        'pref_id',
        'city',
        'address1',
        'address2',
        'tel',
        'ship_name',
        'ship_zip',
        'ship_pref_id',
        'ship_city',
        'ship_address',
        'ship_tel',
        'ship_date',
        'ship_time_id',
        'ship_request',
        'sum',
        'fee',
        'in_fee',
        'total',
        'payment_id',
        'remark',
        'status_id',
        'estimate',
    ];


}
