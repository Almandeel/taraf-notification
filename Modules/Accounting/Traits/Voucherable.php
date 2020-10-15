<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Voucher;
/**
*  Voucherable Trait
*/
trait Voucherable
{
    
    public function vouchers()
    {
        return $this->morphMany(Voucher::class, 'voucherable');
    }

    public function getName(){
        return $this->name;
    }
    
    public function getCurrency(){
        return 'ريال';
    }
    
    public function addVoucher($type, $amount, $details = ''){
        $voucher = Voucher::create([
            'type' => $type,
            'amount' => $amount,
            'details' => $details,
        ]);

        if($voucher){
            $this->vouchers()->save($voucher);
            return true;
        }

        return false;
    }

    public function vouch($voucher){
        if (gettype($voucher) == 'array') {
            $voucher = Voucher::create($voucher);
        }
        return $this->vouchers()->save($voucher);
    }

    public static function bootVoucherable(){
        static::deleting(function($model){
            if($model->vouchers){
                foreach ($model->vouchers as $voucher) {
                    $voucher->delete();
                }
            }
        });
    }
}