<?php
namespace App\Services\Log;

use App\Models\Log;
use Illuminate\Support\Facades\Log as LaravelLog;

class LogService
{
  /**
   * Log a message and save it to the database.
   *
   * @param string $level
   * @param string $message
   * @param array|null $context
   */
  public function log(string $level, string $message, array $context = null): void
  {
    // Laravel'in default log sistemiyle dosyaya yazma
    LaravelLog::$level($message, $context ?? []);

    // Veritabanına kaydetme
    Log::create([
      'level' => $level,
      'message' => $message,
      'context' => $context ? json_encode($context) : null,
    ]);
  }

  /**
   * Get all logs from the database.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getLogs()
  {
    return Log::orderBy('created_at', 'desc')->get();
  }
}
