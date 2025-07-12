@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Container Details') }}</title>
@endsection
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ __('Container Details') }}</h4>
            <a href="{{ route('admin.container.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>{{ __('Container Number') }}:</strong></h6>
                    <p>{{ $container->container_number }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Container Type') }}:</strong></h6>
                    <p>{{ $container->container_type }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Shipping Line') }}:</strong></h6>
                    <p>{{ $container->shipping_line ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Port of Loading') }}:</strong></h6>
                    <p>{{ $container->port_of_loading ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Port of Discharge') }}:</strong></h6>
                    <p>{{ $container->port_of_discharge ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Estimated Departure') }}:</strong></h6>
                    <p>{{ optional($container->estimated_departure)->format('d M, Y') ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Estimated Arrival') }}:</strong></h6>
                    <p>{{ optional($container->estimated_arrival)->format('d M, Y') ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Actual Arrival') }}:</strong></h6>
                    <p>{{ optional($container->actual_arrival)->format('d M, Y') ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>{{ __('Status') }}:</strong></h6>
                    <span class="badge bg-info">{{ $container->status }}</span>
                </div>
                <div class="col-md-12 mt-3">
                    <h6><strong>{{ __('Remarks') }}:</strong></h6>
                    <p>{{ $container->remarks ?? '-' }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">{{ __('Products in this Container') }}</h5>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($container->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">{{ __('No products added.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
