@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Payment Successful') }}</div>

                    <div class="card-body">
                        <p>{{ __('Thank you for your payment!') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
