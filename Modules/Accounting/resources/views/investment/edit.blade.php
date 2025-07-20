@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Investment') }}</title>
@endsection

@section('admin-content')
    <form action="{{ route('admin.investment.update', $investment->id) }}" method="post" id="create_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Main Form -->
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ __('Edit Investment') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Investor -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="investor_id" label="{{ __('Investor') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Investor') }}" />
                                    @foreach ($investors as $investor)
                                        <x-admin.select-option :value="$investor->id" :selected="$investment->investor_id == $investor->id" :text="$investor->name" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="amount" label="{{ __('Investment Amount') }}"
                                    value="{{ old('amount', $investment->amount) }}" type="number" step="0.01"
                                    required="true" />
                            </div>

                            <!-- Investment Date -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="investment_date" label="{{ __('Investment Date') }}"
                                    value="{{ old('investment_date', $investment->investment_date->format('Y-m-d')) }}"
                                    type="date" required="true" />
                            </div>

                            <!-- Expected Profit -->
                            <div class="col-md-12 mb-4">
                                <x-admin.form-input name="expected_profit" label="{{ __('Expected Profit') }}"
                                    value="{{ old('expected_profit', $investment->expected_profit) }}" type="number"
                                    step="0.01" />
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <x-admin.form-textarea name="remarks" label="{{ __('Remarks') }}"
                                    value="{{ old('remarks', $investment->remarks) }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn />
            </div>
        </div>
    </form>
@endsection
