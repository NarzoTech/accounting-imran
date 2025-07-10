@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Product') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.product.store') }}" method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Create product') }}</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-12">
                                <div class="form-group">
                                    <x-admin.form-input id="name" name="name" label="{{ __('Product Name') }}"
                                        placeholder="{{ __('Enter Product Name') }}" value="{{ old('name') }}"
                                        required="true" />
                                </div>
                            </div>

                            @php
                                $sku = random_int(10000000, 99999999);
                            @endphp
                            <!-- Product Code -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="product_code" name="product_code"
                                        label="{{ __('Product Code') }}" placeholder="{{ __('Enter Product Code') }}"
                                        value="{{ old('product_code', $sku) }}" required="true" />
                                </div>
                            </div>

                            <!-- Unit -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-select name="unit" id="unit" class="select2"
                                        label="{{ __('Unit') }}">
                                        @php
                                            $units = [
                                                'PCS',
                                                'Box',
                                                'CM',
                                                'DZ',
                                                'FT',
                                                'G',
                                                'IN',
                                                'KG',
                                                'KM',
                                                'LB',
                                                'MG',
                                                'ML',
                                                'M',
                                                'SET',
                                                'YD',
                                            ];
                                        @endphp
                                        <x-admin.select-option value="" text="{{ __('Select Unit') }}" />
                                        @foreach ($units as $unit)
                                            <x-admin.select-option :value="$unit" :selected="old('unit', 'PCS') === $unit" :text="$unit" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                            </div>


                            <!-- Cost Price -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="number" step="0.01" id="cost_price" name="cost_price"
                                        label="{{ __('Cost Price') }}" placeholder="{{ __('Enter Cost Price') }}"
                                        value="{{ old('cost_price', 0) }}" />
                                </div>
                            </div>

                            <!-- Sell Price -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="number" step="0.01" id="sell_price" name="sell_price"
                                        label="{{ __('Sell Price') }}" placeholder="{{ __('Enter Sell Price') }}"
                                        value="{{ old('sell_price', 0) }}" />
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-select name="category_id" id="category_id" class="select2"
                                        label="{{ __('Category') }}">
                                        <x-admin.select-option value="" text="{{ __('Select Category') }}" />
                                        @foreach ($categories as $category)
                                            <x-admin.select-option :selected="$category->id == old('category_id')" value="{{ $category->id }}"
                                                text="{{ $category->name }}" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                            </div>

                            <!-- Sub Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-select name="sub_category_id" id="sub_category_id" class="select2"
                                        label="{{ __('Sub Category') }}">
                                        <x-admin.select-option value="" text="{{ __('Select Sub Category') }}" />
                                        @foreach ($subCategories as $subCategory)
                                            <x-admin.select-option :selected="$subCategory->id == old('sub_category_id')" value="{{ $subCategory->id }}"
                                                text="{{ $subCategory->name }}" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-admin.form-textarea id="description" name="description"
                                        label="{{ __('Description') }}" value="{{ old('description') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn />

                <!-- Image Upload -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Image') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <x-admin.form-image-preview :required="false" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Status') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-3">
                                    <x-admin.form-switch name="status" label="{{ __('Status') }}" :checked="old('status') == 1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
