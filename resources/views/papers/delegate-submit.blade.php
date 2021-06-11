@extends('delegate.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 d-flex flex-column flex-grow-1">
                <div class="card">
                    @if(Route::currentRouteName() == 'delegate-paper')
                        <div class="card-header">{{ __('Edit abstract') }}</div>
                    @else
                        <div class="card-header">{{ __('Submit new abstract') }}</div>
                    @endif

                    <div class="card-body py-0 pr-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @livewire('paper-create-form', ['paper' => $paper ?? null])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endpush
