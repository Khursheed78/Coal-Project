@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <h2 class="mb-4">Create Purchase Record</h2>
                            <div class="card">
                                <div class="card-body">
                                    @if (Auth::user()->role === 'admin')
                                        <!-- Button to Open Modal -->
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#DriverModal">
                                                    New Purchase Record
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
                                    <div class="row mt-4">
                                        <div class="col-md-4 text">
                                            <h5 class="text-danger">Total Trips: <span
                                                    id="totalTrips">{{ $totaltrips }}</span></h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-primary">Total Unique Suppliers: <span
                                                    id="totalUniqueSuppliers">{{ $supplierCount }}</span></h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-success">Total Unique Drivers: <span
                                                    id="totalUniqueDrivers">{{ $driverCount }}</span></h5>
                                        </div>
                                    </div>

                                    <div class="table-responsive">

                                        <table id="classTable" class="table table-bordered mt-4 table-striped">
                                            <thead style="border-bottom: 2px solid black;">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Supplier Name</th>
                                                    <th>Contact Person</th>
                                                    <th>Phone</th>
                                                    <th>Supplier Balance</th>
                                                    <th>Payment Status</th>
                                                    <th>Supplier Count</th>
                                                    <th>Driver Name</th>
                                                    <th>Phone</th>
                                                    <th>Driver Balance</th>
                                                    <th>Payment Status</th>
                                                    <th>Trips Per Driver</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Quantity</th>
                                                    <th>Price per Ton</th>
                                                    <th>Transportation Cost</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchases as $purchase)
                                                    <tr id="classRow_{{ $purchase->id }}">
                                                        <td>{{ $purchase->id }}</td>
                                                        <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
                                                        <td>{{ $purchase->supplier->contact_person ?? 'N/A' }}</td>
                                                        <td>{{ $purchase->supplier->phone ?? 'N/A' }}</td>
                                                        <td class="text-primary">
                                                            {{ number_format($purchase->supplier_balance, 2) }}</td>
                                                        <!-- Supplier Balance -->

                                                        <!-- Supplier Balance Payment -->
                                                        <td>
                                                            @if ($purchase->supplier_balance == 0)
                                                                <span class="badge bg-danger"><i
                                                                        class="fas fa-check-circle"></i> Paid</span>
                                                            @else
                                                                <button type="button" class="btn btn-success payment-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#PaymentModal"
                                                                    data-id="{{ $purchase->id }}"
                                                                    data-name="{{ $purchase->supplier->supplier_name }}"
                                                                    data-balance="{{ $purchase->supplier_balance }}"
                                                                    data-field="supplier_balance">
                                                                    Pay Now
                                                                </button>
                                                            @endif
                                                        </td>

                                                        <td class="text-danger">
                                                            {{ $supplierTrips[$purchase->supplier_id]['count'] ?? 0 }}</td>
                                                        <!-- No. of Trips per Supplier -->

                                                        <td>{{ $purchase->driver->name ?? 'N/A' }}</td>
                                                        <td>{{ $purchase->driver->phone ?? 'N/A' }}</td>
                                                        <td class="text-success">
                                                            {{ number_format($purchase->driver_balance, 2) }}</td>

                                                        <!-- Driver Payment -->
                                                        <td>
                                                            @if ($purchase->driver_balance == 0)
                                                                <span class="badge bg-danger"><i
                                                                        class="fas fa-check-circle"></i> Paid</span>
                                                            @else
                                                                <button type="button" class="btn btn-success payment-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#PaymentModal"
                                                                    data-id="{{ $purchase->id }}"
                                                                    data-name="{{ $purchase->driver->name }}"
                                                                    data-balance="{{ $purchase->driver_balance }}"
                                                                    data-field="driver_balance">
                                                                    Pay Now
                                                                </button>
                                                            @endif
                                                        </td>

                                                        <td class="text-danger">
                                                            {{ $driverTrips[$purchase->driver_id]['count'] ?? 0 }}</td>
                                                        <!-- No. of Trips per Driver -->

                                                        <td>{{ $purchase->from }}</td>
                                                        <td>{{ $purchase->to }}</td>
                                                        <td>{{ $purchase->quantity }}</td>
                                                        <td>{{ number_format($purchase->price_per_ton, 2) }}</td>
                                                        <td class="text-warning">
                                                            {{ number_format($purchase->transportation_cost, 2) }}</td>
                                                        <!-- Transportation Cost -->

                                                        <!-- ✅ Updated Total Price Calculation (Including Unpaid Balances) -->
                                                        <td class="fw-bold text-success">
                                                            {{ number_format($purchase->quantity * $purchase->price_per_ton + $purchase->transportation_cost + $purchase->supplier_balance + $purchase->driver_balance, 2) }}
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-primary btn-sm editInvoice"
                                                                data-id="{{ $purchase->id }}"
                                                                data-supplier="{{ $purchase->supplier_id }}"
                                                                data-driver="{{ $purchase->driver_id }}"
                                                                data-quantity="{{ $purchase->quantity }}"
                                                                data-price="{{ $purchase->price_per_ton }}"
                                                                data-from="{{ $purchase->from }}"
                                                                data-to="{{ $purchase->to }}"
                                                                data-transportation-cost="{{ $purchase->transportation_cost }}"
                                                                data-supplier-balance="{{ $purchase->supplier_balance }}"
                                                                data-driver-balance="{{ $purchase->driver_balance }}"
                                                                data-bs-toggle="modal" data-bs-target="#editInvoiceModal">
                                                                <i class="fa fa-edit text-white"></i>
                                                            </button>


                                                            <button class="btn btn-danger btn-sm deletePurchase"
                                                                data-id="{{ $purchase->id }}">
                                                                <i class="fa fa-trash text-white"></i>
                                                            </button>



                                                            <a href="{{ route('purchase.pdf', ['purchase_id' => $purchase->id]) }}"
                                                                class="btn btn-warning btn-sm">
                                                                <i class="fa fa-file-pdf"></i> Generate PDF
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>


                                        </table>

                                    </div>
                                    <div class="d-flex justify-content-center mt-3">

                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Payment Modal -->

        <div class="modal fade" id="PaymentModal" tabindex="-1" aria-labelledby="PaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="PaymentModalLabel">Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> <span id="modalPaymentName"></span></p>

                        <!-- Editable Balance Input -->
                        <div class="form-group">
                            <label for="paymentAmount">Balance to Pay:</label>
                            <input type="number" class="form-control" id="paymentAmount" min="0">
                        </div>

                        <!-- Hidden Inputs to Store Data -->
                        <input type="hidden" id="paymentId">
                        <input type="hidden" id="paymentField"> <!-- Store supplier_balance or driver_balance -->

                        <!-- Submit Payment Button -->
                        <button type="button" class="btn btn-primary mt-2" id="submitPayment">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Modal -->
        <div class="modal fade" id="DriverModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 70%;" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel">Add New Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="purchaseInvoiceForm">
                            @csrf
                            <!-- Supplier Dropdown -->
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Select Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="form-control">
                                    <option value="">Choose Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-name="{{ $supplier->supplier_name }}"
                                            data-phone="{{ $supplier->phone }}" data-balance="{{ $supplier->balance }}">
                                            {{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Supplier Details -->
                            <div id="supplierDetails" class="card my-3 shadow-sm" style="display: none;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-user"></i> Supplier Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Name:</p>
                                            <p id="supplierName" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Phone:</p>
                                            <p id="supplierPhone" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="supplierBalance" class="fw-bold mb-1">Balance:</label>
                                            <input type="text" id="supplierBalance" name="supplier_balance"
                                                class="form-control">
                                            @error('supplier_balance')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Dropdown -->
                            <div class="mb-3">
                                <label for="driver_id" class="form-label">Select Driver</label>
                                <select id="driver_id" name="driver_id" class="form-control">
                                    <option value="">Choose Driver</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                            data-phone="{{ $driver->phone }}" data-balance="{{ $driver->balance }}">
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Driver Details -->
                            <div id="driverDetails" class="card my-3 shadow-sm" style="display: none;">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-truck"></i> Driver Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Name:</p>
                                            <p id="driverName" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Phone:</p>
                                            <p id="driverPhone" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="driverBalance" class="fw-bold mb-1">Balance:</label>
                                            <input type="text" id="driverBalance" name="driver_balance"
                                                class="form-control">
                                            @error('driver_balance')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Coal Quantity & Price -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="quantity_tons" class="form-label">Quantity (Tons)</label>
                                    <input type="number" id="quantity_tons" name="quantity" class="form-control"
                                        min="1">
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="price_per_ton" class="form-label">Price per Ton</label>
                                    <input type="number" id="price_per_ton" name="price_per_ton" class="form-control"
                                        min="1">
                                    @error('price_per_ton')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total_price" class="form-label">Total Price</label>
                                    <input type="text" id="total_price" name="total_price" class="form-control"
                                        readonly>
                                    @error('total_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="to" class="form-label">To (Place)</label>
                                    <input type="text" id="to" name="to" class="form-control">
                                    @error('to')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="from" class="form-label">From (Place)</label>
                                    <input type="text" id="from" name="from" class="form-control">
                                    @error('from')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" id="date" name="date" class="form-control">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="transportation_cost" class="form-label">Transportation Cost</label>
                                    <input type="text" id="transportation_cost" name="transportation_cost"
                                        class="form-control">
                                    @error('transportation_cost')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Invoice Modal -->
        <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInvoiceModalLabel">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateInvoiceForm">
                            @csrf
                            <input type="hidden" id="edit_invoice_id" name="id">

                            <div class="mb-3">
                                <label for="edit_supplier_id" class="form-label">Supplier</label>
                                <select class="form-control" id="edit_supplier_id" name="supplier_id">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_driver_id" class="form-label">Driver</label>
                                <select class="form-control" id="edit_driver_id" name="driver_id">
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_price_per_ton" class="form-label">Price Per Ton</label>
                                <input type="number" class="form-control" id="edit_price_per_ton" name="price_per_ton"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_transportation_cost" class="form-label">Transportation Cost</label>
                                <input type="number" class="form-control" id="edit_transportation_cost"
                                    name="transportation_cost">
                            </div>

                            <div class="mb-3">
                                <label for="edit_from" class="form-label">From</label>
                                <input type="text" class="form-control" id="edit_from" name="from" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_to" class="form-label">To</label>
                                <input type="text" class="form-control" id="edit_to" name="to" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_supplier_balance" class="form-label">Supplier Balance</label>
                                <input type="number" class="form-control" id="edit_supplier_balance"
                                    name="supplier_balance">
                            </div>

                            <div class="mb-3">
                                <label for="edit_driver_balance" class="form-label">Driver Balance</label>
                                <input type="number" class="form-control" id="edit_driver_balance"
                                    name="driver_balance">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function()
          /* =======================
       ✅ DELETE PURCHASE RECORD
       ======================= */
        {
            $(document).on("click", ".deletePurchase", function() {
                let purchaseId = $(this).data("id");
                let supplierId = $(this).closest("tr").find(".supplier-id").text();
                let driverId = $(this).closest("tr").find(".driver-id").text();

                Swal.fire({
                    title: "Are you sure?",
                    text: "This purchase will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase.delete') }}", // Laravel delete route
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: purchaseId
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "The purchase has been removed.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // ✅ Remove row dynamically
                                $("#classRow_" + purchaseId).fadeOut(500, function() {
                                    $(this).remove();
                                });

                                // ✅ Update Total Trips count manually
                                let totalTripsElement = $("#totalTrips");
                                let currentTotalTrips = parseInt(totalTripsElement
                                .text()) || 0;
                                if (currentTotalTrips > 0) {
                                    totalTripsElement.text(currentTotalTrips - 1);
                                }

                                // ✅ Manually track and update Supplier & Driver counts
                                updateSupplierDriverCount(supplierId, driverId);
                            },
                            error: function(xhr) {
                                console.log("AJAX Error:", xhr.responseText);
                                Swal.fire("Error!",
                                    "Something went wrong. Please try again.",
                                    "error");
                            }
                        });
                    }
                });
            });

            // ✅ Function to update supplier and driver counts dynamically without `count()`
            function updateSupplierDriverCount(supplierId, driverId) {
                let supplierCountElement = $("#totalUniqueSuppliers");
                let driverCountElement = $("#totalUniqueDrivers");

                // Check if there are other rows with the same supplier_id
                let remainingSupplierRows = $(".supplier-id").filter(function() {
                    return $(this).text() == supplierId;
                }).length;

                if (remainingSupplierRows === 0) {
                    let currentSupplierCount = parseInt(supplierCountElement.text()) || 0;
                    if (currentSupplierCount > 0) {
                        supplierCountElement.text(currentSupplierCount - 1);
                    }
                }

                // Check if there are other rows with the same driver_id
                let remainingDriverRows = $(".driver-id").filter(function() {
                    return $(this).text() == driverId;
                }).length;

                if (remainingDriverRows === 0) {
                    let currentDriverCount = parseInt(driverCountElement.text()) || 0;
                    if (currentDriverCount > 0) {
                        driverCountElement.text(currentDriverCount - 1);
                    }
                }
            }
        });


  /* =======================
       ✅ HANDLE PAYMENT MODAL
       ======================= */
        $(document).ready(function() {
            // Handle Payment Button Click
            $(".payment-btn").click(function() {
                let id = $(this).data("id");
                let name = $(this).data("name");
                let balance = $(this).data("balance");
                let field = $(this).data("field"); // supplier_balance or driver_balance

                // Populate modal fields
                $("#modalPaymentName").text(name);
                $("#paymentAmount").val(balance);
                $("#paymentId").val(id);
                $("#paymentField").val(field);

                // Update modal title dynamically
                $("#PaymentModalLabel").text(field === "supplier_balance" ? "Supplier Payment" :
                    "Driver Payment");
            });
  /* ============================
       ✅ HANDLE PAYMENT SUBMISSION
       ============================ */
            $(document).ready(function() {
                $(".payment-btn").click(function() {
                    let name = $(this).attr("data-name"); // Get Supplier/Driver Name
                    let balance = $(this).attr("data-balance"); // Get Current Balance
                    let id = $(this).attr("data-id"); // Get Purchase ID
                    let field = $(this).attr(
                    "data-field"); // Get supplier_balance or driver_balance

                    // Set values in the modal
                    $("#modalPaymentName").text(name);
                    $("#paymentAmount").val(balance);
                    $("#paymentId").val(id);
                    $("#paymentField").val(field);

                    // Update modal title dynamically
                    $("#PaymentModalLabel").text(field === "supplier_balance" ? "Supplier Payment" :
                        "Driver Payment");
                });

                $(document).ready(function() {
                    $(".payment-btn").click(function() {
                        let name = $(this).data("name"); // Get Name
                        let balance = $(this).data("balance"); // Get Balance
                        let id = $(this).data("id"); // Get Purchase ID
                        let field = $(this).data(
                        "field"); // Get supplier_balance or driver_balance

                        $("#modalPaymentName").text(name);
                        $("#paymentAmount").val(balance);
                        $("#paymentId").val(id);
                        $("#paymentField").val(field);

                        // Debugging logs
                        console.log("Modal Opened - ID:", id, "Field:", field, "Balance:",
                            balance);
                    });

                    // Handle Payment Submission
                    $("#submitPayment").click(function() {
                        let paymentId = $("#paymentId").val();
                        let paymentField = $("#paymentField").val();
                        let paymentAmount = parseFloat($("#paymentAmount").val());

                        if (!paymentId || isNaN(paymentAmount) || paymentAmount <= 0) {
                            Swal.fire("Error!", "Enter a valid payment amount.", "error");
                            return;
                        }

                        let currentBalance = parseFloat($(
                            `button[data-id="${paymentId}"][data-field="${paymentField}"]`
                            ).attr("data-balance"));
                        if (paymentAmount > currentBalance) {
                            Swal.fire("Error!", "Amount exceeds balance!", "error");
                            return;
                        }

                        // Debugging log before sending request
                        console.log("Sending Payment ID:", paymentId, "Field:",
                            paymentField, "Amount:", paymentAmount);


                        $.ajax({
                            url: "{{ route('payment.process') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: paymentId,
                                field: paymentField,
                                amount: paymentAmount
                            },
                            success: function(response) {
                                console.log("Server Response:", response);

                                if (response.success) {
                                    Swal.fire({
                                        toast: true,
                                        position: "top",
                                        icon: "success",
                                        title: "Payment processed successfully!",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    // Close modal and remove backdrop
                                    $("#PaymentModal").modal("hide");
                                    $(".modal-backdrop").remove();
                                    $("body").removeClass("modal-open");

                                    // Update balance dynamically in the table
                                    let balanceElement = $(
                                        `button[data-id="${paymentId}"][data-field="${paymentField}"]`
                                        ).closest("td").prev();
                                    balanceElement.text(response.new_balance
                                        .toFixed(2));

                                    // Update button attributes
                                    $(`button[data-id="${paymentId}"][data-field="${paymentField}"]`)
                                        .attr("data-balance", response
                                            .new_balance);

                                    // If balance is 0, mark as paid
                                    if (response.new_balance <= 0) {
                                        balanceElement.html(
                                            '<span class="badge bg-danger"><i class="fas fa-check-circle"></i> Paid</span>'
                                            );
                                        $(`button[data-id="${paymentId}"][data-field="${paymentField}"]`)
                                            .remove();
                                    }

                                    // Reload after 1 second
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    Swal.fire({
                                        toast: true,
                                        position: "top",
                                        icon: "error",
                                        title: "Unexpected response from server!",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.log("AJAX Error:", xhr.responseText);
                                Swal.fire({
                                    toast: true,
                                    position: "top",
                                    icon: "error",
                                    title: "Something went wrong! Check console logs.",
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        });
                    });
                });

            });


            // Auto-calculate total price
            $("#quantity_tons, #price_per_ton").on("input", function() {
                let quantity = parseFloat($("#quantity_tons").val()) || 0;
                let price = parseFloat($("#price_per_ton").val()) || 0;
                let totalPrice = quantity * price;
                $("#total_price").val(totalPrice.toFixed(2));
            });

            // Handle Purchase Form Submission
            $("#purchaseInvoiceForm").submit(function(e) {
                e.preventDefault();

                let quantity = parseFloat($("#quantity_tons").val()) || 0;
                let price = parseFloat($("#price_per_ton").val()) || 0;
                let transportationCost = parseFloat($("#transportation_cost").val()) || 0;
                let totalPrice = (quantity * price) + transportationCost;
                let purchaseDate = $("#date").val();

                $.ajax({
                    url: "{{ route('purchase.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        supplier_id: $("#supplier_id").val(),
                        driver_id: $("#driver_id").val(),
                        quantity: quantity,
                        price_per_ton: price,
                        total_price: totalPrice,
                        transportation_cost: transportationCost,
                        supplier_balance: $("#supplierBalance").val(),
                        driver_balance: $("#driverBalance").val(),
                        to_place: $("#to").val(),
                        from_place: $("#from").val(),
                        date: purchaseDate,
                    },
                    success: function(response) {
                        Swal.fire("Success!", "Invoice Submitted Successfully!", "success");
                        $("#DriverModal").modal("hide");
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let field = $("#" + key);
                                field.addClass("is-invalid");
                                field.after('<span class="text-danger">' + value[0] +
                                    "</span>");
                            });
                        } else {
                            Swal.fire("Error!", "Something went wrong. Please try again.",
                                "error");
                        }
                    }
                });
            });
   /* ==============================
       ✅ HANDLE PDF GENERATE
       ============================== */

            $('#generatePdfBtn').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('purchase.pdf') }}",
                    method: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        supplier_id: $("#supplier_id").val(),
                        supplier_name: $("#supplierName").text(),
                        supplier_phone: $("#supplierPhone").text(),
                        supplier_balance: $("#supplierBalance").text(),
                        driver_id: $("#driver_id").val(),
                        driver_name: $("#driverName").text(),
                        driver_trips: $("#driverTrips").text(),
                        driver_balance: $("#driverBalance").text(),
                        driver_quantity: $("#driverQuantity").text(),
                        driver_price: $("#driverPrice").text(),
                        quantity: $("#quantity_tons").val(),
                        price_per_ton: $("#price_per_ton").val(),
                        total_price: $("#total_price").val(),
                        total_price: $("#date").val(),
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        let blob = new Blob([data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "purchase_invoice.pdf";
                        link.click();
                    },
                    error: function(xhr) {
                        console.error("Error generating PDF:", xhr.responseText);
                    }
                });
            });

            // Handle Supplier Selection
            $("#supplier_id").change(function() {
                let selected = $(this).find("option:selected");
                $("#supplierName").text(selected.data("name"));
                $("#supplierPhone").text(selected.data("phone"));
                $("#supplierBalance").val(selected.data("balance"));
                $("#supplierDetails").toggle(!!selected.val());
            });

            // Handle Driver Selection
            $("#driver_id").change(function() {
                let selected = $(this).find("option:selected");
                let attributes = ["name", "phone", "trips", "balance", "quantity", "price"];
                attributes.forEach(attr => {
                    $("#driver" + attr.charAt(0).toUpperCase() + attr.slice(1)).text(selected.data(
                        attr) || "N/A");
                });
                $("#driverDetails").toggle(!!selected.val());
            });
        });
        $(document).ready(function () {
    /* =================================
       ✅ SHOW SUPPLIER DATA IN MODAL FOR EDITING
       ================================= */
    $(document).on("click", ".editInvoice", function () {
        let id = $(this).data("id");
        let supplier = $(this).data("supplier");
        let driver = $(this).data("driver");
        let quantity = $(this).data("quantity");
        let price = $(this).data("price");
        let from = $(this).data("from");
        let to = $(this).data("to");
        let transportation_cost = $(this).data("transportation-cost");
        let supplier_balance = $(this).data("supplier-balance");
        let driver_balance = $(this).data("driver-balance");

        // Populate Modal Fields
        $("#edit_invoice_id").val(id);
        $("#edit_supplier_id").val(supplier);
        $("#edit_driver_id").val(driver);
        $("#edit_quantity").val(quantity);
        $("#edit_price_per_ton").val(price);
        $("#edit_from").val(from);
        $("#edit_to").val(to);
        $("#edit_transportation_cost").val(transportation_cost);
        $("#edit_supplier_balance").val(supplier_balance);
        $("#edit_driver_balance").val(driver_balance);
    });

      /* ==============================
       ✅ HANDLE PURCHASE UPDATE FORM
       ============================== */
    $("#updateInvoiceForm").submit(function (e) {
        e.preventDefault();

        let invoiceId = $("#edit_invoice_id").val();
        let formData = {
            _token: "{{ csrf_token() }}",
            _method: "PUT",
            supplier_id: $("#edit_supplier_id").val(),
            driver_id: $("#edit_driver_id").val(),
            quantity: $("#edit_quantity").val(),
            price_per_ton: $("#edit_price_per_ton").val(),
            from: $("#edit_from").val(),
            to: $("#edit_to").val(),
            transportation_cost: $("#edit_transportation_cost").val(),
            supplier_balance: $("#edit_supplier_balance").val(),
            driver_balance: $("#edit_driver_balance").val(),
        };

        $.ajax({
            url: "{{ route('purchase.UpdatePurchase', ':id') }}".replace(":id", invoiceId), // Replace with dynamic ID
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    Swal.fire("Updated!", "Supplier details updated successfully!", "success");

                    // Update Row in Table Dynamically
                    let row = $("#classRow_" + invoiceId);
                    row.find("td:nth-child(2)").text(response.data.supplier_name);
                    row.find("td:nth-child(3)").text(response.data.driver_name);
                    row.find("td:nth-child(4)").text(response.data.quantity);
                    row.find("td:nth-child(5)").text(response.data.price_per_ton);
                    row.find("td:nth-child(6)").text(response.data.from);
                    row.find("td:nth-child(7)").text(response.data.to);
                    row.find("td:nth-child(8)").text(response.data.transportation_cost);
                    row.find("td:nth-child(9)").text(response.data.supplier_balance);
                    row.find("td:nth-child(10)").text(response.data.driver_balance);

                    // ✅ Close Modal
                    $("#editInvoiceModal").modal("hide");
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                    setTimeout(() => location.reload(), 1000);

                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire("Error!", "Something went wrong. Please try again.", "error");
            }
        });
    });
});

    </script>
@endsection
