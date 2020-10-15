<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\{Employee, Salary, Transaction};
use App\Traits\Statusable;

class SalaryController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');

        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $salaries = Salary::orderBy('created_at', 'DESC');
        // dd($salaries->get());
        $salaries = $salaries->where('status', Statusable::$STATUS_APPROVED);
        $salaries = $salaries->whereBetween('created_at', [$from_date_time, $to_date_time]);

        $salaries = $salaries->get()->filter(function($salary){
            return $salary->statusInChecking();
        });
        return view('accounting::salaries.index', compact('salaries', 'from_date', 'to_date'));
    }


    /**
    * Display the specified resource.
    *
    * @param  \App\Transaction  $salary
    * @return \Illuminate\Http\Response
    */
    public function show(Salary $salary)
    {
        return view('accounting::salaries.show', compact('salary'));
    }


    /**
    * Display the specified resource.
    *
    * @param  Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function confirm(Salary $salary)
    {
        $remain_transactions = $salary->transactions('remain')->sortBy('type');
        $payed_debts = $salary->transactions('payed')->where('type', Transaction::TYPE_DEBT);
        $payed_deducations = $salary->transactions('payed')->where('type', Transaction::TYPE_DEDUCATION);
        $payed_bonuses = $salary->transactions('payed')->where('type', Transaction::TYPE_BONUS);

        return view('accounting::salaries.confirm', compact('salary', 'remain_transactions', 'payed_debts', 'payed_deducations', 'payed_bonuses'));
    }

    /**
    * Salary a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function confirming(Request $request, Salary $salary)
    {
        // dd($request->all());
        // if(\Hash::check($request->password, auth()->user()->password)){
        $request->validate([
        'net' => 'required|numeric',
        'total' => 'required|numeric',
        'safe_id' => 'required|numeric',
        'account_id' => 'required|numeric',
        ]);
        // $date = $request->year . '-';
        // $date .= $request->month < 10 ? '0' . $request->month : $request->month;
        // $request['month'] = $date;
        // $request['status'] = $date;
        if($request->has(['remain_ids', 'remain_safes', 'remain_accounts'])){
            if(is_array($request->remain_ids) && is_array($request->remain_safes) && is_array($request->remain_accounts)){
                if((count($request->remain_ids) == count($request->remain_safes)) && (count($request->remain_ids) == count($request->remain_accounts))){
                    for ($i=0; $i < count($request->remain_ids); $i++) {
                        $id = $request->remain_ids[$i];
                        $safe = $request->remain_safes[$i];
                        $account = $request->remain_accounts[$i];

                        $transaction = Transaction::findOrFail($id);
                        $transaction->confirm($safe, $account);
                    }
                }
            }
        }
        $salary->update($request->all());
        $details = __('accounting::salaries.entry_details');
        $details = str_replace('__month__', $salary->month, $details);
        $details = str_replace('__employee_id__', $salary->employee_id, $details);
        $salary->confirm($request->safe_id, $request->account_id, $request->net, $details);
        return redirect()->route('accounting.salary', ['salary' => $salary, 'layout' => 'print'])->with('success', __('accounting::salaries.checked_success'));

        // $previous_url = url()->previous();
        // $show_url = route('accounting.salaries.show', $salary);
        // $confirm_url = route('accounting.salaries.confirm', $salary);
        // if($previous_url == $show_url || $previous_url == $confirm_url){
        //     return redirect()->route('accounting.salaries')->with('success', __('accounting::salaries.checked_success'));
        // }
        // return back()->with('success', __('accounting::salaries.checked_success'));
        // }
        // return back()->with('error', 'كلمة المرور خاطئة');
    }
}
