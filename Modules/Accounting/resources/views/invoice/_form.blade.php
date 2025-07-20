{{-- resources/views/admin/invoices/_form.blade.php --}}

<form action="{{ isset($invoice) ? route('admin.invoice.update', $invoice->id) : route('admin.invoice.store') }}"
    method="post" id="invoice_form" enctype="multipart/form-data">
    @csrf
    @if (isset($invoice))
        @method('PUT')
    @endif

    <div class="invoice-container mt-0 rounded-3">

        <div class="row">
            <!-- Left Column: Customer and Invoice Details -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body border rounded-3">
                        <div id="customer-display" class="customer-info-box">
                            @if (isset($invoice) && $invoice->customer)
                                {{-- Pre-filled customer details for edit mode --}}
                                <div class="customer-details">
                                    <p class="mb-1">Bill to</p>
                                    <h5>{{ $invoice->customer->name }}</h5>
                                    <p>{{ $invoice->customer->address ?? '' }}</p>
                                    <p>{{ $invoice->customer->email ?? '' }}</p>
                                    <p>{{ $invoice->customer->phone ?? '' }}</p>
                                    <div class="edit-options mt-2">
                                        <a href="javascript:;" id="edit-customer-btn" data-bs-toggle="modal"
                                            data-bs-target="#editCustomerModal">Edit {{ $invoice->customer->name }}</a>
                                        <a href="#" id="change-customer-btn" data-bs-toggle="modal"
                                            data-bs-target="#customerModal">Choose a different customer</a>
                                    </div>
                                    <input type="hidden" name="customer_id" value="{{ $invoice->customer->id }}">
                                </div>
                            @else
                                {{-- Initial state for create mode --}}
                                <i class="fas fa-user-plus add-customer-btn" data-bs-toggle="modal"
                                    data-bs-target="#customerModal"></i>
                                <div class="add-customer-btn mt-2" data-bs-toggle="modal"
                                    data-bs-target="#customerModal">
                                    Add a customer</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Invoice Number, Dates -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body border rounded-3">
                        <div class="row mb-3 align-items-center">
                            <label for="invoiceNumber"
                                class="col-sm-5 col-form-label">{{ __('Invoice Number') }}</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="invoiceNumber" name="invoice_number"
                                    value="{{ old('invoice_number', $invoice->invoice_number ?? '') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="poSoNumber" class="col-sm-5 col-form-label">{{ __('P.O./S.O. number') }}</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="poSoNumber"
                                    placeholder="Enter P.O./S.O. Number" name="po_so_number"
                                    value="{{ old('po_so_number', $invoice->po_so_number ?? '') }}">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="invoiceDate" class="col-sm-5 col-form-label">{{ __('Invoice Date') }}</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="invoiceDate"
                                    value="{{ old('invoice_date', isset($invoice) ? $invoice->invoice_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    name="invoice_date" required>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="paymentDate"
                                class="col-sm-5 col-form-label">{{ __('Payment Due Date') }}</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" id="paymentDate"
                                    value="{{ old('payment_date', isset($invoice) ? $invoice->payment_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}"
                                    name="payment_date" required>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="container_id" class="col-sm-5 col-form-label">{{ __('Container') }}</label>
                            <div class="col-sm-7">
                                <select name="container_id" id="container_id" class="form-control select2">
                                    <option value="">{{ __('Select Container') }}</option>
                                    @foreach ($containers as $container)
                                        <option value="{{ $container->id }}"
                                            {{ old('container_id', $invoice->container_id ?? '') == $container->id ? 'selected' : '' }}>
                                            {{ $container->container_number }} ({{ $container->container_type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details Section -->
        <div class="card mb-4">
            <div class="card-header">
                {{ __('Item Details') }}
            </div>
            <div class="card-body">
                <!-- Initial Item Display with Search Product -->
                <div id="initial-item-section"
                    style="{{ isset($invoice) && $invoice->items->isNotEmpty() ? 'display: none;' : '' }}">
                    <input type="text" class="form-control search-input" placeholder="Search Products"
                        data-bs-toggle="modal" data-bs-target="#productSearchModal" readonly>
                    <div class="mt-5">
                        <a href="#" class="add-new-product btn btn-primary" id="add-new-product-initial"
                            data-bs-toggle="modal" data-bs-target="#productSearchModal"><i
                                class="fas fa-plus-circle me-2"></i>Add "as a new
                            product"</a>
                    </div>
                </div>

                <!-- Item Table (initially hidden or empty, shown when items are added) -->
                <div class="table-responsive" id="item-table-container"
                    style="{{ isset($invoice) && $invoice->items->isNotEmpty() ? '' : 'display: none;' }}">
                    <table class="table table-borderless item-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 35%;">{{ __('Item') }}</th>
                                <th style="width: 30%;">{{ __('Description') }}</th>
                                <th style="width: 10%;">{{ __('Qty') }}</th>
                                <th style="width: 10%;">{{ __('Unit') }}</th>
                                <th style="width: 10%;">{{ __('Price') }}</th>
                                <th style="width: 10%;">{{ __('Amount') }}</th>
                                <th style="width: 5%;"></th> <!-- For delete button -->
                            </tr>
                        </thead>
                        <tbody id="invoice-items">
                            @if (isset($invoice) && $invoice->items->isNotEmpty())
                                @foreach ($invoice->items as $index => $item)
                                    <tr data-item-id="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? 'N/A' }}
                                            <input type="hidden" name="items[{{ $item->id }}][item_id]"
                                                value="{{ $item->id }}">
                                            <input type="hidden" name="items[{{ $item->id }}][product_id]"
                                                value="{{ $item->product_id }}">
                                        </td>
                                        <td><input type="text" class="form-control item-description"
                                                value="{{ old('items.' . $item->id . '.description', $item->description ?? '') }}"
                                                placeholder="Description"
                                                name="items[{{ $item->id }}][description]"></td>
                                        <td><input type="number" class="form-control item-qty"
                                                value="{{ old('items.' . $item->id . '.quantity', $item->quantity) }}"
                                                min="1" name="items[{{ $item->id }}][quantity]"></td>
                                        <td><input type="string" class="form-control item-unit"
                                                value="{{ old('items.' . $item->id . '.unit', $item->unit ?? 'pcs') }}"
                                                name="items[{{ $item->id }}][unit]"></td>
                                        <td><input type="number" class="form-control item-price"
                                                value="{{ old('items.' . $item->id . '.price', $item->price) }}"
                                                min="0" step="0.01"
                                                name="items[{{ $item->id }}][price]"></td>
                                        <td><input type="text" class="form-control item-amount"
                                                value="{{ old('items.' . $item->id . '.amount', $item->amount) }}"
                                                readonly name="items[{{ $item->id }}][amount]"></td>
                                        <td><button type="button" class="btn btn-sm btn-danger delete-item"><i
                                                    class="fas fa-trash-alt"></i></button></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="text-start mt-3" id="add-new-item-btn-container"
                    style="{{ isset($invoice) && $invoice->items->isNotEmpty() ? '' : 'display: none;' }}">
                    <a href="#" class="add-item-btn" id="add-new-item" data-bs-toggle="modal"
                        data-bs-target="#productSearchModal"><i
                            class="fas fa-plus-circle me-2"></i>{{ __('Add new item') }}</a>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-12">
                        <table class="table table-borderless summary-table rounded-3">
                            <tbody>
                                <tr>
                                    <td>{{ __('Subtotal') }}</td>
                                    <td class="text-end" id="subtotal">
                                        {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                    <input type="hidden" name="subtotal" id="hidden_subtotal"
                                        value="{{ $invoice->subtotal ?? 0 }}">
                                </tr>
                                <tr>
                                    <td>{{ __('Discount') }} <input type="number"
                                            class="form-control d-inline-block w-25 ms-2 me-1"
                                            id="discount-percentage"
                                            value="{{ old('discount_percentage', $invoice->discount_percentage ?? 0) }}"
                                            name="discount_percentage">%</td>
                                    <td class="text-end" id="discount-amount">
                                        {{ number_format((($invoice->subtotal ?? 0) * ($invoice->discount_percentage ?? 0)) / 100, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Add Delivery Charge') }}</td>
                                    <td class="text-end">
                                        <input type="number" class="form-control d-inline-block w-50"
                                            id="delivery-charge"
                                            value="{{ old('delivery_charge', $invoice->delivery_charge ?? 0) }}"
                                            step="0.01" name="delivery_charge">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="total-amount">{{ __('Grand Total') }}</td>
                                    <td class="text-end total-amount" id="grand-total">
                                        {{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                    <input type="hidden" name="total_amount" id="hidden_total_amount"
                                        value="{{ $invoice->total_amount ?? 0 }}">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3 mt-5">
                    <label for="notesTerms" class="form-label">{{ __('Notes / Terms') }}</label>
                    <textarea class="form-control" id="notesTerms" rows="3"
                        placeholder="Enter notes or terms of service that you are visible to your customer" name="notes_terms">{{ old('notes_terms', $invoice->notes_terms ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center mb-3">
                    <div class="col-md-3">{{ __('Payment Status') }}:</div>
                    <div class="col-md-9 payment-status">
                        <div class="btn-group" role="group" aria-label="Payment Status">
                            <button type="button"
                                class="btn {{ isset($initialPaymentStatus) && ($initialPaymentStatus === 'Unpaid' || $initialPaymentStatus === 'Partially Paid') ? 'active' : '' }}"
                                data-status="unpaid" id="status-unpaid">{{ __('Unpaid') }}</button>
                            <button type="button"
                                class="btn {{ isset($initialPaymentStatus) && $initialPaymentStatus === 'Paid' ? 'active' : '' }}"
                                data-status="paid" id="status-paid">{{ __('Paid') }}</button>
                        </div>
                        <input type="hidden" name="payment_status_input" id="payment_status_input"
                            value="{{ isset($initialPaymentStatus) && $initialPaymentStatus === 'Paid' ? 'paid' : 'unpaid' }}">
                    </div>
                </div>
                <!-- Payment related input fields, initially hidden based on status -->
                <div id="payment-details-input-fields" class="payment-details-section"
                    style="{{ isset($initialPaymentStatus) && $initialPaymentStatus === 'Paid' ? '' : 'display: none;' }}">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="paymentDateInput" class="form-label">{{ __('Payment Date') }}</label>
                            <input type="date" class="form-control" id="paymentDateInput"
                                value="{{ old('payment_date_input', isset($invoice) && $invoice->payments->isNotEmpty() ? $invoice->payments->first()->created_at->format('Y-m-d') : date('Y-m-d')) }}"
                                name="payment_date_input">
                        </div>
                        <div class="col-md-4">
                            <label for="paymentAccount" class="form-label">{{ __('Payment Account') }}</label>
                            <select class="form-select" id="paymentAccount" name="payment_account_id">
                                {{-- Changed name to payment_account_id --}}
                                <option value="">{{ __('Select Account') }}</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ old('payment_account_id', isset($invoice) && $invoice->payments->isNotEmpty() ? $invoice->payments->first()->account_id : '') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="amountPaid" class="form-label">{{ __('Amount Paid') }}</label>
                            <input type="number" class="form-control" id="amountPaid"
                                value="{{ old('amount_paid', isset($initialAmountPaid) ? $initialAmountPaid : 0) }}"
                                step="0.01" name="amount_paid">
                            {{-- Hidden field to pass payment method text if needed for payment record --}}
                            <input type="hidden" name="payment_method_text" id="payment_method_text"
                                value="{{ old('payment_method_text', isset($invoice) && $invoice->payments->isNotEmpty() ? $invoice->payments->first()->method : 'Cash') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Footer Section (now an Accordion) -->
        <div class="accordion mb-4" id="invoiceFooterAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingInvoiceFooter">
                    <button class="accordion-button collapsed rounded-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseInvoiceFooter" aria-expanded="false"
                        aria-controls="collapseInvoiceFooter">
                        {{ __('Invoice Footer') }}
                    </button>
                </h2>
                <div id="collapseInvoiceFooter"
                    class="accordion-collapse collapse {{ old('invoice_footer', $invoice->invoice_footer ?? '') ? 'show' : '' }}"
                    aria-labelledby="headingInvoiceFooter" data-bs-parent="#invoiceFooterAccordion">
                    <div class="accordion-body mt-5">
                        <label for="invoiceFooter"
                            class="form-label visually-hidden">{{ __('Invoice Footer Text') }}</label>
                        <textarea class="form-control" id="invoiceFooter" rows="2" name="invoice_footer">{{ old('invoice_footer', $invoice->invoice_footer ?? 'Just wanted to say thank you for your purchase. We\'re so lucky to have customers like you!') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary btn-save-invoice">{{ __('Save Invoice') }}</button>
        </div>
    </div>
</form>

<!-- Customer Selection Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">{{ __('Select Customer') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <!-- Search and Add Customer Input -->
                <div class="customer-search-input-group input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="customerSearchInput"
                        placeholder="Type a customer name">
                </div>
                <div id="customerSearchResults" class="list-group">
                    <!-- Customer list will be populated here by JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>

            </div>
        </div>
    </div>
</div>

<!-- Product Search Modal -->
<div class="modal fade" id="productSearchModal" tabindex="-1" aria-labelledby="productSearchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productSearchModalLabel">{{ __('Search Products') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <input type="text" class="form-control mb-3" id="productSearchInput"
                    placeholder="Search Products">
                <div id="productSearchResults" class="list-group">
                    <!-- Search results will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">{{ __('Edit Customer Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <div class="mb-3">
                        <label for="editCustomerName" class="form-label">{{ __('Customer Name') }}</label>
                        <input type="text" class="form-control" id="editCustomerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCustomerAddress" class="form-label">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="editCustomerAddress">
                    </div>
                    <div class="mb-3">
                        <label for="editCustomerEmail" class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="editCustomerEmail">
                    </div>
                    <div class="mb-3">
                        <label for="editCustomerPhone" class="form-label">{{ __('Phone') }}</label>
                        <input type="tel" class="form-control" id="editCustomerPhone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary"
                    id="saveCustomerChanges">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('backend\css\invoice-create.css') }}">
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            let itemCounter =
                {{ isset($invoice) && $invoice->items->isNotEmpty() ? $invoice->items->max('id') + 1 : 0 }}; // Start counter from max existing item ID + 1
            let currentCustomer = null; // To store the currently selected customer's data
            let initialInvoiceItems = []; // To store initial items for edit mode

            // Mock product data (replace with actual AJAX fetch or passed data)
            const products = @json($products);
            const backendCustomers = @json($customers);
            const accounts = @json($accounts); // Pass accounts from controller

            // Map backend customer data to frontend format
            function mapCustomerData(backendCustomer) {
                const fullName = backendCustomer.name ||
                    `${backendCustomer.first_name || ''} ${backendCustomer.last_name || ''}`.trim();
                const addressParts = [];
                if (backendCustomer.address) addressParts.push(backendCustomer.address);
                if (backendCustomer.city) addressParts.push(backendCustomer.city);
                if (backendCustomer.state) addressParts.push(backendCustomer.state);
                if (backendCustomer.country) addressParts.push(backendCustomer.country);
                const fullAddress = addressParts.join(', ');

                const avatarText = avatarTextGenerator(fullName);

                return {
                    id: backendCustomer.id,
                    name: fullName,
                    address: fullAddress,
                    email: backendCustomer.email || '',
                    phone: backendCustomer.phone || '',
                    avatarText: avatarText
                };
            }

            const customers = backendCustomers.map(mapCustomerData);

            // For edit mode, pre-fill current customer and items
            @if (isset($invoice))
                currentCustomer = customers.find(c => c.id === {{ $invoice->customer_id }});
                // displayCustomerDetails(currentCustomer);

                // Populate initialInvoiceItems for edit mode
                @foreach ($invoice->items as $item)
                    initialInvoiceItems.push({
                        item_id: {{ $item->id }}, // Existing item ID
                        product_id: {{ $item->product_id ?? 'null' }},
                        name: "{{ $item->product->name ?? 'N/A' }}",
                        description: "{{ $item->description ?? '' }}",
                        quantity: {{ $item->quantity }},
                        unit: "{{ $item->unit ?? 'pcs' }}",
                        price: {{ $item->price }},
                        amount: {{ $item->amount }}
                    });
                @endforeach
            @endif

            function avatarTextGenerator(name) {
                if (!name) return '';
                const words = name.trim().split(' ');
                if (words.length === 1) {
                    return words[0].charAt(0).toUpperCase();
                }
                return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
            }

            // Function to add a selected item to the invoice table
            function addItemToInvoiceTable(item, isExistingItem = false) {
                let itemId = isExistingItem ? item.item_id : ++itemCounter; // Use existing ID or new counter
                const productId = item.id;
                const newRow = `
                    <tr data-item-unique-id="${itemId}" data-item-id="${isExistingItem ? item.item_id : ''}">
                        <td>${$('#invoice-items tr').length + 1}</td>
                        <td>${item.name}
                            <input type="hidden" name="items[${itemId}][item_id]" value="${isExistingItem ? item.item_id : ''}">
                            <input type="hidden" name="items[${itemId}][product_id]" value="${productId}">
                        </td>
                        <td><input type="text" class="form-control item-description" value="${item.description ?? ''}" placeholder="Description" name="items[${itemId}][description]"></td>
                        <td><input type="number" class="form-control item-qty" value="${item.quantity ?? 1}" min="1" name="items[${itemId}][quantity]"></td>
                        <td><input type="string" class="form-control item-unit" value="${item.unit ?? 'pcs'}" name="items[${itemId}][unit]"></td>
                        <td><input type="number" class="form-control item-price" value="${item.price ?? 0}" min="0" step="0.01" name="items[${itemId}][price]"></td>
                        <td><input type="text" class="form-control item-amount" value="${item.amount ?? 0}" readonly name="items[${itemId}][amount]"></td>
                        <td><button type="button" class="btn btn-sm btn-danger delete-item"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                `;
                $('#invoice-items').append(newRow);
                updateItemNumbers();
                calculateTotals();

                $('#item-table-container').show();
                $('#add-new-item-btn-container').show();
                $('#initial-item-section').hide();
            }

            // Function to update item numbers after deletion
            function updateItemNumbers() {
                $('#invoice-items tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                if ($('#invoice-items tr').length === 0) {
                    $('#item-table-container').hide();
                    $('#add-new-item-btn-container').hide();
                    $('#initial-item-section').show();
                }
            }

            // Function to calculate totals
            function calculateTotals() {
                let subtotal = 0;
                $('#invoice-items tr').each(function() {
                    const qty = parseFloat($(this).find('.item-qty').val()) || 0;
                    const price = parseFloat($(this).find('.item-price').val()) || 0;
                    const amount = qty * price;
                    $(this).find('.item-amount').val(amount.toFixed(2));
                    subtotal += amount;
                });

                $('#subtotal').text(subtotal.toFixed(2));
                $('#hidden_subtotal').val(subtotal.toFixed(2)); // Update hidden subtotal

                const discountPercentage = parseFloat($('#discount-percentage').val()) || 0;
                const discountAmount = (subtotal * discountPercentage / 100);
                $('#discount-amount').text(discountAmount.toFixed(2));

                const deliveryCharge = parseFloat($('#delivery-charge').val()) || 0;

                const grandTotal = subtotal - discountAmount + deliveryCharge;
                $('#grand-total').text(grandTotal.toFixed(2));
                $('#hidden_total_amount').val(grandTotal.toFixed(2)); // Update hidden total amount

                // Only update amountPaid if payment status is 'Paid'
                if ($('#status-paid').hasClass('active')) {
                    $('#amountPaid').val(grandTotal.toFixed(2));
                }
            }

            // Handle opening the product search modal from initial section or "Add new item" button
            $(document).on('click', '#add-new-product-initial, #add-new-item, .search-input', function(e) {
                e.preventDefault();
                $('#productSearchModal').modal('show');
                $('#productSearchInput').val(''); // Clear search input on modal open
                displaySearchResults(''); // Display all products initially
            });

            // Delete item row on click
            $(document).on('click', '.delete-item', function() {
                const row = $(this).closest('tr');
                const itemId = row.data('item-id'); // Get existing item ID if it's an existing item

                if (itemId) {
                    // Add existing item ID to a hidden input for deletion on backend
                    $('#invoice_form').append(
                        `<input type="hidden" name="items_to_delete[]" value="${itemId}">`);
                }
                row.remove();
                updateItemNumbers();
                calculateTotals();
            });

            // Recalculate totals on quantity, price, discount, or delivery charge change
            $(document).on('input', '.item-qty, .item-price, #discount-percentage, #delivery-charge', function() {
                calculateTotals();
            });

            // Handle payment status buttons
            $('.payment-status .btn').on('click', function() {
                $('.payment-status .btn').removeClass('active');
                $(this).addClass('active');
                const status = $(this).data('status');
                $('#payment_status_input').val(status); // Update hidden input

                if (status === 'paid') {
                    $('#payment-details-input-fields').slideDown(); // Show with slide effect
                    calculateTotals(); // Recalculate and set amountPaid when paid is selected
                } else {
                    $('#payment-details-input-fields').slideUp(); // Hide with slide effect
                    $('#amountPaid').val('0.00'); // Reset amount paid when unpaid
                }
            });

            // Customer selection from modal
            $(document).on('click', '#customerSearchResults .customer-list-item', function(e) {
                e.preventDefault();
                const customerId = $(this).data('customer-id');
                currentCustomer = customers.find(c => c.id === customerId);
                displayCustomerDetails(currentCustomer);
                $('#customerModal').modal('hide');
            });

            // Function to display customer details
            function displayCustomerDetails(customer) {
                const customerHtml = `
                    <div class="customer-details">
                        <p class="mb-1">{{ __('Bill to') }}</p>
                        <h5>${customer.name}</h5>
                        <p>${customer.address}</p>
                        <p>${customer.email}</p>
                        <p>${customer.phone}</p>
                        <div class="edit-options mt-2">
                            <a href="#" id="edit-customer-btn" data-bs-toggle="modal" data-bs-target="#editCustomerModal">{{ __('Edit') }} ${customer.name}</a>
                            <a href="#" id="change-customer-btn" data-bs-toggle="modal" data-bs-target="#customerModal">{{ __('Choose a different customer') }}</a>
                        </div>
                        <input type="hidden" name="customer_id" value="${customer.id}">
                    </div>
                `;
                $('#customer-display').html(customerHtml);
            }

            // Populate edit customer modal when it opens
            $('#editCustomerModal').on('show.bs.modal', function() {
                if (currentCustomer) {
                    $('#editCustomerName').val(currentCustomer.name);
                    $('#editCustomerAddress').val(currentCustomer.address);
                    $('#editCustomerEmail').val(currentCustomer.email);
                    $('#editCustomerPhone').val(currentCustomer.phone);
                }
            });

            // Handle saving changes from edit customer modal
            $('#saveCustomerChanges').on('click', function() {
                if (currentCustomer) {
                    currentCustomer.name = $('#editCustomerName').val();
                    currentCustomer.address = $('#editCustomerAddress').val();
                    currentCustomer.email = $('#editCustomerEmail').val();
                    currentCustomer.phone = $('#editCustomerPhone').val();
                    displayCustomerDetails(currentCustomer); // Update displayed details
                    $('#editCustomerModal').modal('hide'); // Close the modal
                }
            });

            // Re-open modal to choose a different customer
            $(document).on('click', '#change-customer-btn', function(e) {
                e.preventDefault();
                // Modal will be opened by data-bs-target
            });

            // Set current date for invoice and payment dates
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
            const dd = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;
            $('#invoiceDate').val(formattedDate);
            $('#paymentDateInput').val(formattedDate);

            // Set payment date to 7 days from invoice date initially
            const paymentDate = new Date();
            paymentDate.setDate(today.getDate() + 7);
            const paymentYYYY = paymentDate.getFullYear();
            const paymentMM = String(paymentDate.getMonth() + 1).padStart(2, '0');
            const paymentDD = String(paymentDate.getDate()).padStart(2, '0');
            const formattedPaymentDate = `${paymentYYYY}-${paymentMM}-${paymentDD}`;
            $('#paymentDate').val(formattedPaymentDate);

            // Product search modal logic
            function displaySearchResults(searchTerm) {
                const resultsContainer = $('#productSearchResults');
                resultsContainer.empty(); // Clear previous results

                const filteredProducts = products.filter(product =>
                    product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    (product.description && product.description.toLowerCase().includes(searchTerm
                        .toLowerCase())) ||
                    (product.product_code && product.product_code.toLowerCase().includes(searchTerm
                        .toLowerCase()))
                );

                if (filteredProducts.length > 0) {
                    filteredProducts.forEach(product => {
                        const resultHtml = `
                            <a href="#" class="list-group-item list-group-item-action product-search-result"
                               data-product-id="${product.id}"
                               data-product-name="${product.name}"
                               data-product-description="${product.description ?? ''}"
                               data-product-price="${product.sell_price}">
                                <div>
                                    <div class="product-name">${product.name}</div>
                                    <div class="product-sku">SKU:${product.product_code ?? 'N/A'}</div>
                                </div>
                                <div class="product-price">BDT ${product.sell_price}</div>
                            </a>
                        `;
                        resultsContainer.append(resultHtml);
                    });
                } else {
                    resultsContainer.append('<div class="p-3 text-muted">{{ __('No products found.') }}</div>');
                }
            }

            // Live search as user types
            $('#productSearchInput').on('input', function() {
                const searchTerm = $(this).val();
                displaySearchResults(searchTerm);
                // Update the "Add "" as a new product" text

            });

            // Handle click on a product search result
            $(document).on('click', '.product-search-result', function(e) {
                e.preventDefault();
                const selectedProduct = {
                    id: $(this).data('product-id'),
                    name: $(this).data('product-name'),
                    description: $(this).data('product-description'),
                    price: parseFloat($(this).data('product-price'))
                };
                addItemToInvoiceTable(selectedProduct);
                $('#productSearchModal').modal('hide'); // Close the modal
            });

            // Handle "Add as a new product" from modal
            $('#add-new-product-modal').on('click', function(e) {
                e.preventDefault();
                const newProductName = $('#productSearchInput').val().trim();
                if (newProductName) {
                    const newProduct = {
                        name: newProductName,
                        description: '', // Can be edited later
                        price: 0.00, // Can be edited later
                        product_id: null // Indicate it's a new custom product
                    };
                    addItemToInvoiceTable(newProduct);
                    $('#productSearchModal').modal('hide');
                } else {
                    alert('{{ __('Please enter a product name to add as a new product.') }}');
                }
            });

            // Initial state for payment status
            // For create, default to unpaid. For edit, use existing status.
            @if (isset($initialPaymentStatus))
                $('#status-{{ strtolower(str_replace(' ', '', $initialPaymentStatus)) }}').click();
            @else
                $('#status-unpaid').click(); // Default for create
            @endif


            // Customer search modal logic
            function displayCustomerSearchResults(searchTerm) {
                const resultsContainer = $('#customerSearchResults');
                resultsContainer.empty(); // Clear previous results

                const filteredCustomers = customers.filter(customer =>
                    customer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    (customer.phone && customer.phone.includes(searchTerm)) ||
                    (customer.email && customer.email.toLowerCase().includes(searchTerm.toLowerCase()))
                );

                if (filteredCustomers.length > 0) {
                    filteredCustomers.forEach(customer => {
                        const contactInfo = customer.phone || customer.email || customer.address;
                        const resultHtml = `
                            <a href="#" class="customer-list-item list-group-item list-group-item-action"
                               data-customer-id="${customer.id}"
                               data-customer-name="${customer.name}"
                               data-customer-address="${customer.address}"
                               data-customer-email="${customer.email}"
                               data-customer-phone="${customer.phone}">
                                <div class="customer-avatar">${customer.avatarText}</div>
                                <div class="customer-info">
                                    <div class="customer-name-display">${customer.name}</div>
                                    <div class="customer-contact-display">${contactInfo}</div>
                                </div>
                            </a>
                        `;
                        resultsContainer.append(resultHtml);
                    });
                } else {
                    resultsContainer.append('<div class="p-3 text-muted">{{ __('No customers found.') }}</div>');
                }
            }

            // Live search as user types for customers
            $('#customerSearchInput').on('input', function() {
                const searchTerm = $(this).val();
                displayCustomerSearchResults(searchTerm);
            });

            // Populate customer modal on show
            $('#customerModal').on('show.bs.modal', function() {
                $('#customerSearchInput').val(''); // Clear search input
                displayCustomerSearchResults(''); // Show all customers initially
            });

            // Invoice Footer Accordion logic
            $('#headingInvoiceFooter button').on('click', function() {
                const $footerTextarea = $('#invoiceFooter');
                // The name attribute is added/removed based on whether the accordion is expanded.
                // This ensures the footer text is only submitted if the accordion is open.
                if ($('#collapseInvoiceFooter').hasClass('show')) {
                    $footerTextarea.removeAttr('name');
                } else {
                    $footerTextarea.attr('name', 'invoice_footer');
                }
            });

            // Initial calculation for edit mode
            @if (isset($invoice))
                // if (initialInvoiceItems.length > 0) {
                //     initialInvoiceItems.forEach(item => addItemToInvoiceTable(item, true));
                // }
                calculateTotals();
            @endif
        });
    </script>
@endpush
