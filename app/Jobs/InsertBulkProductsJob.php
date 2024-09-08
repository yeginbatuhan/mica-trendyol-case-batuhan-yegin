<?php
namespace App\Jobs;

use App\Services\Trendyol\ProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InsertBulkProductsJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected array $products;

  /**
   * InsertBulkProductsJob constructor.
   *
   * @param array $products
   */
  public function __construct(array $products)
  {
    $this->products = $products;
  }

  /**
   * Handle the job to insert products in bulk.
   *
   * @param ProductService $productService
   * @return void
   */
  public function handle(ProductService $productService): void
  {
    try {
      if (empty($this->products)) {
        throw new \Exception('Ürün listesi boş, kayıt yapılamadı.');
      }

      $productService->saveProducts($this->products);
    } catch (\Exception $e) {
      \Log::error('Ürünleri toplu ekleme sırasında hata: ' . $e->getMessage());
    }
  }
}
