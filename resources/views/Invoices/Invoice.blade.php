@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Invoice</h4>
                        <!-- Button to Open Modal -->
                        @if (Auth::user()->role === 'admin')
                            <!-- Button to Open Modal -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#InvoiceModal">
                                        Add New Invoice
                                    </button>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="searchPhone" class="form-control"
                                            placeholder="Search by phone number">
                                        <button class="btn btn-primary" id="searchBtn">Search</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="classTable" class="table table-bordered mt-4 table-striped">
                                <thead style="border-bottom: 2px solid black;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Invoice No#</th>
                                        <th>Customer Name </th>
                                        <th>Supplier Name</th>
                                        <th>Invoice Date</th>
                                        <th>Tota Amountl</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr id="classRow_{{ $invoice->id }}">
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->customer }}</td>
                                            <td>{{ $invoice->supplier->supplier_name }}</td>
                                            <td>{{ $invoice->invoice_date }}</td>
                                            <td>{{ $invoice->total_amount }}</td>
                                            <td>{{ $invoice->amount_paid }}</td>
                                            <td>{{ $invoice->balance_due }}</td>
                                            <td>{{ $invoice->payment_status }}</td>
                                            <td>

                                                <button class="btn btn-primary btn-sm editInvoice"
                                                    data-id="{{ $invoice->id }}"
                                                    data-customer_id="{{ $invoice->customer->id }}"
                                                    data-supplier_id="{{ $invoice->supplier->id }}"
                                                    data-invoice_number="{{ $invoice->invoice_number }}"
                                                    data-invoice_date="{{ $invoice->invoice_date }}"
                                                    data-total_amount="{{ $invoice->total_amount }}"
                                                    data-amount_paid="{{ $invoice->amount_paid }}"
                                                    data-balance_due="{{ $invoice->balance_due }}"
                                                    data-payment_status="{{ $invoice->payment_status }}"
                                                    data-bs-toggle="modal" data-bs-target="#editInvoiceModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>


                                                <button class="btn btn-danger btn-sm deleteInvoice"
                                                    data-id="{{ $invoice->id }}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
                                                <a href="{{ route('invoices.pdf', ['invoice_id' => $invoice->id]) }}"
                                                    class="btn btn-success">
                                                    <i class="fa fa-file-pdf"></i> Download PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>


                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3" style="gap: 10px;">
                            {{-- {{ $invoices->links('pagination::bootstrap-5') }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Save Modal -->
        <div class="modal fade" id="InvoiceModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
            <div class="modal-dialog "style="max-width: 70%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel">Add New Invoice </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="InvoiceForm">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="customer_name">Customer Name</label>
                                        <select class="form-control" id="customer_name" name="customer_id">
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->customer }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier_name">Supplier Name</label>
                                        <select class="form-control" id="supplier_name" name="supplier_id">
                                            <option value="">-- Select Supplier --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="invoice_number">Invoice Number</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                            placeholder="Invoice Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="invoice_date">Invoice Date</label>
                                        <input type="date" class="form-control" id="invoice_date" name="invoice_date">
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="total_amount"
                                            name="total_amount" placeholder="Total Amount">
                                    </div>

                                    <div class="form-group">
                                        <label for="amount_paid">Amount Paid</label>
                                        <input type="number" step="0.01" class="form-control" id="amount_paid"
                                            name="amount_paid" placeholder="Amount Paid">
                                    </div>

                                    <div class="form-group">
                                        <label for="balance_due">Balance Due</label>
                                        <input type="number" step="0.01" class="form-control" id="balance_due"
                                            name="balance_due" placeholder="Balance Due">
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select class="form-control" id="payment_status" name="payment_status">

                                            <option value="Pending"
                                                {{ old('payment_status', $invoice->payment_status ?? '') == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Paid"
                                                {{ old('payment_status', $invoice->payment_status ?? '') == 'Paid' ? 'selected' : '' }}>
                                                Paid</option>
                                        </select>
                                    </div>




                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Invoice</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <!-- Edit Invoice Modal -->
        <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="max-width: 70%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInvoiceModalLabel">Edit Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editInvoiceForm">
                            @csrf
                            <input type="hidden" id="edit_invoice_id" name="invoice_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edit_customer_name">Customer Name</label>
                                        <select class="form-control" id="edit_customer_name" name="customer_id">
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->customer }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_supplier_name">Supplier Name</label>
                                        <select class="form-control" id="edit_supplier_name" name="supplier_id">
                                            <option value="">-- Select Supplier --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_invoice_number">Invoice Number</label>
                                        <input type="text" class="form-control" id="edit_invoice_number"
                                            name="invoice_number" placeholder="Invoice Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_invoice_date">Invoice Date</label>
                                        <input type="date" class="form-control" id="edit_invoice_date"
                                            name="invoice_date">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edit_total_amount">Total Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_total_amount"
                                            name="total_amount" placeholder="Total Amount">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_amount_paid">Amount Paid</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_amount_paid"
                                            name="amount_paid" placeholder="Amount Paid">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_balance_due">Balance Due</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_balance_due"
                                            name="balance_due" placeholder="Balance Due">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_payment_status">Payment Status</label>
                                        <select class="form-control" id="edit_payment_status" name="payment_status">
                                            <option value="Pending">Pending</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update Invoice</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- Ajax --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#searchBtn').on('click', function() {
                    let phone = $('#searchPhone').val().trim();

                    $.ajax({
                        url: "{{ route('admin.searchCustomer') }}",
                        type: "GET",
                        data: {
                            phone: phone
                        },
                        success: function(response) {
                            let tableBody = $('tbody');
                            tableBody.empty(); // Clear previous results

                            if (response.length > 0) {
                                $.each(response, function(index, customer) {
                                    tableBody.append(`
                                        <tr>
                                            <td>${customer.id}</td>
                                            <td>${customer.customer}</td>
                                            <td>${customer.contact_person}</td>
                                            <td>${customer.phone}</td>
                                            <td>${customer.email}</td>
                                            <td>${customer.address}</td>
                                            <td>${customer.balance}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm editClass"
                                                    data-id="${customer.id}"
                                                    data-supplier_name="${customer.customer}"
                                                    data-contact_person="${customer.contact_person}"
                                                    data-phone="${customer.phone}"
                                                    data-email="${customer.email}"
                                                    data-balance="${customer.balance}"
                                                    data-address="${customer.address}"
                                                    data-bs-toggle="modal" data-bs-target="#editSupplierModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deleteInvoice"
                                                    data-id="${customer.id}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tableBody.append(
                                    `<tr><td colspan="8" class="text-center">No results found</td></tr>`
                                );
                            }
                        }
                    });
                });
            });
        </script>
        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul id="errorMessages" class="text-danger"></ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Function to automatically update the balance when Total Amount or Amount Paid changes
            function updateBalance() {
                var totalAmount = parseFloat(document.getElementById("total_amount").value) || 0;
                var amountPaid = parseFloat(document.getElementById("amount_paid").value) || 0;

                // Calculate the balance due
                var balanceDue = totalAmount - amountPaid;

                // Update the Balance Due field
                document.getElementById("balance_due").value = balanceDue.toFixed(2); // Show balance with 2 decimal places
            }

            // Listen for changes in Total Amount or Amount Paid fields
            document.getElementById("total_amount").addEventListener("input", updateBalance);
            document.getElementById("amount_paid").addEventListener("input", updateBalance);
        </script>

        <script>
            // Save Data
            $(document).ready(function() {
                $('#InvoiceForm').submit(function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('invoices.store') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: "top",
                                icon: "success",
                                title: "Invoice added successfully!",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            $('#InvoiceModal').modal('hide');
                            $('#InvoiceForm')[0].reset();

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "";

                            $.each(errors, function(key, value) {
                                errorMessage += value + "\n";
                            });

                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                icon: "error",
                                title: errorMessage,
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }
                    });
                });
            });

            // Handle Delete Button Click
            $(document).on('click', '.deleteInvoice', function() {
                let id = $(this).data('id'); // Get class ID

                Swal.fire({
                    title: "Are you sure?",
                    text: "This class will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('invoices.deleteInvoice', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Invoice deleted successfully.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Remove the row smoothly without refresh
                                $("#classRow_" + id).fadeOut(500, function() {
                                    $(this).remove();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to delete Invoice. Try again.",
                                    icon: "error",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                console.log(xhr.responseText); // Debugging
                            }
                        });
                    }
                });
            });

            // Handle Edit Button Click
            $(document).ready(function() {
                $('.editInvoice').on('click', function() {
                    let id = $(this).data('id');
                    let customerId = $(this).data('customer_id'); // Change to customer ID
                    let supplierId = $(this).data('supplier_id'); // Change to supplier ID
                    let invoiceNumber = $(this).data('invoice_number');
                    let invoiceDate = $(this).data('invoice_date');
                    let totalAmount = $(this).data('total_amount');
                    let amountPaid = $(this).data('amount_paid');
                    let balanceDue = $(this).data('balance_due');
                    let paymentStatus = $(this).data('payment_status');

                    // Fill the modal fields
                    $('#edit_invoice_id').val(id);
                    $('#edit_customer_name').val(customerId); // Set customer ID to dropdown
                    $('#edit_supplier_name').val(supplierId); // Set supplier ID to dropdown
                    $('#edit_invoice_number').val(invoiceNumber);
                    $('#edit_invoice_date').val(invoiceDate);
                    $('#edit_total_amount').val(totalAmount);
                    $('#edit_amount_paid').val(amountPaid);
                    $('#edit_balance_due').val(balanceDue);
                    $('#edit_payment_status').val(paymentStatus);
                });
            });



            // Handle Update Form Submission
            $('#editInvoiceForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let invoiceid = $('#edit_invoice_id').val(); // Get invoice ID
                let formData = {
                    _token: "{{ csrf_token() }}",
                    customer_id: $('#edit_customer_name').val(),
                    supplier_id: $('#edit_supplier_name').val(),
                    invoice_number: $('#edit_invoice_number').val(),
                    invoice_date: $('#edit_invoice_date').val(),
                    total_amount: $('#edit_total_amount').val(),
                    amount_paid: $('#edit_amount_paid').val(),
                    balance_due: $('#edit_balance_due').val(),
                    payment_status: $('#edit_payment_status').val()
                };

                $.ajax({
                    url: "{{ route('invoices.updateInvoice', ':id') }}".replace(':id',
                    invoiceid), // Correct route for updating invoice
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Toastify({
                                text: "Invoice updated successfully!",
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                className: "info",
                                gravity: "top",
                                position: "right",
                                duration: 3000
                            }).showToast();

                            // Update the table row dynamically
                            // $('#classRow_' + invoiceid).find('td:nth-child(1)').text(response.data.id);
                            $('#classRow_' + invoiceid).find('td:nth-child(2)').text(response.data.invoice_number);
                            $('#classRow_' + invoiceid).find('td:nth-child(3)').text(response.data.customer_id);
                            $('#classRow_' + invoiceid).find('td:nth-child(4)').text(response.data.supplier_id);
                            $('#classRow_' + invoiceid).find('td:nth-child(5)').text(response.data.invoice_date);
                            $('#classRow_' + invoiceid).find('td:nth-child(6)').text(response.data.total_amount);
                            $('#classRow_' + invoiceid).find('td:nth-child(7)').text(response.data.amount_paid);
                            $('#classRow_' + invoiceid).find('td:nth-child(8)').text(response.data.balance_due);
                            $('#classRow_' + invoiceid).find('td:nth-child(9)').text(response.data.payment_status);


                              // Hide modal properly
                            $('#editInvoiceModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');

                            // Refresh page after 1.5 seconds
                            setTimeout(() => location.reload(), 1500);

                            // Optionally, you can refresh the table row to reflect the new data instantly.
                        } else {
                            Toastify({
                                text: "Error updating invoice.",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                className: "error",
                                gravity: "top",
                                position: "right",
                                duration: 5000
                            }).showToast();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Debugging
                        Toastify({
                            text: "Something went wrong. Please try again.",
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            className: "error",
                            gravity: "top",
                            position: "right",
                            duration: 5000
                        }).showToast();
                    }
                });
            });
        </script>
    @endsection
