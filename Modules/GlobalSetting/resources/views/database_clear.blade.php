@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Database clear') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
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

                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearDatabaseModal">
                                    <i class="fas fa-exclamation-triangle"></i> {{ __('Clear System') }}
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="clearDatabaseModal">
        <div class="modal-dialog" role="document">
            <form class="modal-content" action="{{ route('admin.system.cleanup-success') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Clear System') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-0">
                    <p>{{ __('Are you really want to clear this database?') }}</p>

                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <x-admin.button data-bs-dismiss="modal" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Yes, Clear') }}" variant="danger" />
                </div>
            </form>
        </div>
    </div>
@endsection
