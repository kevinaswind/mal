@extends('delegate.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Delegate') }} :: {{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <div class="d-flex justify-content-between mt-3">
                        <div class="w-25 px-2 py-4 border-light rounded-lg bg-info text-center text-white">
                            <a href="{{ route('delegate-papers') }}">{{ __('Abstract Submission') }}</a>
                        </div>
                        <div class="w-25 px-2 py-4 border-light rounded-lg bg-info text-center text-white">
                            {{ __('Registration') }}
                        </div>
                        <div class="w-25 px-2 py-4 border-light rounded-lg bg-info text-center text-white">
                            <a href="{{ route('delegate-pay-1') }}">{{ __('Payment') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
