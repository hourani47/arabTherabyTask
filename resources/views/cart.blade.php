@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Cart</h1>

        @if (count($items) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{  $item['product']->name }}</td>
                        <td>${{ $item['product']->price }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['product']->price * $item['quantity']}}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <form action="{{ route('cart.checkout') }}" method="get">
                @csrf
                <button type="submit" class="btn btn-primary">Checkout</button>
            </form>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
@endsection
