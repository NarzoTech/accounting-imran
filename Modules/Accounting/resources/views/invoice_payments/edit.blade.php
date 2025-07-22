@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Edit Customer Due Receive') }}</title>
@endsection

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('admin.invoice_payments.update', $payment->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <div class="card">
                    <div class="card-header">
                        <h4 class="section_title">{{ __('Edit Customer Due Receive') }}</h4>
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
                                                {{ (isset($payment) && $payment->account_id == $account->id) || old('account_id') == $account->id ? 'selected' : '' }}>
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
                                        value="{{ old('method', $payment->method ?? '') }}">
                                    @error('method')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="note">{{ __('Note (Optional)') }}</label>
                                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror">{{ old('note', $payment->note ?? '') }}</textarea>
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
                                            @php
                                                $totalDue = 0;
                                            @endphp
                                            @forelse ($invoices as $invoice)
                                                @php
                                                    $totalDue +=
                                                        $invoice->amount_due + ($appliedAmounts[$invoice->id] ?? 0.0);
                                                @endphp
                                                <tr data-invoice-id="{{ $invoice->id }}">
                                                    <td>
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox"
                                                                class="custom-control-input invoice-checkbox"
                                                                id="invoice-checkbox-{{ $invoice->id }}"
                                                                name="invoice_selected[]" value="{{ $invoice->id }}"
                                                                data-due-amount="{{ $invoice->amount_due + ($appliedAmounts[$invoice->id] ?? 0.0) }}">
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
                                                        BDT
                                                        {{ $invoice->amount_due + ($appliedAmounts[$invoice->id] ?? 0.0) }}
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control receiving-amount-input"
                                                            name="amounts[{{ $invoice->id }}]"
                                                            data-invoice-id="{{ $invoice->id }}" step="0.01"
                                                            min="0"
                                                            value="{{ old('amounts.' . $invoice->id, $appliedAmounts[$invoice->id] ?? 0.0) }}">
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
                                    {{-- NEW: Payment Discount Field --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Payment Discount') }}</label>
                                            <input type="number" class="form-control" name="payment_discount"
                                                id="payment_discount_input" step="0.01" min="0"
                                                value="{{ old('payment_discount', $totalDiscountForGroup ?? 0.0) }}">
                                            <small
                                                class="form-text text-muted">{{ __('Optional discount applied to the total receivable amount for this payment.') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Total Receiving Amount') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="receiving_amount"
                                                id="total_receiving_amount_input" step="0.01" min="0" required
                                                value="{{ old('receiving_amount', $totalAppliedToInvoices + ($totalDiscountForGroup ?? 0.0)) }}">
                                        </div>
                                    </div>
                                    {{-- Advance Amount Display --}}
                                    <div class="col-12" id="advance_amount_group" style="display: none;">
                                        <div class="form-group">
                                            <label>{{ __('Advance Amount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-text" id="advance-addon"><i
                                                        class="fas fa-hand-holding-usd"></i></div>
                                                <input type="text" class="form-control" id="advance_amount_display"
                                                    value="0.00" readonly />
                                            </div>
                                            <small
                                                class="form-text text-muted">{{ __('This amount will be saved as an advance payment for the customer.') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Receiving Date') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datepicker" name="payment_date"
                                                value="{{ old('payment_date', \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y')) }}"
                                                autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-danger me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-success ">{{ __('Update') }}</button>
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
                // After updating total receiving, always check and update advance amount display
                updateAdvanceAmountDisplay();
            }

            // Function to manage the "Select All" checkbox state
            function updateSelectAllCheckbox() {
                const totalInvoices = $('.invoice-checkbox').length;
                const checkedInvoices = $('.invoice-checkbox:checked').length;
                $('#checkbox-all').prop('checked', totalInvoices > 0 && totalInvoices === checkedInvoices);
            }

            // Function to update the advance amount display
            function updateAdvanceAmountDisplay() {
                const totalReceivable = parseFloat($('#total_receivable_hidden').val());
                const paymentDiscount = parseFloat($('#payment_discount_input').val() || 0);
                const totalReceivingAmount = parseFloat($('#total_receiving_amount_input').val() || 0);

                // Calculate effective receivable after applying the payment discount
                let effectiveTotalReceivable = totalReceivable - paymentDiscount;
                if (effectiveTotalReceivable < 0) effectiveTotalReceivable =
                    0; // Receivable cannot be negative after discount

                let advanceAmount = 0;
                if (totalReceivingAmount > effectiveTotalReceivable) {
                    advanceAmount = totalReceivingAmount - effectiveTotalReceivable;
                    $('#advance_amount_display').val(advanceAmount.toFixed(2));
                    $('#advance_amount_group').show();
                } else {
                    $('#advance_amount_display').val('0.00');
                    $('#advance_amount_group').hide();
                }
            }



            $('#checkbox-all').on('click', function() {
                const isChecked = $(this).prop('checked');
                const totalReceivableOriginal = parseFloat($('#total_receivable_hidden').val());
                const paymentDiscount = parseFloat($('#payment_discount_input').val() || 0);
                let effectiveTotalReceivableForAllocation = totalReceivableOriginal - paymentDiscount;
                if (effectiveTotalReceivableForAllocation < 0) effectiveTotalReceivableForAllocation = 0;

                let amountToAllocate = effectiveTotalReceivableForAllocation;
                let allocatedSum = 0;

                $('.invoice-checkbox').each(function() {
                    const $thisCheckbox = $(this);
                    const $receivingInput = $thisCheckbox.closest('tr').find(
                        '.receiving-amount-input');
                    const dueAmount = parseFloat($thisCheckbox.data('due-amount'));

                    $thisCheckbox.prop('checked', isChecked);

                    if (isChecked && amountToAllocate > 0) {
                        const amount = Math.min(dueAmount, amountToAllocate);
                        $receivingInput.val(amount.toFixed(2));
                        allocatedSum += amount;
                        amountToAllocate -= amount;
                    } else {
                        $receivingInput.val('0.00');
                    }
                });
                $('#total_receiving_amount_input').val(allocatedSum.toFixed(2));
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
                    $(this).val(value.toFixed(2));
                } else {
                    $(this).val(value.toFixed(2));
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

            // NEW: Payment Discount Input Handler
            $('#payment_discount_input').on('input', function() {
                let discountValue = parseFloat($(this).val());
                if (isNaN(discountValue) || discountValue < 0) {
                    discountValue = 0;
                }
                $(this).val(discountValue.toFixed(2));

                // Trigger recalculation of the total receiving amount handler, which will re-allocate
                $('#total_receiving_amount_input').trigger('input');
            });


            // 4. Main "Total Receiving Amount" Input Handler (auto-allocation)
            $('#total_receiving_amount_input').on('input', function() {
                let totalInputAmount = parseFloat($(this).val());


                if (isNaN(totalInputAmount) || totalInputAmount < 0) {
                    totalInputAmount = 0;
                }
                $(this).val(totalInputAmount.toFixed(2));

                // Update and display advance amount based on the full input amount
                updateAdvanceAmountDisplay();

                // Get original total receivable and current payment discount
                const totalReceivableOriginal = parseFloat($('#total_receivable_hidden').val());
                const paymentDiscount = parseFloat($('#payment_discount_input').val() || 0);

                // Calculate effective total receivable for allocation to invoices
                let effectiveTotalReceivableForAllocation = totalReceivableOriginal - paymentDiscount;
                if (effectiveTotalReceivableForAllocation < 0) effectiveTotalReceivableForAllocation = 0;

                // Determine the amount that can be allocated specifically to invoices
                let amountForInvoiceAllocation = Math.min(totalInputAmount,
                    effectiveTotalReceivableForAllocation);
                // Reset all individual receiving amounts and checkboxes
                $('.receiving-amount-input').val('0.00');
                $('.invoice-checkbox').prop('checked', false);
                $('#checkbox-all').prop('checked', false);

                // Iterate through invoices and allocate payment up to `amountForInvoiceAllocation`
                $('.receiving-amount-input').each(function() {
                    const $thisInput = $(this);
                    const $thisCheckbox = $thisInput.closest('tr').find('.invoice-checkbox');
                    const dueAmount = parseFloat($thisCheckbox.data('due-amount'));
                    let currentVal = parseFloat($thisInput.val());

                    if (isNaN(currentVal) || currentVal <= 0) {
                        if (amountForInvoiceAllocation <= 0 || dueAmount <= 0) {
                            $thisInput.val('0.00');
                            $thisCheckbox.prop('checked', false);
                            return true;
                        }
                        const amountToApply = Math.min(amountForInvoiceAllocation, dueAmount);
                        $thisInput.val(amountToApply.toFixed(2));
                        $thisCheckbox.prop('checked', true);
                        amountForInvoiceAllocation -= amountToApply;
                    } else {
                        // Keep existing input value, just make sure checkbox is checked
                        $thisCheckbox.prop('checked', true);
                    }
                });

                updateSelectAllCheckbox();
            });


            // Initial call to set amounts if old input exists (e.g., after validation error)
            @if (old('receiving_amount') > 0 || old('payment_discount') > 0)
                // Trigger discount input first to ensure effective total receivable is set before receiving amount logic runs
                $('#payment_discount_input').trigger('input');
                $('#total_receiving_amount_input').trigger('input');
            @else

                updateTotalReceivingAmount();
                updateSelectAllCheckbox();
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

            // If in edit mode, pre-fill invoice amounts and check boxes
            // This block will run only when the page is loaded for editing
            @if (isset($payment))
                // Store applied amounts for easy access in JS
                const appliedAmounts = @json($appliedAmounts ?? []);

                // Set initial total receivable for edit mode
                $('#total_receivable_hidden').val("{{ $totalDue ?? 0 }}");
                $('#total_receivable_display').val("{{ $totalDue ?? 0 }}");

                let totalPreFilledAmount = 0;
                $('.receiving-amount-input').each(function() {
                    const invoiceId = $(this).data('invoice-id');
                    if (appliedAmounts[invoiceId] !== undefined) {
                        const applied = parseFloat(appliedAmounts[invoiceId]);
                        $(this).val(applied.toFixed(2));
                        $(this).closest('tr').find('.invoice-checkbox').prop('checked', true);
                        totalPreFilledAmount += applied;
                    }
                });

                // Set the total receiving amount input based on the pre-filled values
                // This will also trigger updateAdvanceAmountDisplay and updateSelectAllCheckbox
                $('#total_receiving_amount_input').val(totalPreFilledAmount.toFixed(2));
                $('#total_receiving_amount_input').trigger('input'); // Trigger to re-run allocation logic if needed
            @endif
        });
    </script>
@endpush
