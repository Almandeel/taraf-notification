<?php

namespace Modules\Services\Models;

use Modules\Accounting\Traits\Accountable;

class Customer extends BaseModel
{
    
    use Accountable;
    protected $table  = 'customers';
    protected $fillable = ['id', 'id_number', 'name', 'address', 'phones', 'description'];
    
    public function complaints() {
        return $this->hasMany('Modules\Services\Models\Complaint');
    }
    
    public function contracts() {
        return $this->hasMany('Modules\Services\Models\Contract');
    }
    
    // public static function create(){}
}