@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Investment') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.investment.store') }}" method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Main Form -->
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ __('Create Investment') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Investor -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="investor_id" label="{{ __('Investor') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Investor') }}" />
                                    @foreach ($investors as $investor)
                                        <x-admin.select-option :value="$investor->id" :text="$investor->name" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="account_id" label="{{ __('Account') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Account') }}" />
                                    @foreach ($accounts as $account)
                                        <x-admin.select-option :value="$account->id" :text="$account->name" :selected="old('account_id')" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="amount" label="{{ __('Investment Amount') }}"
                                    value="{{ old('amount') }}" type="number" step="0.01" required="true" />
                            </div>

                            <!-- Investment Date -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="investment_date" label="{{ __('Investment Date') }}"
                                    value="{{ old('investment_date', date('Y-m-d')) }}" type="date" required="true" />
                            </div>

                            <!-- Expected Profit -->
                            <div class="col-md-12 mb-4">
                                <x-admin.form-input name="expected_profit" label="{{ __('Expected Profit') }}"
                                    value="{{ old('expected_profit', 0) }}" type="number" step="0.01" />
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <x-admin.form-textarea name="remarks" label="{{ __('Remarks') }}"
                                    value="{{ old('remarks') }}" />
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
