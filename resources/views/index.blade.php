@extends('layouts.app')

@section('content')
    <div class="container">
        @if( !empty(auth()->user()) && auth()->user()->role->name == "admin")
        <div class="m-2">
                <a href="/admin/products">
                    <button class="btn btn-success" >add product  </button>
                </a>
        </div>
        @endif
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm custom-card">
                            <div class="card-body">
                                <h2 class="card-title">{{ $product->name }} </h2>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                <form class="add-to-cart-form" data-product-id="{{ $product->id }}" action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 50px">
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 add-to-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $products->links() }}
            </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-to-cart-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var productId = form.data('product-id');
            var quantity = form.find('#quantity').val();
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    'product_id': productId,
                    'quantity': quantity,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                }
            });
        });
    });
</script>
