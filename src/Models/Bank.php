<?php

namespace Dipantry\Rupiah\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('rupiah.table_prefix').'banks';
        parent::__construct($attributes);
    }
}