<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TransactionIndexController extends Controller
{
    /**
     * Page Load
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return Inertia::render('finance/Transaction/Index');
    }
}