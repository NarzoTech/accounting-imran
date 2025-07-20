@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Account') }}</title>
@endsection
@section('admin-content')
    <form action="{{ isset($account) ? route('admin.account.update', $account->id) : route('admin.account.store') }}"
        method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        @if (isset($account))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ isset($account) ? 'Edit Account' : 'Create Account' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="name" label="{{ __('Account Name') }}"
                                    value="{{ old('name', $account->name ?? '') }}" type="text" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="type" label="{{ __('Account Type') }}" required="true">
                                    <x-admin.select-option value="bank" text="{{ __('Bank') }}" :selected="old('type', $account->type ?? '') == 'bank'" />
                                    <x-admin.select-option value="mobile_banking" text="{{ __('Mobile Banking') }}"
                                        :selected="old('type', $account->type ?? '') == 'mobile_banking'" />
                                    <x-admin.select-option value="card" text="{{ __('Card') }}" :selected="old('type', $account->type ?? '') == 'card'" />
                                    <x-admin.select-option value="cash" text="{{ __('Cash/In Hand') }}"
                                        :selected="old('type', $account->type ?? '') == 'cash'" />
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="account_number" label="{{ __('Account Number') }}"
                                    value="{{ old('account_number', $account->account_number ?? '') }}" type="text" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="provider" label="{{ __('Provider') }}"
                                    value="{{ old('provider', $account->provider ?? '') }}" type="text" />
                            </div>

                            <div class="col-md-12 mb-4">
                                <x-admin.form-input name="balance" label="{{ __('Opening Balance') }}"
                                    value="{{ old('balance', $account->balance ?? 0) }}" type="number" step="0.01" />
                            </div>

                            <div class="col-12">
                                <x-admin.form-textarea name="note" label="{{ __('Note') }}"
                                    value="{{ old('note', $account->note ?? '') }}" />
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
