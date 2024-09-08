<?php
namespace App\Services\Trendyol;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use App\Models\Product;

class ProductService
{
  protected string $baseUrl;
  protected string $apiKey;
  protected string $apiSecret;
  protected string $supplierId;

  public function __construct()
  {
    $this->baseUrl = 'https://api.trendyol.com/sapigw';
    $this->apiKey = config('trendyol.api_key');
    $this->apiSecret = config('trendyol.api_secret');
    $this->supplierId = config('trendyol.supplier_id');
  }

  /**
   * Trendyol API'den ürünleri çeker.
   *
   * @param int $page
   * @param int $size
   * @return array
   * @throws ConnectionException
   */
  public function getProducts(int $page = 0, int $size = 100): array
  {
    try {
      $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
        ->get("{$this->baseUrl}/suppliers/{$this->supplierId}/products", [
          'page' => $page,
          'size' => $size,
        ]);

      if ($response->successful()) {
        return $response->json();
      }

      $errorMessage = $response->json()['message'] ?? 'Bilinmeyen bir hata oluştu.';
      throw new \Exception($errorMessage);

    } catch (ConnectionException $e) {
      throw new ConnectionException('API bağlantı hatası: ' . $e->getMessage());
    } catch (\Exception $e) {
      throw new \Exception('Ürünleri alırken hata oluştu: ' . $e->getMessage());
    }
  }

  /**
   * Alınan ürün verilerini kaydeder veya günceller.
   *
   * @param array $products
   * @return void
   */
  public function saveProducts(array $products): void
  {
    foreach ($products as $productData) {
      $defaultValues = [
        'productCode' => null,
        'productMainId' => null,
        'description' => '',
        'gender' => null,
        'brand' => null,
        'categoryName' => null,
        'listPrice' => 0,
        'salePrice' => 0,
        'quantity' => 0,
        'images' => [],
        'productUrl' => null,
      ];

      $productData = array_merge($defaultValues, $productData);

      Product::updateOrCreate(
        ['product_id' => $productData['id']],
        [
          'product_code' => $productData['productCode'],
          'product_main_id' => $productData['productMainId'],
          'title' => $productData['title'],
          'description' => $productData['description'],
          'barcode' => $productData['barcode'],
          'attributes' => $productData['attributes'],
          'brand' => $productData['brand'],
          'category_name' => $productData['categoryName'],
          'gender' => $productData['gender'],
          'list_price' => $productData['listPrice'],
          'sale_price' => $productData['salePrice'],
          'quantity' => $productData['quantity'],
          'images' => $productData['images'],
          'product_url' => $productData['productUrl'],
        ]
      );
    }
  }
}
