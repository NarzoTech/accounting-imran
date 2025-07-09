@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Clear cache') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning alert-has-icon d-flex flex-wrap align-items-center gap-2">
                        <div class="alert_icon fs-2">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert_text">
                            <h5 class="text-warning">{{ __('Warning') }}</h5>
                            <p class="m-0">
                                {{ __('If you want to clearing all caches on your website may briefly affect its performance as cached data is regenerated.') }}
                            </p>
                        </div>
                    </div>
                    <x-admin.button variant="danger" data-bs-toggle="modal" data-bs-target="#cacheClearModal"
                        text="{{ __('Clear cache') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="cacheClearModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Cache Clear Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-0">
                    <p>{{ __('Are You sure want to clear cache ?') }}</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form action="{{ route('admin.cache-clear-confirm') }}" method="POST">
                        @csrf
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Clear') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
