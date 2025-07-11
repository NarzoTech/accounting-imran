@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Container') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.container.store') }}" method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Create Container') }}</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <!-- Container Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="container_number" name="container_number"
                                        label="{{ __('Container Number') }}"
                                        placeholder="{{ __('Enter Container Number') }}"
                                        value="{{ old('container_number') }}" :required="true" />
                                </div>
                            </div>

                            <!-- Container Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="container_type" name="container_type"
                                        label="{{ __('Container Type') }}"
                                        placeholder="{{ __('Enter Container Type ex: 20ft') }}"
                                        value="{{ old('container_type') }}" :required="true" />
                                </div>
                            </div>

                            <!-- Shipping Line -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="shipping_line" name="shipping_line"
                                        label="{{ __('Shipping Line') }}" placeholder="{{ __('Enter Shipping Line') }}"
                                        value="{{ old('shipping_line') }}" />
                                </div>
                            </div>

                            <!-- Port of Loading -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="port_of_loading" name="port_of_loading"
                                        label="{{ __('Port of Loading') }}"
                                        placeholder="{{ __('Enter Port of Loading') }}"
                                        value="{{ old('port_of_loading') }}" />
                                </div>
                            </div>

                            <!-- Port of Discharge -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="port_of_discharge" name="port_of_discharge"
                                        label="{{ __('Port of Discharge') }}"
                                        placeholder="{{ __('Enter Port of Discharge') }}"
                                        value="{{ old('port_of_discharge') }}" />
                                </div>
                            </div>

                            <!-- Estimated Departure -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="date" id="estimated_departure" name="estimated_departure"
                                        label="{{ __('Estimated Departure') }}"
                                        value="{{ old('estimated_departure') }}" />
                                </div>
                            </div>

                            <!-- Estimated Arrival -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="date" id="estimated_arrival" name="estimated_arrival"
                                        label="{{ __('Estimated Arrival') }}" value="{{ old('estimated_arrival') }}" />
                                </div>
                            </div>

                            <!-- Actual Arrival -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="date" id="actual_arrival" name="actual_arrival"
                                        label="{{ __('Actual Arrival') }}" value="{{ old('actual_arrival') }}" />
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <div class="form-group">
                                    <x-admin.form-textarea id="remarks" name="remarks" label="{{ __('Remarks') }}"
                                        value="{{ old('remarks') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Products in Container') }}</h4>
                    </div>

                    <div class="card-body">
                        <div id="product_items_wrapper">
                            <div class="row g-2 product-item align-items-end">
                                <div class="col-md-8">
                                    <x-admin.form-select name="products[0][product_id]" label="{{ __('Product') }}"
                                        class="select2" :required="true">
                                        <x-admin.select-option value="" text="{{ __('Select Product') }}" />
                                        @foreach ($products as $product)
                                            <x-admin.select-option :value="$product->id" :text="$product->name" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                                <div class="col-md-3">
                                    <x-admin.form-input type="number" min="1" name="products[0][quantity]"
                                        label="{{ __('Quantity') }}" value="1" :required="true" />
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add_product_item" class="btn btn-secondary mt-2">
                            <i class="fas fa-plus"></i> {{ __('Add Product') }}
                        </button>
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn />

                <!-- Status -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Status') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <x-admin.form-select name="status" id="status" label="{{ __('Status') }}"
                                        :required="true">
                                        @php
                                            $statuses = ['Pending', 'In Transit', 'Arrived', 'Cleared', 'Delivered'];
                                        @endphp
                                        <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                        @foreach ($statuses as $status)
                                            <x-admin.select-option :value="$status" :selected="old('status') === $status" :text="$status" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('js')
    <script>
        let productIndex = 1;

        $('#add_product_item').click(function() {
            let row = `
            <div class="row g-2 product-item align-items-end mt-2">
                <div class="col-md-8">
                    <label class="form-label">Product</label>
                    <select name="products[${productIndex}][product_id]" class="form-control select2" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-product-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
            $('#product_items_wrapper').append(row);
            $('.select2').select2();
            productIndex++;
        });

        $(document).on('click', '.remove-product-item', function() {
            $(this).closest('.product-item').remove();
        });
    </script>
@endpush
