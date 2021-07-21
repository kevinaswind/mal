@extends('delegate.layouts.app')

@push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 d-flex flex-column flex-grow-1">
                <div class="card">
                    @if(Gate::forUser(Auth::guard('delegate')->user())->allows('update-paper', $paper))
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
                    @else
                        <div class="card-body py-0 pr-0">
                            <h3>You are not allowed to view/edit the paper.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endpush
