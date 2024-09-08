<?php

namespace App\Http\Controllers\service\trendyol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Trendyol\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
use App\Jobs\FetchProductJob;
use App\Models\Product;
use App\Services\Log\LogService;

class ProductController extends Controller
{
  protected ProductService $productService;

  protected LogService $logService;

  public function __construct(ProductService $productService, LogService $logService)
  {
    $this->productService = $productService;
    $this->logService = $logService;
  }

  /**
   * Ürünleri Trendyol API'den çeker ve JSON formatında döner
   *
   * @return JsonResponse
   */
  public function fetchProducts(): JsonResponse
  {
    try {
      $totalPages = 93;
      $size = 100;

      for ($page = 0; $page < $totalPages; $page++) {
        dispatch(new FetchProductJob($page, $size));
      }

      $this->logService->log('info', 'Ürünler başarılı şekilde arka planda işleniyor.');

      return response()->json(['message' => 'Ürünler arka planda çekiliyor.'], 200);
    } catch (\Exception $e) {
      $this->logService->log('error', 'Ürün çekilirken hata oluştu.', ['error' => $e->getMessage()]);

      return response()->json(['error' => 'Ürünler çekilirken bir hata oluştu.'], 500);
    }
  }

  /**
   * Ürünlerin listelenmesi ve arama yapılması
   *
   * @param Request $request
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    $search = $request->input('search');

    $products = Product::when($search, function ($query) use ($search) {
      return $query->where('title', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhere('barcode', 'like', '%' . $search . '%');
    })
      ->paginate(10);

    return view('content.pages.product', compact('products'));
  }

  /**
   * Ürün güncelleme işlemi (Fiyat, stok miktarı vb.)
   *
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(Request $request, int $id): JsonResponse
  {
    $validated = $request->validate([
      'list_price' => 'required|numeric|min:0',
      'sale_price' => 'required|numeric|min:0',
      'quantity' => 'required|integer|min:0',
    ]);

    try {
      $product = Product::findOrFail($id);
      $product->update($validated);

      return response()->json(['success' => true, 'message' => 'Ürün başarıyla güncellendi!'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Ürün güncellenirken bir hata oluştu: ' . $e->getMessage()], 500);
    }
  }
}
