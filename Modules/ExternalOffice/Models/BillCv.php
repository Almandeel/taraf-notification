<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;

class BillCv extends BaseModel
{
    protected $table = 'bill_cv';
    protected $fillable = [
		'bill_id',
		'cv_id',
		'amount',
    ];

    public function bill()
    {
    	return $this->belongsTo('Modules\Customer\Models\Bill');
    }

    public function cv()
    {
    	return $this->belongsTo(Cv::class);
    }
}