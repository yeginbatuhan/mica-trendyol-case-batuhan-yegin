# Mica-Trendyol-Case-Batuhan-Yegin

Bu proje, Trendyol API'sini kullanarak belirli bir tedarikçinin (supplierId) ürünlerini çeken, veritabanına kaydeden,
kullanıcıya listeleyen ve ürün stok ve fiyat güncellemelerini sağlayan bir Laravel mikro servis projesidir. Proje,
performans optimizasyonları ve kuyruklama (queue) mekanizmalarıyla çalışmakta, loglama sistemiyle kullanıcıya detaylı
bilgi sağlamaktadır.

## Gereksinimler

- PHP >= 8.2
- Laravel >= 11.x
- Composer
- MySQL
- npm
- Geliştirme sırasında kullanılmak üzere: `php artisan serve`
- **Not:** Bu proje `npm run dev` olmadan çalışır, sadece `php artisan serve` ile çalıştırılmalıdır.

## Kurulum

1. Projeyi klonlayın:
   ```bash
   git clone https://github.com/batuhan-yegin/mica-trendyol-case-batuhan-yegin.git
2. Proje dizinine gidin:
   ```bash
   cd mica-trendyol-case-batuhan-yegin
3. Bağımlılıkları yükleyin:

   ```bash
   composer upgrade

4. .env dosyasını oluşturun:
   ```bash
   cp .env.example .env
   
5. .env dosyasını düzenleyin ve veritabanı bilgilerinizi ve Trendyol API bilgilerinizi girin:
   ```bash
   DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=trendyol_case
    DB_USERNAME=root
    DB_PASSWORD=your_password

    TRENDYOL_API_KEY=your_api_key
    TRENDYOL_API_SECRET=your_api_secret
    TRENDYOL_SUPPLIER_ID=your_supplier_id
   
6. Veritabanını oluşturun::
   ```bash
   php artisan migrate

7. NPM Paketlerini Yükleyin Ve Çalıştırın:
   ```bash
   npm install && npm run dev   
8. Kuyrukları Çalıştırın:
   ```bash
   php artisan queue:work
   
9. Laraveli Çalıştırın:
   ```bash
   php artisan serve   
   
9. Trendyol Ürünleri Çekin:
   ```bash
   http://127.0.0.1:8000/fetch-products
      
## Teslim:
Bu proje, bir Laravel mikro servis olarak tasarlanmış olup, loglama, kuyruklama ve API entegrasyonu gibi önemli işlevlere 
sahiptir. Kodlar temiz, okunabilir ve performansa yönelik olarak optimize edilmiştir.
