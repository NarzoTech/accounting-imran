@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Expense') }}</title>
@endsection
@section('admin-content')
    <form action="{{ isset($expense) ? route('admin.expense.update', $expense->id) : route('admin.expense.store') }}"
        method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        @if (isset($expense))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ isset($expense) ? 'Edit Expense' : 'Create Expense' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="title" label="{{ __('Expense Title') }}"
                                    value="{{ old('title', $expense->title ?? '') }}" type="text" required="true" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="amount" label="{{ __('Amount') }}"
                                    value="{{ old('amount', $expense->amount ?? 0) }}" type="number" step="0.01"
                                    required="true" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="account_id" label="{{ __('Account') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Account') }}" />
                                    @foreach ($accounts as $account)
                                        <x-admin.select-option :value="$account->id" :text="$account->name" :selected="old('account_id', $expense->account_id ?? '') == $account->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="container_id" label="{{ __('Container (Optional)') }}">
                                    <x-admin.select-option value="" text="{{ __('Select Container') }}" />
                                    @foreach ($containers as $container)
                                        <x-admin.select-option :value="$container->id" :text="$container->container_number .
                                            ' (' .
                                            $container->container_type .
                                            ')'" :selected="old('container_id', $expense->container_id ?? '') == $container->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="payment_method" label="{{ __('Payment Method') }}"
                                    value="{{ old('payment_method', $expense->payment_method ?? '') }}" type="text" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="reference" label="{{ __('Reference') }}"
                                    value="{{ old('reference', $expense->reference ?? '') }}" type="text" />
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="attachment" label="{{ __('Attachment') }}" value=""
                                    type="file" />
                                @if (isset($expense) && $expense->attachment)
                                    <p class="form-text text-muted">Current: <a
                                            href="{{ asset('storage/' . $expense->attachment) }}" target="_blank">View
                                            Attachment</a></p>
                                @endif
                            </div>

                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="date" label="{{ __('Date') }}"
                                    value="{{ old('date', $expense->date ?? date('Y-m-d')) }}" type="date"
                                    required="true" />
                            </div>

                            <div class="col-12">
                                <x-admin.form-textarea name="note" label="{{ __('Note') }}"
                                    value="{{ old('note', $expense->note ?? '') }}" />
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
