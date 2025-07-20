@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Transfer') }}</title>
@endsection
@section('admin-content')
    <form action="{{ isset($transfer) ? route('admin.transfer.update', $transfer->id) : route('admin.transfer.store') }}"
        method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        @if (isset($transfer))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ isset($transfer) ? 'Edit Transfer' : 'Create Transfer' }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="from_account_id" label="{{ __('From Account') }}"
                                    required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Account') }}" />
                                    @foreach ($accounts as $account)
                                        <x-admin.select-option :value="$account->id" :text="$account->name" :selected="old('from_account_id', $transfer->from_account_id ?? '') ==
                                            $account->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="to_account_id" label="{{ __('To Account') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Account') }}" />
                                    @foreach ($accounts as $account)
                                        <x-admin.select-option :value="$account->id" :text="$account->name" :selected="old('to_account_id', $transfer->to_account_id ?? '') == $account->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="amount" label="{{ __('Amount') }}"
                                    value="{{ old('amount', $transfer->amount ?? '') }}" type="number" step="0.01"
                                    required="true" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="date" label="{{ __('Date') }}"
                                    value="{{ old('date', $transfer->date ?? date('Y-m-d')) }}" type="date"
                                    required="true" />
                            </div>

                            <div class="col-md-12 mb-4">
                                <x-admin.form-input name="reference" label="{{ __('Reference') }}"
                                    value="{{ old('reference', $transfer->reference ?? '') }}" type="text" />
                            </div>

                            <div class="col-12">
                                <x-admin.form-textarea name="note" label="{{ __('Note') }}"
                                    value="{{ old('note', $transfer->note ?? '') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn />
            </div>
        </div>
    </form>
@endsection
