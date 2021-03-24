@extends('delegate.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 d-flex flex-column flex-grow-1">
                <div class="card">
                    <div class="card-header">{{ __('Delegate') }} :: {{ __('Dashboard') }}</div>

                    <div class="card-body py-0 pr-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @livewire('paper-create-form', ['paperId' => $paperId ?? null])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
