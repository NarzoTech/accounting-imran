@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Repayment') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.repayment.update', $repayment->id) }}" method="post" id="create_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Main Form Area -->
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ __('Edit Repayment') }}</h4>
                    </div>
                    <div class="card-body">
                        <!-- Investor Details -->
                        <div class="mb-4 border-bottom pb-2">
                            <h5 class="mb-3">{{ __('Investor Information') }}</h5>
                            <p><strong>{{ __('Name') }}:</strong> {{ $repayment->investor->name }}</p>
                            <p><strong>{{ __('Email') }}:</strong> {{ $repayment->investor->email ?? '-' }}</p>
                            <p><strong>{{ __('Phone') }}:</strong> {{ $repayment->investor->phone ?? '-' }}</p>
                            <p><strong>{{ __('Address') }}:</strong> {{ $repayment->investor->address ?? '-' }}</p>
                        </div>

                        <div class="row">
                            <!-- Investment Selection -->
                            <div class="col-md-12">
                                <x-admin.form-select name="investment_id" label="{{ __('Investment') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Investment') }}" />
                                    @foreach ($repayment->investor->investments as $investment)
                                        <x-admin.select-option :value="$investment->id" :selected="$repayment->investment_id == $investment->id" :text="__('Amount:') .
                                            ' ' .
                                            number_format($investment->amount, 2) .
                                            ' | ' .
                                            $investment->investment_date" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <x-admin.form-input name="amount" label="{{ __('Repayment Amount') }}"
                                    value="{{ old('amount', $repayment->amount) }}" type="number" step="0.01"
                                    required="true" id="amount" />
                            </div>

                            <!-- Repayment Date -->
                            <div class="col-md-6">
                                <x-admin.form-input name="repayment_date" label="{{ __('Repayment Date') }}"
                                    value="{{ old('repayment_date', $repayment->repayment_date->format('Y-m-d')) }}"
                                    type="date" required="true" id="repayment_date" />
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <x-admin.form-textarea name="notes" label="{{ __('Notes') }}"
                                    value="{{ old('notes', $repayment->notes) }}" id="notes" />
                            </div>

                            <!-- Hidden Investor ID -->
                            <input type="hidden" name="investor_id" value="{{ $repayment->investor_id }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn :edit="true" />
            </div>
        </div>
    </form>
@endsection
