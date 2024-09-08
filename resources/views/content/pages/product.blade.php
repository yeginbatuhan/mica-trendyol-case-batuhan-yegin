@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Product Listing')

@section('content')
  <div class="card">
    <h5 class="card-header">Product List</h5>

    <!-- Arama Kutusu -->
    <form action="{{ route('products.index') }}" method="GET" class="m-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>

    <!-- Toplam Ürün ve Sayfa Bilgisi -->
    <div class="m-4">
      <p>
        Showing {{ $products->count() }} products on this page out of {{ $products->total() }} total products.
      </p>
    </div>

    <!-- Ürün Tablosu -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Barcode</th>
          <th>List Price</th>
          <th>Sale Price</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
          <tr>
            <!-- Resim -->
            <td>
              <img src="{{ $product->images[0]['url'] ?? 'https://via.placeholder.com/50' }}" alt="{{ $product->title }}" class="rounded" style="width: 50px; height: 50px;">
            </td>
            <!-- Başlık -->
            <td>
              <a href="{{ $product->product_url }}" target="_blank" class="text-body fw-bold">
                {{ \Illuminate\Support\Str::limit($product->title, 50) }}
              </a>
            </td>
            <!-- Barkod -->
            <td>{{ $product->barcode }}</td>
            <!-- Liste Fiyatı -->
            <td>{{ number_format($product->list_price, 2) }} ₺</td>
            <!-- İndirimli Fiyat -->
            <td>{{ number_format($product->sale_price, 2) }} ₺</td>
            <!-- Stok -->
            <td>{{ $product->quantity }}</td>
            <!-- Güncelleme Butonu -->
            <td>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal"
                      data-id="{{ $product->id }}"
                      data-title="{{ $product->title }}"
                      data-list_price="{{ $product->list_price }}"
                      data-sale_price="{{ $product->sale_price }}"
                      data-quantity="{{ $product->quantity }}">
                Update
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">No products found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <!-- Sayfalama -->
    <div class="mt-3 d-flex justify-content-center">
      {{ $products->links('pagination::bootstrap-4') }}
    </div>
  </div>

  <!-- Update Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm" novalidate>
            @csrf
            <div class="mb-3">
              <label for="list_price" class="form-label">List Price</label>
              <input type="text" class="form-control" id="list_price" name="list_price" required pattern="^\d+(\.\d{1,2})?$">
              <div class="invalid-feedback">Please enter a valid price.</div>
            </div>
            <div class="mb-3">
              <label for="sale_price" class="form-label">Sale Price</label>
              <input type="text" class="form-control" id="sale_price" name="sale_price" required pattern="^\d+(\.\d{1,2})?$">
              <div class="invalid-feedback">Please enter a valid sale price.</div>
            </div>
            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
              <div class="invalid-feedback">Please enter a valid quantity.</div>
            </div>
            <input type="hidden" id="product_id" name="product_id">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Modal açıldığında mevcut değerleri doldurma
    document.getElementById('updateModal').addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var title = button.getAttribute('data-title');
      var listPrice = button.getAttribute('data-list_price');
      var salePrice = button.getAttribute('data-sale_price');
      var quantity = button.getAttribute('data-quantity');

      var modalTitle = document.getElementById('updateModalLabel');
      modalTitle.textContent = 'Update ' + title;

      document.getElementById('product_id').value = id;
      document.getElementById('list_price').value = listPrice;
      document.getElementById('sale_price').value = salePrice;
      document.getElementById('quantity').value = quantity;
    });

    // Form gönderme işlemi (AJAX)
    document.getElementById('saveChanges').addEventListener('click', function (event) {
      event.preventDefault();

      // Validation kontrolü
      var form = document.getElementById('updateForm');
      if (form.checkValidity() === false) {
        form.classList.add('was-validated');
        return;
      }

      // AJAX ile form gönderimi
      var id = document.getElementById('product_id').value;
      fetch(`/products/update/${id}`, {
        method: 'POST',
        body: new FormData(form),
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          }
        });
    });
  </script>

@endsection
