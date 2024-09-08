<?php
namespace App\Jobs;

use App\Services\Trendyol\ProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchProductJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected int $page;
  protected int $size;

  /**
   * FetchProductJob constructor.
   *
   * @param int $page
   * @param int $size
   */
  public function __construct(int $page, int $size)
  {
    $this->page = $page;
    $this->size = $size;
  }

  /**
   * Handle the job by fetching products from the Trendyol API and saving them.
   *
   * @param ProductService $productService
   * @return void
   */
  public function handle(ProductService $productService): void
  {
    try {
      $products = $productService->getProducts($this->page, $this->size);

      if (!isset($products['content']) || empty($products['content'])) {
        throw new \Exception('Ürün içeriği boş veya eksik.');
      }
      $productService->saveProducts($products['content']);
    } catch (\Exception $e) {
      \Log::error('Ürün çekme veya kaydetme sırasında hata: ' . $e->getMessage());
    }
  }
}

