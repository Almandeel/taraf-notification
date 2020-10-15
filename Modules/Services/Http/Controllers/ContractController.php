<?php

namespace Modules\Services\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;
use Modules\Services\Models\ContractCustomer;
use Modules\ExternalOffice\Models\Cv;
use Modules\Main\Models\Office;
use Modules\ExternalOffice\Models\{Country, Profession};

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:contracts-create')->only(['create', 'store']);
        $this->middleware('permission:contracts-read')->only(['index', 'show']);
        $this->middleware('permission:contracts-update')->only(['edit', 'update']);
        $this->middleware('permission:contracts-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        
        $contracts = Contract::orderBy('created_at');
        $first_contract = Contract::first();
        $from_date = is_null($request->from_date) ? (is_null($first_contract) ? date('Y-m-d') : $first_contract->created_at->format('Y-m-d')) : $request->from_date;
        $to_date = is_null($request->to_date) ? date('Y-m-d') : $request->to_date;
        $status = !is_null($request->status) ? $request->status : 'initial';
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $contracts = $contracts->whereBetween('created_at', [$from_date_time, $to_date_time]);
        // $contracts = $contracts->whereBetween('created_at', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        // $contracts = $contracts->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        
        $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
        if ($country_id != 'all') {
            $contracts = $contracts->where('country_id', $country_id);
        }
        
        $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
        if ($profession_id != 'all') {
            $contracts = $contracts->where('profession_id', $profession_id);
        }
        
        $contracts = $contracts->get();
        
        $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
        if ($office_id != 'all') {
            $contracts = $contracts->filter(function($contract) use($office_id){
                return $contract->cv()->office_id == $office_id;
            });
        }
        $gender = !is_null($request->gender) ? $request->gender : 'all';
        if ($gender != 'all') {
            $contracts = $contracts->filter(function($contract) use($gender){
                return $contract->cv()->gender == array_search($gender, Cv::GENDERS);
            });
        }
        
        // if ($status != 'all') {
        //     $contracts = $contracts->filter(function($contract) use($status){
        //         return $contract->checkStatus($status);
        //     });
        // }
        
        return view('services::contracts.index', compact('contracts', 'from_date', 'to_date', 'status', 'gender', 'office_id', 'country_id', 'profession_id', 'countries', 'offices', 'professions'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $view = !is_null($request->view) ? $request->view : 'create';
        $layout = 'master';
        $title = 'إضافة عقد';
        $crumbs = [
        'title' => $title,
        'datatable' => true,
        'modals' => ['customer', 'marketer'],
        'crumbs' => [
        [route('contracts.index'), 'العقود'],
        ['#', $title],
        ]
        ];
        
        $countries = Country::all();
        $professions = Profession::all();
        $statuses = Contract::STATUSES;
        $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv){
            $data = $cv->getAttributes();
            return array_merge($data, [
            'gender' => $cv->displayGender(),
            'age' => $cv->age(),
            'payed' => $cv->payed(),
            'country_name' => $cv->country->name,
            'office_name' => $cv->office->name ?? '',
            'profession_name' => $cv->profession->name,
            ]);
        });
        // dd($cvs);
        $cv = Cv::find($request->cv_id);
        if(is_null($cv)){
            $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
            $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
            $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        else{
            $country_id = $cv->country_id;
            $office_id = $cv->office_id;
            $profession_id = $cv->profession_id;
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        
        if ($view == 'initial') {
            $contract = Contract::find($request->contract_id);
            $title = is_null($contract) ? 'إنشاء عقد مبدئي' : 'عقد مبدئي';
            $layout = is_null($contract) ? 'base' : 'print';
            $crumbs = [
            'title' => $title,
            'datatable' => true,
            'modals' => ['customer', 'marketer'],
            'crumbs' => [
            [route('contracts.index'), 'العقود'],
            ['#', $title],
            ]
            ];
            if (!is_null($contract)) {
                $crumbs = [
                'title' => $title,
                'heading' => 'عقد مبدئي',
                ];
            }
            
            $compact = is_null($contract) ? compact('crumbs', 'cvs', 'cv', 'layout', 'countries', 'professions', 'country_id', 'profession_id', 'statuses', 'title', 'contract') : compact('crumbs', 'title', 'contract', 'layout');
            return view('services::contracts.' . $view, $compact);
        }
        
        $customers = Customer::all();
        $offices = Office::all();
        $marketers = Marketer::all();
        return view('services::contracts.' . $view, compact('crumbs', 'marketers', 'customers', 'cvs', 'cv', 'layout', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id', 'statuses'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param  Request  $request
    * @return Response
    */
    public function store(Request $request)
    {
        if ($request->type == 'initial') {
            $request->validate([
            'customer_name' => 'required|string',
            'customer_phones' => 'required|string',
            'customer_id_number' => 'numeric',
            'visa' => 'nullable|numeric',
            ]);
            $data = $request->only(['cv_id', 'visa']);
            $cv = Cv::findOrFail($request->cv_id);
            $data['country_id'] = $cv->country_id;
            $data['office_id'] = $cv->office_id;
            $data['profession_id'] = $cv->profession_id;
            $data['amount'] = 0;
            
            if ($request->customer_id_number) {
                $customer = Customer::where('id_number', $request->customer_id_number)->first();
            }
            if (!!is_null($request->customer_id_number) && !is_null($request->customer_phones)) {
                $customer = Customer::where('phones', $request->customer_phones)->first();
            }
            if(!isset($customer)){
                $customer = Customer::firstOrCreate([
                'name' => $request->customer_name,
                'phones' => $request->customer_phones,
                ]);
            }
            $data['customer_id'] = $customer->id;
            $data['status'] = Contract::STATUS_INITIAL;
            // dd($data);
            $contract = Contract::create($data);
            $cv->contracting($contract->id, Contract::STATUS_INITIAL);
            if ($contract) {
                return redirect()->route('contracts.create', ['view' => 'initial', 'contract_id' => $contract->id])->withSuccess('تم إنشاء العقد بنجاح');
            }
            return back()->withError('حدث خطأ اثناء إنشاء العقد');
        }else {
            $request->validate([
            'phones' => 'unique:customers',
            'visa' => 'nullable|numeric',
            'details' => 'nullable|string',
            'cv_id' => 'required|numeric',
            'profession_id' => 'required',
            'country_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'marketer_id' => 'nullable',
            'marketing_ratio' => 'nullable|numeric',
            ]);
            $data = $request->except(['_token', 'marketer_id']);
            $cv = Cv::findOrFail($request->cv_id);
            if ($request->country_id == 'all' || $request->country_id != $cv->country_id) {
                $data['country_id'] = $cv->country_id;
            }
            if ($request->profession_id == 'all' || $request->profession_id != $cv->profession_id) {
                $data['profession_id'] = $cv->profession_id;
            }
            
            if (is_null($request->customer_id)) {
                $customer_data = [];
                if (!is_null($request->customer_name)) {
                    $customer_data['name'] = $request->customer_name;
                }
                if (!is_null($request->customer_id_number)) {
                    $customer_data['id_number'] = $request->customer_id_number;
                }
                if (count($customer_data)) {
                    $customer = Customer::create($customer_data);
                    if ($customer) {
                        $data['customer_id'] = $customer->id;
                    }
                }
            }
            
            if ($request->marketer_id && $request->marketing_ratio) {
                $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
                $data['marketer_id'] = $marketer->id;

                $debt = $request->marketing_ratio;

                $marketer->update([
                    'debt' => ($marketer->debt +  $debt)
                ]);
            }else if($request->marketer_id) {
                $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
                $data['marketer_id'] = $marketer->id;
            }
            

            $contract = Contract::create($data);
            
            
            $cv->contracting($contract->id, $request->status);
            
            if ($contract) {
                $contract->attach();
            }
            return redirect()->route('contracts.show', $contract->id)->with('success', __('global.operation_success'));
        }
        
    }
    
    /**
    * Show the specified resource.
    * @param  Contract  $contract
    * @return Response
    */
    public function show(Contract $contract)
    {
        return view('services::contracts.show', compact('contract'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param  Contract  $contract
    * @return Response
    */
    public function edit(Request $request, Contract $contract)
    {
        $marketers = Marketer::all();
        // factory(Customer::class, 10)->create();
        $customers = Customer::all();
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        $statuses = Contract::STATUSES;
        $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv){
            return [
            'id' => $cv->id,
            'name' => $cv->name,
            'gender' => $cv->displayGender(),
            'age' => $cv->age(),
            'passport' => $cv->passport,
            'payed' => $cv->payed(),
            'passport' => $cv->passport,
            'country_id' => $cv->country_id,
            'office_id' => $cv->office_id,
            'profession_id' => $cv->profession_id,
            'country_name' => $cv->country->name,
            'office_name' => $cv->office->name ?? '',
            'profession_name' => $cv->profession->name,
            ];
        });
        $cv = $contract->cv();
        if(is_null($cv)){
            $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
            $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
            $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        else{
            $country_id = $cv->country_id;
            $office_id = $cv->office_id;
            $profession_id = $cv->profession_id;
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        
        
        return view('services::contracts.edit', compact('contract', 'marketers', 'customers', 'cvs', 'cv', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id', 'statuses'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param  Request  $request
    * @param  Contract  $contract
    * @return Response
    */
    public function update(Request $request, Contract $contract)
    {
        // dd($contract->cvs->where('pivot.cv_id', $request->cv_id)->first()->pivot->status);
        $request->validate([
        'visa' => 'nullable|numeric',
        // 'details' => 'nullable|string',
        'profession_id' => 'required',
        'country_id' => 'required',
        'amount' => 'required|numeric|min:0',
        ]);
        
        $data = $request->except(['_token', '_method', 'marketer_id']);
        $cv = Cv::findOrFail($request->cv_id);
        if ($request->country_id == 'all' || $request->country_id != $cv->country_id) {
            $data['country_id'] = $cv->country_id;
        }
        if ($request->profession_id == 'all' || $request->profession_id != $cv->profession_id) {
            $data['profession_id'] = $cv->profession_id;
        }
        
        if (is_null($request->customer_id)) {
            $customer_data = [];
            if (!is_null($request->customer_name)) {
                $customer_data['name'] = $request->customer_name;
            }
            if (!is_null($request->customer_id_number)) {
                $customer_data['id_number'] = $request->customer_id_number;
            }
            if (count($customer_data)) {
                $customer = Customer::firstOrCreate($customer_data);
                if ($customer) {
                    $data['customer_id'] = $customer->id;
                }
            }
        }
        
        if ($contract->cvs->count()) {
            if (!$contract->cvs->contains($request->cv_id)) {
                foreach($contract->cvs->where('pivot.status', Contract::STATUS_WORKING) as $c){
                    $contract->cvs()->updateExistingPivot($c, array('status' => Contract::STATUS_CANCELED), false);
                    $c->update(['status' => Cv::STATUS_ACCEPTED]);
                }
                
                
                $cv->contracting($contract->id, $request->status);
            }else{
                $contract_cv = $contract->cvs->where('pivot.cv_id', $request->cv_id)->last();
                if ($contract_cv->pivot->status != $request->status) {
                    $contract->cvs()->updateExistingPivot($cv, array('status' => $request->status), false);
                }
            }
        }else{
            $cv->contracting($contract->id, $request->status);
        }
        
        if ($request->marketer_id) {
            $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
            $data['marketer_id'] = $marketer->id;
            $debt = $request->marketing_ratio;
            if ($contract->marketer_id) {
                $contract->marketer->update([
                'debt' => ($contract->marketer->debt -  $contract->marketing_ratio)
                ]);
            }
            
            $marketer->update([
            'debt' => ($marketer->debt +  $debt)
            ]);
        }
        
        $contract->update($data);
        
        return back()->with('success', __('global.operation_success'));
    }
    
    /**
    * Remove the specified resource from storage.
    * @param  Contract  $contract
    * @return Response
    */
    public function destroy(Request $request, Contract $contract)
    {
        if ($request->operation == 'cancel') {
            $contract->cancel();
            return back()->with('success', __('global.operation_success'));
        }
        $previous_url = url()->previous();
        $show_url = route('contracts.show', $contract);
        $contract->delete();
        if($previous_url == $show_url){
            return redirect()->route('contracts.index')->with('success', __('contracts.cancel_success'));
        }
        return back()->with('success', __('global.delete_success'));
    }
}