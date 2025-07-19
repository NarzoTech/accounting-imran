@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Customer Due Receive') }}</title>
@endsection

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('admin.invoice_payments.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <div class="card">
                    <div class="card-header">
                        <h4 class="section_title">{{ __('Customer Due Receive') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-2">
                                    {{ __('Name') }}: {{ $customer->name }}
                                </h6>
                                <h6 class="mb-2">
                                    {{ __('Phone') }}: {{ $customer->phone }}
                                </h6>
                                <h6 class="mb-2">
                                    {{ __('Address') }}: {{ $customer->address }}
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">{{ __('Payment Account') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="account_id" id="account_id"
                                        class="form-control @error('account_id') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Account') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="method">{{ __('Payment Method (Optional)') }}</label>
                                    <input type="text" name="method" id="method"
                                        class="form-control @error('method') is-invalid @enderror"
                                        value="{{ old('method') }}">
                                    @error('method')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="note">{{ __('Note (Optional)') }}</label>
                                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="invoices_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" data-checkboxes="checkgroup"
                                                            data-checkbox-role="dad" class="custom-control-input"
                                                            id="checkbox-all">
                                                        <label for="checkbox-all"
                                                            class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </th>
                                                <th>{{ __('Invoice No') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Invoice Amount') }}</th>
                                                <th>{{ __('Due Amount') }}</th>
                                                <th>{{ __('Receiving Amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($invoices as $invoice)
                                                <tr data-invoice-id="{{ $invoice->id }}">
                                                    <td>
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox"
                                                                class="custom-control-input invoice-checkbox"
                                                                id="invoice-checkbox-{{ $invoice->id }}"
                                                                name="invoice_selected[]" value="{{ $invoice->id }}"
                                                                data-due-amount="{{ $invoice->amount_due }}">
                                                            <label for="invoice-checkbox-{{ $invoice->id }}"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $invoice->invoice_number }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                        BDT {{ $invoice->total_amount }}
                                                    </td>
                                                    <td class="invoice-due-display">
                                                        BDT {{ $invoice->amount_due }}
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control receiving-amount-input"
                                                            name="amounts[{{ $invoice->id }}]"
                                                            data-invoice-id="{{ $invoice->id }}" step="0.01"
                                                            min="0" value="0.00">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        {{ __('No outstanding invoices found for this customer.') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- Summary --}}
                        <div class="row mt-5 justify-content-end">
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Total Receivable') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-text" id="basic-addon11"><i
                                                        class="fas fa-money-check-alt"></i></div>
                                                <input type="number" class="form-control" placeholder="Total Receivable"
                                                    aria-label="Total Receivable" aria-describedby="basic-addon11"
                                                    id="total_receivable_display" name="total_receivable_display"
                                                    value="{{ $totalDue }}" step="0.01" readonly />
                                                <input type="hidden" id="total_receivable_hidden"
                                                    value="{{ $totalDue }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Total Receiving Amount') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="receiving_amount"
                                                id="total_receiving_amount_input" step="0.01" min="0" required
                                                value="{{ old('receiving_amount', 0.0) }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Receiving Date') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datepicker" name="payment_date"
                                                value="{{ now()->format('d-m-Y') }}" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-danger me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-success ">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            'use strict';

            // Function to update the total receiving amount input based on individual invoice amounts
            function updateTotalReceivingAmount() {
                let total = 0;
                $('.receiving-amount-input').each(function() {
                    total += parseFloat($(this).val() || 0);
                });
                $('#total_receiving_amount_input').val(total.toFixed(2));
            }

            // Function to manage the "Select All" checkbox state
            function updateSelectAllCheckbox() {
                const totalInvoices = $('.invoice-checkbox').length;
                const checkedInvoices = $('.invoice-checkbox:checked').length;
                $('#checkbox-all').prop('checked', totalInvoices > 0 && totalInvoices === checkedInvoices);
            }

            // 1. "Select All" checkbox handler
            $('#checkbox-all').on('click', function() {
                const isChecked = $(this).prop('checked');
                let allocatedSum = 0;

                $('.invoice-checkbox').each(function() {
                    const $thisCheckbox = $(this);
                    const $receivingInput = $thisCheckbox.closest('tr').find(
                        '.receiving-amount-input');
                    const dueAmount = parseFloat($thisCheckbox.data('due-amount'));

                    $thisCheckbox.prop('checked', isChecked);

                    if (isChecked) {
                        $receivingInput.val(dueAmount.toFixed(2));
                        allocatedSum += dueAmount;
                    } else {
                        $receivingInput.val('0.00');
                    }
                });
                updateTotalReceivingAmount();
            });

            // 2. Individual Invoice Checkbox Handler
            $('.invoice-checkbox').on('click', function() {
                const $thisCheckbox = $(this);
                const $receivingInput = $thisCheckbox.closest('tr').find('.receiving-amount-input');
                const dueAmount = parseFloat($thisCheckbox.data('due-amount'));

                if ($thisCheckbox.prop('checked')) {
                    $receivingInput.val(dueAmount.toFixed(2));
                } else {
                    $receivingInput.val('0.00');
                }
                updateTotalReceivingAmount();
                updateSelectAllCheckbox();
            });

            // 3. Individual Receiving Amount Input Handler (manual input)
            $('.receiving-amount-input').on('input', function() {
                let value = parseFloat($(this).val());
                const dueAmount = parseFloat($(this).closest('tr').find('.invoice-checkbox').data(
                    'due-amount'));

                if (isNaN(value) || value < 0) {
                    value = 0;
                }

                // Cap the input value at the due amount for that invoice
                if (value > dueAmount) {
                    value = dueAmount;
                    $(this).val(value.toFixed(2)); // Correct the input field if it exceeds due
                } else {
                    $(this).val(value.toFixed(2)); // Format to 2 decimal places
                }


                const $checkbox = $(this).closest('tr').find('.invoice-checkbox');
                if (value > 0) {
                    $checkbox.prop('checked', true);
                } else {
                    $checkbox.prop('checked', false);
                }
                updateTotalReceivingAmount();
                updateSelectAllCheckbox();
            });


            // 4. Main "Total Receiving Amount" Input Handler (auto-allocation)
            $('#total_receiving_amount_input').on('input', function() {
                let remainingPayment = parseFloat($(this).val());

                if (isNaN(remainingPayment) || remainingPayment < 0) {
                    remainingPayment = 0;
                }
                $(this).val(remainingPayment.toFixed(2)); // Format to 2 decimal places

                // Reset all individual receiving amounts and checkboxes
                $('.receiving-amount-input').val('0.00');
                $('.invoice-checkbox').prop('checked', false);
                $('#checkbox-all').prop('checked', false);

                // Iterate through invoices and allocate payment
                $('.receiving-amount-input').each(function() {
                    const $thisInput = $(this);
                    const $thisCheckbox = $thisInput.closest('tr').find('.invoice-checkbox');
                    const dueAmount = parseFloat($thisCheckbox.data('due-amount'));

                    if (remainingPayment <= 0) {
                        // Stop allocating if no payment left
                        $thisInput.val('0.00');
                        $thisCheckbox.prop('checked', false);
                        return true; // continue to next iteration in jQuery .each()
                    }

                    if (dueAmount > 0) {
                        const amountToApply = Math.min(remainingPayment, dueAmount);
                        $thisInput.val(amountToApply.toFixed(2));
                        $thisCheckbox.prop('checked', true);
                        remainingPayment -= amountToApply;
                    } else {
                        $thisInput.val('0.00');
                        $thisCheckbox.prop('checked', false);
                    }
                });
                updateSelectAllCheckbox(); // Update select all checkbox after allocation
            });


            // Initial call to set amounts if old input exists (e.g., after validation error)
            @if (old('receiving_amount') > 0)
                $('#total_receiving_amount_input').trigger('input');
            @endif

            // Initialize datepicker
            if ($.fn.datepicker) {
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                });
            } else {
                console.warn('jQuery UI Datepicker not found. Please ensure it\'s loaded.');
            }

            // Initial calculation on page load (if any pre-filled values exist)
            updateTotalReceivingAmount();
            updateSelectAllCheckbox();
        });
    </script>
@endpush
