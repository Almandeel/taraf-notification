<?php

namespace Modules\Services\Models;
use Modules\ExternalOffice\Models\{Country, Profession, Cv};
use App\Traits\Attachable;
use Modules\Accounting\Traits\Voucherable;
class Contract extends BaseModel
{
    use Attachable;
    use Voucherable;
    public const STATUS_INITIAL    = -1;
    public const STATUS_WAITING    = 0;
    public const STATUS_WORKING    = 1;
    public const STATUS_CANCELED   = 2;
    public const STATUS_FINISHED   = 3;
    public const STATUSES = [
    self::STATUS_INITIAL => 'initial',
    self::STATUS_WAITING => 'waiting',
    self::STATUS_WORKING => 'working',
    self::STATUS_CANCELED => 'canceled',
    self::STATUS_FINISHED => 'finished',
    ];
    
    protected $fillable = [
    'visa',
    'profession_id',
    'marketing_ratio',
    'gender',
    'details',
    'marketer_id',
    'country_id',
    'customer_id',
    'amount',
    'destination',
    'arrival_airport',
    'date_arrival',
    'start_date',
    'ex_date',
    'status'
    ];
    public function getStatus(){
        $cv = $this->cv();
        return is_null($cv) ? null : self::STATUSES[$this->cv()->pivot->status];
        // return $this->status;
    }
    
    public function displayStatus(){
        // $status = '';
        // switch ($this->getStatus()) {
        //     case self::STATUS_WAITING:
        //         $status = 'في الانتظار';
        //         break;
        //     case self::STATUS_WORKING:
        //         $status = 'جاري';
        //         break;
        //     case self::STATUS_CANCELED:
        //         $status = 'ملغي';
        //         break;
        //     case self::STATUS_FINISHED:
        //         $status = 'منتهي';
        //         break;
        //     default:
        //         $status = 'غير معلومة';
        // }
        // return $status;
        return __('contracts.statuses.' . $this->getStatus());
    }
    
    public function getName(){
        return $this->customer->name;
    }
    
    public function getCurrency(){
        return 'ريال';
    }
    
    public function getMarketerMoney(){
        return $this->marketing_ratio;// * ($this->amount / 100);
    }
    public function getCustomerName(){
        if (is_null($this->customer)) {
            return 'لا يوجد';
        }
        
        return $this->customer->name;
    }
    public function getCvName(){
        if (is_null($this->cv())) {
            return 'لا يوجد';
        }
        
        return $this->cv()->name;
    }
    public function getCvPassport(){
        if (is_null($this->cv())) {
            return 'لا يوجد';
        }
        
        return $this->cv()->passport;
    }
    public function getOfficeName(){
        if (is_null($this->cv())) {
            return 'لا يوجد';
        }
        
        return $this->cv()->office->name  ?? '';
    }
    public function getProfessionName(){
        if (is_null($this->cv())) {
            return 'لا يوجد';
        }
        
        return $this->cv()->profession->name;
    }
    public function getApplicationDays($display = true, $remain_only = false){
        if ($remain_only) {
            $start_time = \Carbon\Carbon::parse($this->start_date);
            $now = \Carbon\Carbon::parse(date('Y-m-d'));
            // $now = now();
            $remain_days = $start_time->diffInDays($now, false);
            if ($start_time->gt($now)) {
                if ($display) {
                    return __('contracts.statuses.waiting');
                }
                return $this->ex_date - $remain_days;
            }
            if ($display) {
                return $this->ex_date - $remain_days . ' يوم';
            }
        }
        if ($display) {
            return $this->ex_date . ' يوم';
        }
        return $this->ex_date;
    }
    
    public function isWaiting(){
        return $this->checkStatus('waiting');
    }
    
    public function isWorking(){
        return $this->checkStatus('working');
    }
    
    public function isCanceled(){
        return $this->checkStatus('canceled');
    }
    
    public function isFinished(){
        return $this->checkStatus('finished');
    }
    
    public function checkStatus($status){
        if ($this->cvs->count()) {
            $cv = is_null($this->cv()) ? $this->cvs->last() : $this->cv();
            if (gettype($status) == 'string') {
                return $status == self::STATUSES[$cv->pivot->status];
            }
            elseif (gettype($status) == 'integer') {
                return $status == $cv->pivot->status;
            }
            
            throw new \Exception("Unsupported status data type", 1);
        }
        
        return false;
    }
    
    public function cvs()
    {
        return $this->belongsToMany(Cv::class)->withPivot(['status', 'created_at', 'updated_at']);
    }
    
    public function marketer()
    {
        return $this->belongsTo('Modules\ExternalOffice\Models\Marketer');
    }
    
    public function office()
    {
        return $this->cv()->belongsTo(Office::class);
    }
    
    public function country()
    {
        return $this->cv()->belongsTo(Country::class);
    }
    
    public function profession()
    {
        return $this->cv()->belongsTo(Profession::class);
    }
    
    public function cv($only_working = false) {
        $last_working_cv = $this->cvs->where('pivot.status', self::STATUS_WORKING)->sortByDesc('pivot.updated_at')->first();
        if ($only_working) {
            return $only_working;
        }
        if ($last_working_cv) {
            return $last_working_cv;
        }
        return  $this->cvs->sortByDesc('pivot.updated_at')->first();
    }
    
    // public function customer() {
    //     return $this->customers->sortBy('pivot.updated_at')->last();
    // }
    
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    
    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withPivot(['id', 'created_at', 'updated_at']);
    }
    
    public function contractCustomer() {
        return $this->hasMany('Modules\Services\Models\ContractCustomer');
    }
    
    public function cancel(){
        $succeeded = false;
        if($this->cv()){
            $this->cvs()->updateExistingPivot($this->cv(), array('status' => Contract::STATUS_CANCELED), false);
            $this->cv()->cancel();
        }
        
        return $succeeded;
    }
    
    public function delete(){
        // $cvs_ids = $this->cvs->pluck('id')->toArray();
        // if (count($cvs_ids)) {
        //     $this->cvs()->detach($cvs_ids);
        // }
        $this->cvs()->detach();
        $result = parent::delete();
        return $result;
    }
    
    public static function statusFromString(string $status)
    {
        return array_search($status, self::STATUSES);
    }
    
    public static function statusFromNumber(int $status)
    {
        return self::STATUSES[$status];
    }
    
    public static function getByStatus($status, $from_date = null, $to_date = null){
        $status_in_string = self::statusFromString($status);
        $from_date = is_null($from_date) ? (is_null(Contract::first()) ? date('Y-m-d') : Contract::first()->created_at->format('Y-m-d')) : $from_date;
        $to_date = is_null($to_date) ? date('Y-m-d') : $to_date;
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $builder = static::with(['cvs'=> function($query) use ($status_in_string){
            $query->wherePivot('status', $status_in_string);
        }]);
        $builder->whereBetween('created_at', [$from_date_time, $to_date_time]);
        return $builder->get();
    }
    
    public static function initial($from_date = null, $to_date = null){
        return self::getByStatus('initial', $from_date, $to_date);
    }
    
    public static function waiting($from_date = null, $to_date = null){
        return self::getByStatus('waiting', $from_date, $to_date);
    }
    
    public static function working($from_date = null, $to_date = null){
        return self::getByStatus('working', $from_date, $to_date);
    }
    
    public static function canceled($from_date = null, $to_date = null){
        return self::getByStatus('canceled', $from_date, $to_date);
    }
    
    public static function finished($from_date = null, $to_date = null){
        return self::getByStatus('finished', $from_date, $to_date);
    }
}