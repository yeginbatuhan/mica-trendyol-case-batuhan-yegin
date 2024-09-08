<?php

namespace App\Http\Controllers\log;

use App\Http\Controllers\Controller;
use App\Services\Log\LogService;
use Illuminate\Http\Request;

class LogController extends Controller
{
  protected LogService $logService;

  public function __construct(LogService $logService)
  {
    $this->logService = $logService;
  }

  public function index()
  {
    $logs = $this->logService->getLogs();
    return view('content.pages.logs', compact('logs'));
  }
}
