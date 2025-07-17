@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Income') }}</title>
@endsection
@section('admin-content')
    <form
        action="{{ isset($payment) ? route('admin.invoice_payments.update', $payment->id) : route('admin.invoice_payments.store') }}"
        method="post" id="invoice_payment_form">
        @csrf
        @if (isset($payment))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Main Form -->
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="section_title">{{ isset($payment) ? 'Edit Payment' : 'Record New Payment' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Customer Selection -->
                            <div class="col-md-6">
                                <x-admin.form-select name="customer_id" label="{{ __('Customer') }}" required="true"
                                    id="customer_id">
                                    <x-admin.select-option value="" text="{{ __('Select Customer') }}" />
                                    @foreach ($customers as $customer)
                                        <x-admin.select-option :value="$customer->id" :text="$customer->name" :selected="old('customer_id', $payment->invoice->customer_id ?? '') ==
                                            $customer->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <!-- Payment Amount -->
                            <div class="col-md-6">
                                <x-admin.form-input name="amount" label="{{ __('Payment Amount') }}"
                                    value="{{ old('amount', $payment->amount ?? '') }}" type="number" step="0.01"
                                    required="true" />
                            </div>

                            <!-- Payment Account -->
                            <div class="col-md-6">
                                <x-admin.form-select name="account_id" label="{{ __('Payment Account') }}" required="true">
                                    <x-admin.select-option value="" text="{{ __('Select Account') }}" />
                                    @foreach ($accounts as $account)
                                        <x-admin.select-option :value="$account->id" :text="$account->name" :selected="old('account_id', $payment->account_id ?? '') == $account->id" />
                                    @endforeach
                                </x-admin.form-select>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-6">
                                <x-admin.form-input name="method" label="{{ __('Payment Method') }}"
                                    value="{{ old('method', $payment->method ?? '') }}" type="text" />
                            </div>

                            <!-- Invoice Selection (Dynamic based on customer) -->
                            <div class="col-md-12 mt-3" id="invoice_selection_area">
                                <label for="invoice_ids"
                                    class="form-label">{{ __('Apply Payment to Invoices (Optional)') }}</label>
                                <div id="invoices_list" class="border p-3 rounded"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <p class="text-muted" id="no_invoices_message">Please select a customer to see
                                        outstanding invoices.</p>
                                    {{-- Invoices will be loaded here via AJAX --}}
                                </div>
                                <small class="form-text text-muted">Select one or more invoices to apply this payment. If no
                                    invoices are selected, or if the payment exceeds the selected invoices' total due, the
                                    remainder will be recorded as an advance.</small>
                            </div>

                            <!-- Note -->
                            <div class="col-12 mt-3">
                                <x-admin.form-textarea name="note" label="{{ __('Note') }}"
                                    value="{{ old('note', $payment->note ?? '') }}" />
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Function to load outstanding invoices for a selected customer
                $('#customer_id').change(function() {
                    var customerId = $(this).val();
                    var invoicesList = $('#invoices_list');
                    var noInvoicesMessage = $('#no_invoices_message');
                    invoicesList.empty(); // Clear previous invoices

                    if (customerId) {
                        noInvoicesMessage.hide();
                        // Fetch invoices via AJAX
                        $.ajax({
                            url: '{{ route('admin.invoice_payments.get_outstanding_invoices') }}',
                            method: 'GET',
                            data: {
                                customer_id: customerId
                            },
                            success: function(response) {
                                if (response.invoices && response.invoices.length > 0) {
                                    $.each(response.invoices, function(index, invoice) {
                                        let checked = '';
                                        // For edit mode, pre-select invoices if this payment was applied to them
                                        @if (isset($payment) && $payment->invoice_id)
                                            if (invoice.id ==
                                                {{ $payment->invoice_id }}
                                                ) { // Simple case for single invoice payment
                                                checked = 'checked';
                                            }
                                        @endif
                                        // For new payment, or if multiple invoices can be selected
                                        // You might need more complex logic here if multiple invoice payments are stored in one record
                                        // For now, assuming one payment record per invoice or advance
                                        invoicesList.append(`
                                    <div class="form-check">
                                        <input class="form-check-input invoice-checkbox" type="checkbox" name="invoice_ids[]" value="${invoice.id}" id="invoice_${invoice.id}" data-due-amount="${invoice.amount_due}" ${checked}>
                                        <label class="form-check-label" for="invoice_${invoice.id}">
                                            Invoice #${invoice.invoice_number} (Due: {{ config('app.currency_symbol', '$') }}${invoice.amount_due.toFixed(2)})
                                        </label>
                                    </div>
                                `);
                                    });
                                } else {
                                    invoicesList.append(
                                        '<p class="text-muted">No outstanding invoices for this customer.</p>'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.error("Error fetching invoices:", xhr.responseText);
                                invoicesList.append(
                                    '<p class="text-danger">Error loading invoices. Please try again.</p>'
                                );
                            }
                        });
                    } else {
                        noInvoicesMessage.show();
                    }
                });

                // Trigger change on page load if a customer is already selected (e.g., in edit mode or if old input exists)
                @if (old('customer_id') || (isset($payment) && $payment->invoice))
                    $('#customer_id').trigger('change');
                @endif
            });
        </script>
    @endpush
@endsection
