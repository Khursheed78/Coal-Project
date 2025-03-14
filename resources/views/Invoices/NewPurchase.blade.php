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

                                                        <td>{{ $purchase->driver->name ?? 'N/A' }}</td>
                                                        <td>{{ $purchase->driver->phone ?? 'N/A' }}</td>
                                                        <td class="text-success">
                                                            {{ number_format($purchase->driver_balance, 2) }}</td>

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

                                                        <td>{{ $purchase->from }}</td>
                                                        <td>{{ $purchase->to }}</td>
                                                        <td>{{ $purchase->quantity }}</td>
                                                        <td>{{ number_format($purchase->price_per_ton, 2) }}</td>
                                                        <td class="text-warning">
                                                            {{ number_format($purchase->transportation_cost, 2) }}</td>

                                                        <td class="fw-bold text-success">
                                                            {{ number_format($purchase->quantity * $purchase->price_per_ton + $purchase->transportation_cost + $purchase->supplier_balance + $purchase->driver_balance, 2) }}
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-primary btn-sm editPurchase"
                                                                data-id="{{ $purchase->id }}"
                                                                data-supplier-id="{{ $purchase->supplier_id }}"
                                                                data-supplier-name="{{ $purchase->supplier->supplier_name }}"
                                                                data-driver-id="{{ $purchase->driver_id }}"
                                                                data-driver-name="{{ $purchase->driver->name }}"
                                                                data-quantity="{{ $purchase->quantity }}"
                                                                data-price-per-ton="{{ $purchase->price_per_ton }}"
                                                                data-transportation-cost="{{ $purchase->transportation_cost }}"
                                                                data-from="{{ $purchase->from }}"
                                                                data-to="{{ $purchase->to }}" data-bs-toggle="modal"
                                                                data-bs-target="#editPurchaseModal">
                                                                <i class="fa fa-edit text-white"></i>
                                                            </button>


                                                            <button class="btn btn-danger btn-sm deleteInvoice"
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
                                        {{ $purchases->links() }}
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
        <!-- Edit Purchase Modal -->
        <div class="modal fade" id="editPurchaseModal" tabindex="-1" aria-labelledby="editPurchaseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPurchaseModalLabel">Edit Purchase</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updatePurchaseForm">
                            @csrf
                            <input type="hidden" id="edit_purchase_id" name="id">

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

                            <button type="submit" class="btn btn-primary">Update Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                /**
                 * ✅ Handle Supplier Dropdown Change
                 */
                $(document).ready(function() {
                    /**
                     * ✅ Handle Supplier Dropdown Change
                     */
                    $("#supplier_id").change(function() {
                        let selected = $(this).find("option:selected"); // Get selected option

                        // Get data attributes
                        let supplierName = selected.attr("data-name") || "N/A";
                        let supplierPhone = selected.attr("data-phone") || "N/A";
                        let supplierBalance = selected.attr("data-balance") || "";

                        console.log("Selected Supplier:", supplierName, supplierPhone,
                            supplierBalance); // Debugging

                        // Update supplier details
                        $("#supplierName").text(supplierName);
                        $("#supplierPhone").text(supplierPhone);
                        $("#supplierBalance").val(supplierBalance);

                        // Show supplier details card if a valid supplier is selected
                        $("#supplierDetails").toggle(!!selected.val());
                    });

                    /**
                     * ✅ Handle Driver Dropdown Change
                     */
                    $("#driver_id").change(function() {
                        let selected = $(this).find("option:selected"); // Get selected option

                        // Get data attributes
                        let driverName = selected.attr("data-name") || "N/A";
                        let driverPhone = selected.attr("data-phone") || "N/A";
                        let driverBalance = selected.attr("data-balance") || "";

                        console.log("Selected Driver:", driverName, driverPhone,
                            driverBalance); // Debugging

                        // Update driver details
                        $("#driverName").text(driverName);
                        $("#driverPhone").text(driverPhone);
                        $("#driverBalance").val(driverBalance);

                        // Show driver details card if a valid driver is selected
                        $("#driverDetails").toggle(!!selected.val());
                    });
                });


                /**
                 * ✅ Delete Purchase Record
                 */
                $(document).on("click", ".deleteInvoice", function() {
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
                                url: "{{ route('purchase.delete') }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: purchaseId
                                },
                                success: function() {
                                    Swal.fire("Deleted!", "The purchase has been removed.",
                                        "success");

                                    $("#classRow_" + purchaseId).fadeOut(500, function() {
                                        $(this).remove();
                                    });

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

                /**
                 * ✅ Update Supplier and Driver Count after Deletion
                 */
                function updateSupplierDriverCount(supplierId, driverId) {
                    let supplierCountElement = $("#totalUniqueSuppliers");
                    let driverCountElement = $("#totalUniqueDrivers");

                    if ($(".supplier-id").filter((_, el) => $(el).text() == supplierId).length === 0) {
                        let currentSupplierCount = parseInt(supplierCountElement.text()) || 0;
                        if (currentSupplierCount > 0) supplierCountElement.text(currentSupplierCount - 1);
                    }

                    if ($(".driver-id").filter((_, el) => $(el).text() == driverId).length === 0) {
                        let currentDriverCount = parseInt(driverCountElement.text()) || 0;
                        if (currentDriverCount > 0) driverCountElement.text(currentDriverCount - 1);
                    }
                }

                /**
                 * ✅ Handle Payment Modal
                 */
                $(".payment-btn").click(function() {
                    let id = $(this).data("id");
                    let name = $(this).data("name");
                    let balance = $(this).data("balance");
                    let field = $(this).data("field");

                    $("#modalPaymentName").text(name);
                    $("#paymentAmount").val(balance);
                    $("#paymentId").val(id);
                    $("#paymentField").val(field);

                    $("#PaymentModalLabel").text(field === "supplier_balance" ? "Supplier Payment" :
                        "Driver Payment");
                });

                /**
                 * ✅ Handle Payment Submission
                 */
                $("#submitPayment").click(function() {
                    let paymentId = $("#paymentId").val();
                    let paymentField = $("#paymentField").val();
                    let paymentAmount = parseFloat($("#paymentAmount").val());

                    if (!paymentId || isNaN(paymentAmount) || paymentAmount <= 0) {
                        Swal.fire("Error!", "Enter a valid payment amount.", "error");
                        return;
                    }

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
                            if (response.success) {
                                Swal.fire("Success!", "Payment processed successfully!", "success");

                                $("#PaymentModal").modal("hide");
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");

                                setTimeout(() => location.reload(), 1000);
                            } else {
                                Swal.fire("Error!", "Unexpected response from server!", "error");
                            }
                        },
                        error: function(xhr) {
                            console.log("AJAX Error:", xhr.responseText);
                            Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                        }
                    });
                });

                /**
                 * ✅ Auto-calculate Total Price
                 */
                $("#quantity_tons, #price_per_ton").on("input", function() {
                    let quantity = parseFloat($("#quantity_tons").val()) || 0;
                    let price = parseFloat($("#price_per_ton").val()) || 0;
                    $("#total_price").val((quantity * price).toFixed(2));
                });

                /**
                 * ✅ Handle Purchase Form Submission
                 */
                $("#purchaseInvoiceForm").submit(function(e) {
                    e.preventDefault();

                    let formData = {
                        _token: "{{ csrf_token() }}",
                        supplier_id: $("#supplier_id").val(),
                        driver_id: $("#driver_id").val(),
                        quantity: parseFloat($("#quantity_tons").val()) || 0,
                        price_per_ton: parseFloat($("#price_per_ton").val()) || 0,
                        total_price: ($("#quantity_tons").val() * $("#price_per_ton").val() + parseFloat($(
                            "#transportation_cost").val() || 0)).toFixed(2),
                        transportation_cost: $("#transportation_cost").val(),
                        supplier_balance: $("#supplierBalance").val(),
                        driver_balance: $("#driverBalance").val(),
                        to_place: $("#to").val(),
                        from_place: $("#from").val(),
                        date: $("#date").val()
                    };

                    $.ajax({
                        url: "{{ route('purchase.store') }}",
                        type: "POST",
                        data: formData,
                        success: function() {
                            Swal.fire("Success!", "Invoice Submitted Successfully!", "success");
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            console.log("AJAX Error:", xhr.responseText);
                            Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                        }
                    });
                });
                $(document).ready(function() {
                    /**
                     * ✅ Handle Purchase Edit - Populate Modal Fields
                     */
                    $(".editPurchase").click(function() {
                        let purchaseId = $(this).data("id");
                        let supplierId = $(this).data("supplier-id");
                        let driverId = $(this).data("driver-id");
                        let supplierName = $(this).data("supplier-name");
                        let driverName = $(this).data("driver-name");
                        let quantity = $(this).data("quantity");
                        let pricePerTon = $(this).data("price-per-ton");
                        let transportationCost = $(this).data("transportation-cost");
                        let from = $(this).data("from");
                        let to = $(this).data("to");

                        // Populate modal fields
                        $("#edit_purchase_id").val(purchaseId);
                        $("#edit_supplier_id").val(supplierId).trigger("change");
                        $("#edit_driver_id").val(driverId).trigger("change");
                        $("#edit_quantity").val(quantity);
                        $("#edit_price_per_ton").val(pricePerTon);
                        $("#edit_transportation_cost").val(transportationCost);
                        $("#edit_from").val(from);
                        $("#edit_to").val(to);

                        // Show modal
                        $("#editPurchaseModal").modal("show");
                    });

                    /**
                     * ✅ Handle Update Form Submission
                     */
                    $("#updatePurchaseForm").submit(function(e) {
                        e.preventDefault();

                        let purchaseId = $("#edit_purchase_id").val();
                        let formData = {
                            _token: "{{ csrf_token() }}", // CSRF Token for Laravel
                            _method: "PUT", // Laravel requires PUT for updates
                            supplier_id: $("#edit_supplier_id").val(),
                            driver_id: $("#edit_driver_id").val(),
                            quantity: $("#edit_quantity").val(),
                            price_per_ton: $("#edit_price_per_ton").val(),
                            transportation_cost: $("#edit_transportation_cost").val(),
                            from: $("#edit_from").val(),
                            to: $("#edit_to").val()
                        };

                        $.ajax({
                            url: "{{ route('purchase.UpdatePurchase', ':id') }}".replace(":id",
                                purchaseId), // Replace ID dynamically
                            type: "POST", // Use POST with `_method: "PUT"`
                            data: formData,
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Updated!",
                                    text: "Purchase details updated successfully!",
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Close modal properly
                                $("#editPurchaseModal").modal("hide");
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");

                                // Refresh page after 1 second
                                setTimeout(() => location.reload(), 1000);
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                                Swal.fire("Error!",
                                    "Something went wrong. Please try again.", "error");
                            }
                        });
                    });
                });

            });
        </script>
    @endsection
