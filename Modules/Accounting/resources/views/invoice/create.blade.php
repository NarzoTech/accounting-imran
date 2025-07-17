@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Invoice') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.invoice.store') }}" method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        <div class="container invoice-container">

            <div class="row">
                <!-- Left Column: Customer and Invoice Details -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div id="customer-display" class="customer-info-box">
                                <i class="fas fa-user-plus add-customer-btn" data-bs-toggle="modal"
                                    data-bs-target="#customerModal"></i>
                                <div class="add-customer-btn mt-2" data-bs-toggle="modal" data-bs-target="#customerModal">
                                    Add a
                                    customer</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Invoice Number, Dates -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <label for="invoiceNumber" class="col-sm-5 col-form-label">Invoice Number</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="invoiceNumber" name="invoice_number">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="poSoNumber" class="col-sm-5 col-form-label">P.O./S.O. number</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="poSoNumber"
                                        placeholder="Enter P.O./S.O. Number" name="po_so_number">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="invoiceDate" class="col-sm-5 col-form-label">Invoice Date</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="invoiceDate" value="{{ date('Y-m-d') }}"
                                        name="invoice_date">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="paymentDate" class="col-sm-5 col-form-label">Payment Date</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}"
                                        name="payment_date">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="paymentDate" class="col-sm-5 col-form-label">Container</label>
                                <div class="col-sm-7">
                                    <select name="container_id" id="container_id" class="form-control select2">
                                        <option value="">Select Container</option>
                                        @foreach ($containers as $container)
                                            <option value="{{ $container->id }}">{{ $container->container_number }}
                                                ({{ $container->container_type }})
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
                    Item Details
                </div>
                <div class="card-body">
                    <!-- Initial Item Display with Search Product -->
                    <div id="initial-item-section">
                        <input type="text" class="form-control search-input" placeholder="Search Products"
                            data-bs-toggle="modal" data-bs-target="#productSearchModal" readonly>
                        <div class="text-center mt-3">
                            <a href="#" class="add-new-product" id="add-new-product-initial" data-bs-toggle="modal"
                                data-bs-target="#productSearchModal"><i class="fas fa-plus-circle me-2"></i>Add "" as a new
                                product</a>
                        </div>
                    </div>

                    <!-- Item Table (initially hidden or empty, shown when items are added) -->
                    <div class="table-responsive" id="item-table-container" style="display: none;">
                        <table class="table table-borderless item-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">Item</th>
                                    <th style="width: 30%;">Description</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 10%;">Unit</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 5%;"></th> <!-- For delete button -->
                                </tr>
                            </thead>
                            <tbody id="invoice-items">
                                <!-- Item rows will be added here by jQuery -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-start mt-3" id="add-new-item-btn-container" style="display: none;">
                        <a href="#" class="add-item-btn" id="add-new-item" data-bs-toggle="modal"
                            data-bs-target="#productSearchModal"><i class="fas fa-plus-circle me-2"></i>Add new item</a>
                    </div>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6">
                            <table class="table table-borderless summary-table">
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-end" id="subtotal">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Discount <input type="number"
                                                class="form-control d-inline-block w-25 ms-2 me-1"
                                                id="discount-percentage" value="0" name="discount_percentage">%</td>
                                        <td class="text-end" id="discount-amount">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Add Delivery Charge</td>
                                        <td class="text-end">
                                            <input type="number" class="form-control d-inline-block w-50"
                                                id="delivery-charge" value="0" step="0.01"
                                                name="delivery_charge">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="total-amount">Grand Total</td>
                                        <td class="text-end total-amount" id="grand-total" name="grand_total">0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="notesTerms" class="form-label">Notes / Terms</label>
                        <textarea class="form-control" id="notesTerms" rows="3"
                            placeholder="Enter notes or terms of service that you are visible to your customer" name="notes_terms"></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Details Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3">Payment Status:</div>
                        <div class="col-md-9 payment-status">
                            <div class="btn-group" role="group" aria-label="Payment Status">
                                <button type="button" class="btn active" data-status="unpaid"
                                    id="status-unpaid">Unpaid</button>
                                <button type="button" class="btn" data-status="paid" id="status-paid">Paid</button>
                            </div>
                        </div>
                    </div>
                    <!-- Payment related input fields, initially hidden -->
                    <div id="payment-details-input-fields" class="payment-details-section">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="paymentDateInput" class="form-label">Payment Date</label>
                                <input type="date" class="form-control" id="paymentDateInput"
                                    value="{{ date('Y-m-d') }}" name="payment_date">
                            </div>
                            <div class="col-md-4">
                                <label for="paymentAccount" class="form-label">Payment Account</label>
                                <select class="form-select" id="paymentAccount" name="payment_account">
                                    <option selected value="Cash">Cash on Hand</option>
                                    <option value="Bank">Bank Account</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="amountPaid" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amountPaid" value="0"
                                    step="0.01" name="amount_paid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Footer Section (now an Accordion) -->
            <div class="accordion mb-4" id="invoiceFooterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingInvoiceFooter">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseInvoiceFooter" aria-expanded="false"
                            aria-controls="collapseInvoiceFooter">
                            Invoice Footer
                        </button>
                    </h2>
                    <div id="collapseInvoiceFooter" class="accordion-collapse collapse"
                        aria-labelledby="headingInvoiceFooter" data-bs-parent="#invoiceFooterAccordion">
                        <div class="accordion-body">
                            <label for="invoiceFooter" class="form-label visually-hidden">Invoice Footer Text</label>
                            <textarea class="form-control" id="invoiceFooter" rows="2">Just wanted to say thank you for your purchase. We're so lucky to have customers like you!</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-save-invoice">Save Invoice</button>
            </div>
        </div>
    </form>
    <!-- Customer Selection Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Select Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <h5 class="modal-title" id="productSearchModalLabel">Search Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="productSearchInput"
                        placeholder="Search Products">
                    <div id="productSearchResults" class="list-group">
                        <!-- Search results will be populated here -->
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="add-new-product" id="add-new-product-modal"><i
                                class="fas fa-plus-circle me-2"></i>Add "" as a new product</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm">
                        <div class="mb-3">
                            <label for="editCustomerName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="editCustomerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCustomerAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="editCustomerAddress">
                        </div>
                        <div class="mb-3">
                            <label for="editCustomerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editCustomerEmail">
                        </div>
                        <div class="mb-3">
                            <label for="editCustomerPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="editCustomerPhone">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCustomerChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend\css\invoice-create.css') }}">
