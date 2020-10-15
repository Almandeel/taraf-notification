<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Voucher;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Request
    */
    public function voucher(Voucher $voucher)
    {
        return view('vouchers.show', compact('voucher'));
    }

    // Notifcation Component 
    public function notification(Request $request) {
        $user = $request->user();
        
        $total = $user->unreadNotifications->count();

        return compact('total');
    }
}
