<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Attachable;
use Modules\Accounting\Traits\{Yearable, Voucherable};
use Modules\Accounting\Models\Voucher;
use App\User;
class Custody extends BaseModel
{
    use Attachable;
    use Yearable;
    use Voucherable;
    public const STATUS_OPEN    = 1;
    public const STATUS_CLOSED   = 2;
    public const STATUSES = [
    self::STATUS_OPEN => 'open',
    self::STATUS_CLOSED => 'closed',
    ];
    protected $table = 'custodies';
    protected $fillable = ['amount', 'details', '', 'user_id', 'employee_id', 'year_id'];
    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
    'created_at' => 'datetime:Y/m/d',
    'updated_at' => 'datetime:Y/m/d',
    ];
    
    // public function setAmountAttribute($amount){
    //     if (is_array($amount)) {
    //         $this->attributes['amount'] = json_encode(['value' => $amount[0], 'currency' => $amount[1]]);
    //     }
    //     $this->attributes['amount'] = $amount;
    // }
    
    public function getAmountAttribute($amount){
        // $amount_array = json_decode($amount);
        // return implode(' ', $amount_array);
        return json_decode($amount);
    }
    
    public function getAmount($attribute = 'value'){
        // $amount_array = explode(' ', $this->amount);
        // if ($get_currency) {
        //     return $amount_array[1];
        // }
        // return $amount_array[0];
        return $this->amount->$attribute;
    }
    
    public function getFormatedAmountAttribute(){
        return number_format($this->getAmount(), 2) . ' ' . $this->getAmount('currency');
    }
    
    public function getName(){
        return $this->employee->name;
    }
    
    public function getStatus($get_value = true){
        $status = ($this->remain() == 0) ? self::STATUS_CLOSED : self::STATUS_OPEN;
        if ($get_value) {
            return $status;
        }
        
        return self::STATUSES[$status];
    }
    
    public function checkStatus($status){
        if (gettype($status) == 'string') {
            return $status == $this->getStatus(false);
        }
        elseif (gettype($status) == 'integer') {
            return $status == $this->getStatus();
        }
        
        throw new \Exception("Unsupported status data type", 1);
    }
    
    public function displayStatus(){
        return __('custodies.statuses.' . $this->getStatus(false));
    }
    
    public function payments(){
        $vouchers = $this->vouchers;
        if ($vouchers->count() > 1) {
            $vouchers->forget(0);
            return $vouchers;
        }
    }
    public function isPayed(){
        return $this->remain() == 0;
    }
    public function payed($formated = false)
    {
        $payed = 0;
        if($this->vouchers->count() > 1){
            $payed = $this->payments()->sum('amount');
        }
        
        if ($formated) {
            return number_format($payed, 2);
        }
        return $payed;
    }
    
    public function remain($formated = false)
    {
        $remain = $this->getAmount() - $this->payed();
        if ($formated) {
            return number_format($remain, 2);
        }
        return $remain;
    }
    
    public function getVoucherAttribute()
    {
        return $this->vouchers->first();
    }
    
    public function isVouched()
    {
        return $this->vouchers->count() > 0;
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    
    public function pay(array $attributes){
        if (!(array_key_exists('type', $attributes))) {
            $attributes['type'] = Voucher::TYPE_RECEIPT;
        }
        if (!(array_key_exists('details', $attributes))) {
            $attributes['details'] = 'عبارة عن سداد تسوية من العهدة رقم: ' . $this->id;
        }
        
        return $this->vouch($attributes);
    }
    
    public static function create(array $attributes){
        if (!(array_key_exists('user_id', $attributes))) {
            $attributes['user_id'] = auth()->user()->getKey();
        }
        if (array_key_exists('amount', $attributes)) {
            $amount = $attributes['amount'];
            $amount_type = gettype($amount);
            if ($amount_type == 'array') {
                $attributes['amount'] = json_encode(['value' => $amount[0], 'currency' => $amount[1]]);
            }elseif ($amount_type == 'string') {
                if (is_json($amount)) {
                    $attributes['amount'] = $amount;
                }else{
                    $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
                }
            }
            else{
                if (array_key_exists('currency', $attributes)) {
                    $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
                }
            }
        }
        $custody = static::query()->create($attributes);
        // dd($attributes, $custody);
        $custody->vouch([
        'amount' => $custody->amount->value,
        'currency' => $custody->amount->currency,
        'details' => $custody->details,
        'type' => Voucher::TYPE_PAYMENT,
        'voucher_date' => date("Y-m-d"),
        ]);
        return $custody;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        if (array_key_exists('amount', $attributes)) {
            $amount = $attributes['amount'];
            $amount_type = gettype($amount);
            if ($amount_type == 'array') {
                $attributes['amount'] = json_encode(['value' => $amount[0], 'currency' => $amount[1]]);
            }elseif ($amount_type == 'string') {
                if (is_json($amount)) {
                    $attributes['amount'] = $amount;
                }else{
                    $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
                }
            }
            else{
                if (array_key_exists('currency', $attributes)) {
                    $attributes['amount'] = json_encode(['value' => $amount, 'currency' => $attributes['currency']]);
                }
            }
        }
        // dd($attributes);
        $result = parent::update($attributes, $options);
        $voucher_data = [];
        $voucher = $this->voucher;
        if ($this->details != $voucher->details) {
            $voucher_data['details'] = $this->details;
        }
        if ($this->getAmount('value') != $voucher->amount) {
            $voucher_data['amount'] = $this->getAmount('value');
        }
        if ($this->getAmount('currency') != $voucher->currency) {
            $voucher_data['currency'] = $this->getAmount('currency');
        }
        if (count($voucher_data)) {
            $voucher->update($voucher_data);
        }
        return $result;
    }
    
    public function delete(){
        $result = parent::delete();
        
        return $result;
    }
}