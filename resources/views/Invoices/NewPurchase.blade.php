@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <h2 class="mb-4">Create Purchase Invoice</h2>
                            <div class="card">
                                <div class="card-body">
                                    <form id="purchaseInvoiceForm">
                                        @csrf
                                        <!-- Supplier Dropdown -->
                                        <div class="mb-3">
                                            <label for="supplier_id" class="form-label">Select Supplier</label>
                                            <select id="supplier_id" class="form-control">
                                                <option value="">Choose Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        data-name="{{ $supplier->supplier_name }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-balance="{{ $supplier->balance }}">
                                                        {{ $supplier->supplier_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Supplier Details -->
                                        <div id="supplierDetails" class="card mt-3 shadow-sm" style="display: none;">
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
                                                        <p class="fw-bold mb-1">Balance:</p>
                                                        <p id="supplierBalance" class="text-muted"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Driver Dropdown -->
                                        <div class="mb-3">
                                            <label for="driver_id" class="form-label">Select Driver</label>
                                            <select id="driver_id" class="form-control">
                                                <option value="">Choose Driver</option>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                                        data-trips="{{ $driver->number_of_trips }}"
                                                        data-balance="{{ $driver->balance }}"
                                                        data-quantity="{{ $driver->quantity_tons }}"
                                                        data-price="{{ $driver->price_per_ton }}">
                                                        {{ $driver->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Driver Details -->
                                        <div id="driverDetails" class="card mt-3 shadow-sm" style="display: none;">
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
                                                        <p class="fw-bold mb-1">No. of Trips:</p>
                                                        <p id="driverTrips" class="text-muted"></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold mb-1">Balance:</p>
                                                        <p id="driverBalance" class="text-muted"></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold mb-1">Coal Quantity (Tons):</p>
                                                        <p id="driverQuantity" class="text-muted"></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold mb-1">Price per Ton:</p>
                                                        <p id="driverPrice" class="text-muted"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Coal Quantity & Price -->
                                        <div class="mb-3">
                                            <label for="quantity_tons" class="form-label">Quantity (Tons)</label>
                                            <input type="number" id="quantity_tons" class="form-control" min="1"
                                                name="quantity" required>
                                        </div>


                                        <div class="mb-3">
                                            <label for="price_per_ton" class="form-label">Price per Ton</label>
                                            <input type="number" id="price_per_ton" class="form-control" min="1"
                                                name="price_per_ton" required>
                                        </div>

                                        <!-- Total Price (Auto-calculated) -->
                                        <div class="mb-3">
                                            <label for="total_price" class="form-label">Total Price</label>
                                            <input type="text" id="total_price" class="form-control" name="tprice"
                                                readonly>

                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit Invoice</button>

                                        <button type="button" id="generatePdfBtn" class="btn btn-success">
                                            <i class="fa fa-file-pdf"></i> Generate PDF
                                        </button>
                                        {{-- <a href="{{route('stock.pdf')}}"
                                            class="btn btn-success">
                                            <i class="fa fa-file-pdf"></i> Download PDF
                                        </a> --}}
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Modal -->
        <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stockModalLabel">Add Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="stockForm">
                            @csrf
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Select Supplier</label>
                                <select id="supplier_id" class="form-control">
                                    <option value="">Choose Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                            data-phone="{{ $supplier->phone }}" data-balance="{{ $supplier->balance }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="driver_id" class="form-label">Select Driver</label>
                                <select id="driver_id" class="form-control">
                                    <option value="">Choose Driver</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                            data-trips="{{ $driver->trips }}" data-balance="{{ $driver->balance }}"
                                            data-quantity="{{ $driver->quantity }}"
                                            data-price="{{ $driver->price_per_ton }}">
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="button" id="saveStock" class="btn btn-primary">Save Stock</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#generatePdfBtn').on('click', function(e) {
                    e.preventDefault();

                    // let formData = {
                    //     _token: $('input[name="_token"]').val(),
                    //     supplier_name: $('#supplier_name').val(),
                    //     supplier_phone: $('#supplier_phone').val(),
                    //     driver_name: $('#driver_name').val(),
                    //     quantity: $('#quantity_tons').val(),
                    //     price_per_ton: $('#price_per_ton').val()
                    // };

                    $.ajax({
                        url: "{{ route('stock.pdf') }}",
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
                            quantity: $("#quantity_tons").val(), // Corrected field name
                            price_per_ton: $("#price_per_ton").val(),
                            total_price: $("#total_price").val(),
                        },
                        xhrFields: {
                            responseType: 'blob' // Ensures the response is treated as a file
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
                        error: function(xhr, status, error) {
                            console.error("Error generating PDF:", error);
                        }
                    });
                });
            });

            $(document).ready(function() {
                // Show Supplier Details when a supplier is selected
                $("#supplier_id").change(function() {
                    var selectedOption = $(this).find("option:selected");
                    var supplierName = selectedOption.data("name");
                    var supplierPhone = selectedOption.data("phone");
                    var supplierBalance = selectedOption.data("balance");

                    if (supplierName) {
                        $("#supplierName").text(supplierName);
                        $("#supplierPhone").text(supplierPhone);
                        $("#supplierBalance").text(supplierBalance);
                        $("#supplierDetails").show();
                    } else {
                        $("#supplierDetails").hide();
                    }
                });

                // Show Driver Details when a driver is selected
                $("#driver_id").change(function() {
                    var selectedOption = $(this).find("option:selected");
                    var driverName = selectedOption.data("name");
                    var driverTrips = selectedOption.data("trips");
                    var driverBalance = selectedOption.data("balance");
                    var driverQuantity = selectedOption.data("quantity");
                    var driverPrice = selectedOption.data("price");

                    if (driverName) {
                        $("#driverName").text(driverName);
                        $("#driverTrips").text(driverTrips);
                        $("#driverBalance").text(driverBalance);
                        $("#driverQuantity").text(driverQuantity);
                        $("#driverPrice").text(driverPrice);
                        $("#driverDetails").show();
                    } else {
                        $("#driverDetails").hide();
                    }
                });

                // Auto-calculate total price
                $("#quantity_tons, #price_per_ton").on("input", function() {
                    var ssquantity = parseFloat($("#quantity_tons").val()) || 0;
                    var price = parseFloat($("#price_per_ton").val()) || 0;
                    var totalPrice = ssquantity * price;
                    $("#total_price").val(totalPrice.toFixed(2));
                });

                // AJAX Form Submission
                $("#purchaseInvoiceForm").submit(function(e) {
                    e.preventDefault(); // Prevent the default form submission
                    // Recalculate total price before submitting
                    var ssquantity = parseFloat($("#quantity_tons").val()) || 0;
                    var price = parseFloat($("#price_per_ton").val()) || 0;
                    var totalPrice = ssquantity * price;
                    $.ajax({
                        url: "{{ route('stock.store') }}", // Replace with your actual route name
                        type: "POST",
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
                            total_price: totalPrice, // Corrected field name
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Success!",
                                text: "Invoice Submitted Successfully!",
                                icon: "success",
                                timer: 3000, // Auto close after 3 seconds
                                showConfirmButton: true
                            }).then(() => {
                                $("#purchaseInvoiceForm")[0]
                                    .reset(); // Reset the form after submission
                                $("#supplierDetails, #driverDetails").hide();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong. Please try again.",
                                icon: "error",
                                timer: 3000, // Auto close after 3 seconds
                                showConfirmButton: true
                            });
                            console.log(xhr.responseText);
                        }
                    });

                });
            });
        </script>
    @endsection
