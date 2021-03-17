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

                            {{ __('All your abstracts listed below.') }}

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>{{ __('Title') }}</td>
                                <td>{{ __('Author') }}</td>
                                <td>{{ __('Date of submission') }}</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($papers as $paper)
                                <tr>
                                    <td>{{ $loop->inex }}</td>
                                    <td>{{ $paper->title }}</td>
                                    <td>{{ $paper->author }}</td>
                                    <td>{{ $paper->created_at }}</td>
                                    <td></td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <h5 class="text-center py-1 mb-0">{{ __('No abstracts submitted') }}</h5>
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
