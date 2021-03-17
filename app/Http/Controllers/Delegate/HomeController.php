<?php

namespace App\Http\Controllers\Delegate;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    protected $redirectTo = '/delegate/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('delegate.auth:delegate');
    }

    /**
     * Show the Delegate dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('delegate.home');
    }

}