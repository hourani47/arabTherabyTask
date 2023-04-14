@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Checkout') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('checkout') }}" id="payment-form">
                            @csrf
                            <div class="form-group">
                                <label for="card-element">{{ __('Credit or debit card') }}</label>
                                <div id="card-element">
                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>Name on Card</label> <input
                                                class='form-control' size='4' name="card_name" type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group  required'>
                                            <label class='control-label'>Card Number</label> <input
                                                autocomplete='off' class='form-control card-number' size='20'
                                                type='text' name="card_nunmber">
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                                            class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                                            type='text' name="card_cvc">
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Month</label> <input
                                                class='form-control card-expiry-month' placeholder='MM' size='2'
                                                type='text' name="card_expiry_month">
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Year</label> <input
                                                class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                                type='text' name="card_expiry_year">
                                        </div>
                                    </div>

                                </div>
                                <div id="card-errors" role="alert"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <div class="col-xs-3 ">
                                <div style="font-size: 20px">Price: ${{$amount}} </div>
                                    <input class='form-control amount' type='hidden' name="amount" value="{{$amount}}">
                                </div>
                                <div class="col-xs-3">
                                <button type="submit" class="btn btn-primary" id="submit-button" >{{ __('Submit Payment') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