@endpush


@push('js')
    <script>
        $(document).ready(function() {
            let itemCounter = 0;
            let currentCustomer = null; // To store the currently selected customer's data

            // Mock product data
            const products = @json($products);
            const $customers = @json($customers);
            console.log($customers);

            function mapCustomerData(backendCustomer) {
                const fullName = backendCustomer.name ||
                    `${backendCustomer.first_name || ''} ${backendCustomer.last_name || ''}`.trim();
                const addressParts = [];
                if (backendCustomer.address) addressParts.push(backendCustomer.address);
                if (backendCustomer.city) addressParts.push(backendCustomer.city);
                if (backendCustomer.state) addressParts.push(backendCustomer.state);
                if (backendCustomer.country) addressParts.push(backendCustomer.country);
                const fullAddress = addressParts.join(', ');

                // Generate avatar text (e.g., first two letters of name)
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

            const customers = [
                ...$customers.map(mapCustomerData)
            ];

            function avatarTextGenerator(name) {
                if (!name) return '';

                const words = name.trim().split(' ');

                if (words.length === 1) {
                    return words[0].charAt(0).toUpperCase();
                }

                return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
            }
            // Function to add a selected item to the invoice table
            function addItemToInvoiceTable(item) {
                itemCounter++;
                const newRow = `
                    <tr data-item-id="${itemCounter}">
                        <td>${itemCounter}</td>
                        <td>${item.name}
                            <input type="hidden" name="items[${itemCounter}][product_id]" value="${item.id}">
                            </td>
                        <td><input type="text" class="form-control item-description" value="${item.description ?? ''}" placeholder="Description" name="items[${itemCounter}][description]"></td>
                        <td><input type="number" class="form-control item-qty" value="1" min="1" name="items[${itemCounter}][quantity]"></td>
                        <td><input type="string" class="form-control item-unit" value="pcs" name="items[${itemCounter}][unit]"></td>
                        <td><input type="number" class="form-control item-price" value="${item.sell_price}" min="0" step="0.01" name="items[${itemCounter}][price]"></td>
                        <td><input type="text" class="form-control item-amount" value="${item.sell_price}" readonly name="items[${itemCounter}][amount]"></td>
                        <td><button type="button" class="btn btn-sm btn-danger delete-item"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                `;
                $('#invoice-items').append(newRow);
                updateItemNumbers();
                calculateTotals();

                // Show the item table and "Add new item" button
                $('#item-table-container').show();
                $('#add-new-item-btn-container').show();
                // Hide the initial item section
                $('#initial-item-section').hide();
            }

            // Function to update item numbers after deletion
            function updateItemNumbers() {
                $('#invoice-items tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                // If no items are left, revert to initial state
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

                // For simplicity, VAT is 0 for now, but you can add logic here
                const totalVat = 0; // Example: subtotal * 0.10;
                $('#total-vat').text(totalVat.toFixed(2));

                const discountPercentage = parseFloat($('#discount-percentage').val()) || 0;
                const discountAmount = (subtotal * discountPercentage / 100);
                $('#discount-amount').text(discountAmount.toFixed(2));

                const deliveryCharge = parseFloat($('#delivery-charge').val()) || 0;

                const grandTotal = subtotal + totalVat - discountAmount + deliveryCharge;
                $('#grand-total').text(grandTotal.toFixed(2));
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
                $(this).closest('tr').remove();
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
                        <p class="mb-1">Bill to</p>
                        <h5>${customer.name}</h5>
                        <p>${customer.address}</p>
                        <p>${customer.email}</p>
                        <p>${customer.phone}</p>
                        <div class="edit-options mt-2">
                            <a href="#" id="edit-customer-btn" data-bs-toggle="modal" data-bs-target="#editCustomerModal">Edit ${customer.name}</a>
                            <a href="#" id="change-customer-btn" data-bs-toggle="modal" data-bs-target="#customerModal">Choose a different customer</a>
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
                    product.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    product.product_code.toLowerCase().includes(searchTerm.toLowerCase())
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
                                    <div class="product-sku">SKU:${product.product_code}</div>
                                </div>
                                <div class="product-price">BDT ${product.sell_price}</div>
                            </a>
                        `;
                        resultsContainer.append(resultHtml);
                    });
                } else {
                    resultsContainer.append('<div class="p-3 text-muted">No products found.</div>');
                }
            }

            // Live search as user types
            $('#productSearchInput').on('input', function() {
                const searchTerm = $(this).val();
                displaySearchResults(searchTerm);
                // Update the "Add "" as a new product" text
                $('#add-new-product-modal').html(
                    `<i class="fas fa-plus-circle me-2"></i>Add "${searchTerm}" as a new product`);
            });

            // Handle click on a product search result
            $(document).on('click', '.product-search-result', function(e) {
                e.preventDefault();
                const selectedProduct = {
                    id: $(this).data('product-id'),
                    name: $(this).data('product-name'),
                    description: $(this).data('product-description'),
                    sell_price: parseFloat($(this).data('product-price'))
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
                        price: 0.00 // Can be edited later
                    };
                    addItemToInvoiceTable(newProduct);
                    $('#productSearchModal').modal('hide');
                } else {
                    alert('Please enter a product name to add as a new product.');
                }
            });

            // Initial state for payment status
            $('#status-unpaid').click(); // Set unpaid as active and hide fields initially
            // Customer search modal logic
            function displayCustomerSearchResults(searchTerm) {
                const resultsContainer = $('#customerSearchResults');
                resultsContainer.empty(); // Clear previous results

                const filteredCustomers = customers.filter(customer =>
                    customer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    customer.phone.includes(searchTerm) ||
                    customer.email.toLowerCase().includes(searchTerm.toLowerCase())
                );

                if (filteredCustomers.length > 0) {
                    filteredCustomers.forEach(customer => {
                        const contactInfo = customer.phone || customer.email || customer.address;
                        const resultHtml = `
                            <a href="#" class="customer-list-item"
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
                    resultsContainer.append('<div class="p-3 text-muted">No customers found.</div>');
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

            // Handle adding a new customer from the customer modal
            $('#addNewCustomerFromModal').on('click', function() {
                // In a real app, this would open a new modal/form to add a customer
                alert('Add New Customer functionality would go here.');
                // For now, let's just add a dummy customer for demonstration
                const newCustomerName = $('#customerSearchInput').val().trim();
                if (newCustomerName) {
                    const newCustomer = {
                        id: customers.length, // Simple ID generation
                        name: newCustomerName,
                        address: "New Address",
                        email: "new@example.com",
                        phone: "111222333",
                        avatarText: newCustomerName.substring(0, 2).toUpperCase()
                    };
                    customers.push(newCustomer);
                    currentCustomer = newCustomer;
                    displayCustomerDetails(currentCustomer);
                    $('#customerModal').modal('hide');
                } else {
                    alert('Please enter a name for the new customer.');
                }
            });

            $('#headingInvoiceFooter button').on('click', function() {
                const $footer = $('#invoiceFooter');
                if ($footer.attr('name') === 'invoice_footer') {
                    $footer.removeAttr('name');
                } else {
                    $footer.attr('name', 'invoice_footer');
                }
            });
        });
    </script>
@endpush
