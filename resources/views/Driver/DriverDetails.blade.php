@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Driver</h4>
                        <!-- Button to Open Modal -->
                        @if (Auth::user()->role === 'admin')
                            <!-- Button to Open Modal -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#DriverModal">
                                        Add Driver Details
                                    </button>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="searchPhone" class="form-control"
                                            placeholder="Search by phone number">
                                        <button class="btn btn-primary" id="searchBtn">Search</button>
                                    </div>
                                </div>
                        @endif

                        <div class="table-responsive">
                            <table id="classTable" class="table table-bordered mt-4 table-striped">
                                <thead style="border-bottom: 2px solid black;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Driver Name</th>
                                        <th>Phone</th>
                                        <th>Vehicle Number</th>
                                        <th>Driver Balance</th>
                                        <th>Balance Status</th>
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
                                <tbody id="driverTableBody">
                                    @foreach ($drivers as $driver)
                                        <tr id="classRow_{{ $driver->id }}">
                                            <td>{{ $driver->id }}</td>
                                            <td>{{ $driver->driver->name ?? 'N/A' }}</td>
                                            <td>{{ $driver->driver->phone ?? 'N/A' }}</td>
                                            <td>{{ $driver->driver->vehicle_number ?? 'N/A' }}</td>
                                            <td>{{ number_format($driver->total_balance, 2) }}</td>
                                            <!-- Driver Payment -->
                                            <td>
                                                @if ($driver->driver_balance == 0)
                                                    <span class="badge bg-danger"><i class="fas fa-check-circle"></i>
                                                        Paid</span>
                                                @else
                                                    <button type="button" class="btn btn-success payment-btn"
                                                        data-bs-toggle="modal" data-bs-target="#PaymentModal"
                                                        data-id="{{ $driver->id }}"
                                                        data-name="{{ $driver->driver->name }}"
                                                        data-balance="{{ $driver->driver_balance }}"
                                                        data-field="driver_balance">
                                                        Pay Now
                                                    </button>
                                                @endif
                                            </td>

                                            <td>{{ $driver->total_trips }}</td>
                                            <td>{{ $driver->from }}</td>
                                            <td>{{ $driver->to }}</td>
                                            <td>{{ $driver->quantity }}</td>
                                            <td>{{ $driver->price_per_ton }}</td>
                                            <td>{{ $driver->transportation_cost }}</td>
                                            <td class="fw-bold text-success">
                                                @if ($driver->driver_balance > 0)
                                                    {{ number_format($driver->quantity * $driver->price_per_ton + $driver->transportation_cost + $driver->driver_balance, 2) }}
                                                @else
                                                    {{ number_format($driver->quantity * $driver->price_per_ton + $driver->transportation_cost, 2) }}
                                                @endif
                                            </td>


                                            <td>
                                                <button class="btn btn-sm btn-primary editDriverDetails"
                                                    data-id="{{ $driver->id }}"
                                                    data-driver-id="{{ $driver->driver_id }}"
                                                    data-name="{{ $driver->driver->name }}"
                                                    data-phone="{{ $driver->driver->phone }}"
                                                    data-vehicle-number="{{ $driver->driver->vehicle_number }}"
                                                    data-balance="{{ $driver->driver->balance }}"
                                                    data-trips="{{ $driver->driver->no_of_trips }}"
                                                    data-quantity="{{ $driver->quantity }}"
                                                    data-price="{{ $driver->price_per_ton }}"
                                                    data-total-price="{{ $driver->total_price }}"
                                                    data-from="{{ $driver->from }}" data-to="{{ $driver->to }}"
                                                    data-transportation="{{ $driver->transportation_cost }}"
                                                    data-driver-balance="{{ $driver->driver_balance }}"
                                                    data-date="{{ $driver->date }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>

                                                <button class="btn btn-danger btn-sm deleteDriverDetails"
                                                    data-id="{{ $driver->id }}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>

                                                <a href="{{ route('driver.generatepdf', ['id' => $driver->id]) }}"
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
                            {{ $drivers->links('pagination::bootstrap-5') }}
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
        <div class="modal fade" id="DriverModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 70%;" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel">Add Driver Details </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="driverDetailsForm">
                            @csrf
                            <!-- Driver Dropdown -->
                            <div class="mb-3">
                                <label for="driver_id" class="form-label">Select Driver</label>
                                <select id="driver_id" name="driver_id" class="form-control">
                                    <option value="">Choose Driver</option>
                                    @foreach ($allDrivers as $driver)
                                        <option value="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                            data-phone="{{ $driver->phone }}" data-balance="{{ $driver->balance }}"
                                            data-no_of_trips="{{ $driver->no_of_trips }}"
                                            data-vehicle_number="{{ $driver->vehicle_number }}">
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
                                            <p class="fw-bold mb-1">Vehicle No:</p>
                                            <p id="driverVehicalNo" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Previous Balance:</p>
                                            <p id="PreviousBalance" class="text-muted"></p> <!-- Changed to <p> -->
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Previous Trips:</p>
                                            <p id="PreviousTrips" class="text-muted"></p> <!-- Changed to <p> -->
                                        </div>

                                        <div class="col-md-4">
                                            <label for="driver_balance" class="fw-bold mb-1">Balance:</label>
                                            <input type="text" id="driver_balance" name="driver_balance"
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
                                    <label for="quantity" class="form-label">Quantity (Tons)</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control"
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

        <!-- Payment Modal -->
        <div class="modal fade" id="PaymentModal" tabindex="-1" aria-labelledby="PaymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="PaymentModalLabel">Driver Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Driver Name:</strong> <span id="modalDriverName"></span></p>

                        <!-- Editable Balance Input -->
                        <div class="form-group">
                            <label for="paymentAmount">Balance:</label>
                            <input type="number" class="form-control" id="paymentAmount" min="0">
                        </div>

                        <input type="hidden" id="paymentId">
                        <input type="hidden" id="paymentField">

                        <!-- Submit Payment Button -->
                        <button type="button" class="btn btn-primary mt-2" id="submitPayment">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDriverModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Added modal-xl for large size -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Driver Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDriverForm">
                            @csrf
                            <input type="hidden" id="edit_driver_id" name="id">

                            <!-- Driver Dropdown -->
                            <div class="mb-3">
                                <label for="edit_driver_select" class="form-label">Select Driver</label>
                                <select id="edit_driver_select" name="driver_id" class="form-control">
                                    <option value="">Choose Driver</option>
                                    @foreach ($allDrivers as $driver)
                                        <option value="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                            data-phone="{{ $driver->phone }}" data-balance="{{ $driver->balance }}"
                                            data-no_of_trips="{{ $driver->no_of_trips }}"
                                            data-vehicle_number="{{ $driver->vehicle_number }}">
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Driver Details -->
                            <div id="edit_driverDetails" class="card my-3 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-truck"></i> Driver Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Name:</p>
                                            <p id="edit_driverName" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Phone:</p>
                                            <p id="edit_driverPhone" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Vehicle No:</p>
                                            <p id="edit_driverVehicalNo" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Previous Balance:</p>
                                            <p id="edit_PreviousBalance" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-1">Previous Trips:</p>
                                            <p id="edit_PreviousTrips" class="text-muted"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="edit_driver_balance" class="fw-bold mb-1">Balance:</label>
                                            <input type="text" id="edit_driver_balance" name="driver_balance"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coal Quantity & Price -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="edit_quantity" class="form-label">Quantity (Tons)</label>
                                    <input type="number" id="edit_quantity" name="quantity" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_price_per_ton" class="form-label">Price per Ton</label>
                                    <input type="number" id="edit_price_per_ton" name="price_per_ton"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_total_price" class="form-label">Total Price</label>
                                    <input type="text" id="edit_total_price" name="total_price" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_to" class="form-label">To (Place)</label>
                                    <input type="text" id="edit_to" name="to" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_from" class="form-label">From (Place)</label>
                                    <input type="text" id="edit_from" name="from" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_date" class="form-label">Date</label>
                                    <input type="date" id="edit_date" name="date" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_transportation_cost" class="form-label">Transportation Cost</label>
                                    <input type="text" id="edit_transportation_cost" name="transportation_cost"
                                        class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


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


        {{-- Ajax --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#searchBtn").click(function() {
                    let phoneNumber = $("#searchPhone").val().trim();

                    if (phoneNumber === "") {
                        Swal.fire({
                            icon: "warning",
                            title: "Enter a phone number!",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }

                    $.ajax({
                        url: "{{ route('driver.searchByPhone') }}",
                        type: "GET",
                        data: {
                            phone: phoneNumber
                        },
                        success: function(response) {
                            if (response.success) {
                                let drivers = response.drivers;
                                let tableBody = $("#driverTableBody");
                                tableBody.empty(); // Clear existing data

                                if (drivers.length === 0) {
                                    tableBody.append(
                                        `<tr><td colspan="13" class="text-center text-danger">No drivers found!</td></tr>`
                                        );
                                } else {
                                    $.each(drivers, function(index, driverDetail) {
                                        // If driver relationship is missing, show 'N/A'
                                        let driver = driverDetail.driver || {};

                                        let newRow = `
                                <tr id="classRow_${driverDetail.id}">
                                    <td>${driverDetail.id}</td>
                                    <td>${driver.name || 'N/A'}</td>
                                    <td>${driver.phone || 'N/A'}</td>
                                    <td>${driver.vehicle_number || 'N/A'}</td>
                                    <td>${parseFloat(driverDetail.driver_balance).toFixed(2)}</td>
                                    <td>
                                        ${driverDetail.driver_balance == 0
                                            ? `<span class="badge bg-danger"><i class="fas fa-check-circle"></i> Paid</span>`
                                            : `<button class="btn btn-success payment-btn"
                                                          data-id="${driverDetail.id}"
                                                          data-name="${driver.name || 'N/A'}"
                                                          data-balance="${driverDetail.driver_balance}">
                                                          Pay Now
                                                      </button>`
                                        }
                                    </td>
                                    <td>${driverDetail.total_trips || 'N/A'}</td>
                                    <td>${driverDetail.from}</td>
                                    <td>${driverDetail.to}</td>
                                    <td>${driverDetail.quantity}</td>
                                    <td>${driverDetail.price_per_ton}</td>
                                    <td>${driverDetail.transportation_cost}</td>
                                    <td class="fw-bold text-success">
                                        ${(parseFloat(driverDetail.quantity) * parseFloat(driverDetail.price_per_ton) + parseFloat(driverDetail.transportation_cost) + (parseFloat(driverDetail.driver_balance) > 0 ? parseFloat(driverDetail.driver_balance) : 0)).toFixed(2)}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editDriverDetails"
                                            data-id="${driverDetail.id}"
                                            data-driver-id="${driver.id || ''}"
                                            data-name="${driver.name || ''}"
                                            data-phone="${driver.phone || ''}"
                                            data-vehicle-number="${driver.vehicle_number || ''}"
                                            data-balance="${driver.balance || ''}"
                                            data-trips="${driverDetail.total_trips || ''}"
                                            data-quantity="${driverDetail.quantity}"
                                            data-price="${driverDetail.price_per_ton}"
                                            data-total-price="${driverDetail.total_price}"
                                            data-from="${driverDetail.from}"
                                            data-to="${driverDetail.to}"
                                            data-transportation="${driverDetail.transportation_cost}"
                                            data-driver-balance="${driverDetail.driver_balance}"
                                            data-date="${driverDetail.date}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button class="btn btn-danger btn-sm deleteDriverDetails" data-id="${driverDetail.id}">
                                            <i class="fa fa-trash text-white"></i>
                                        </button>

                                        <a href="/generate-pdf/${driverDetail.id}" class="btn btn-success">
                                         <i class="fa fa-file-pdf"></i> Download PDF</a>

                                    </td>
                                </tr>`;
                                        tableBody.append(newRow);
                                    });
                                }
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                icon: "error",
                                title: "Something went wrong!",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                });
            });


            $(document).ready(function() {
                $("#driver_id").change(function() {
                    let selected = $(this).find("option:selected");

                    if (selected.val()) {
                        $("#driverName").text(selected.data("name")); // Set text for name
                        $("#driverPhone").text(selected.data("phone")); // Set text for phone
                        $("#driverVehicalNo").text(selected.data("vehicle_number")); // Set vehicle number
                        $("#PreviousBalance").text(selected.data("balance")); // Use .val() for input field
                        $("#PreviousTrips").text(selected.data("no_of_trips")); // Use .val() for input field

                        $("#driverDetails").show(); // Show driver details
                    } else {
                        $("#driverDetails").hide(); // Hide if no driver selected
                    }
                });

                // Auto-calculate total price based on quantity & price per ton
                $("#quantity, #price_per_ton").on("input", function() {
                    let quantity = parseFloat($("#quantity").val()) || 0;
                    let pricePerTon = parseFloat($("#price_per_ton").val()) || 0;
                    let totalPrice = quantity * pricePerTon;
                    $("#total_price").val(totalPrice.toFixed(2)); // Show 2 decimal places
                });
            });


            $(document).ready(function() {
                $("#driverDetailsForm").submit(function(e) {
                    e.preventDefault(); // Prevent Default Form Submission

                    let formData = {
                        _token: "{{ csrf_token() }}", // CSRF Token for Security
                        driver_id: $("#driver_id").val(),
                        quantity: $("#quantity").val(),
                        price_per_ton: $("#price_per_ton").val(),
                        transportation_cost: $("#transportation_cost").val(),
                        driver_balance: $("#driver_balance").val(),
                        from: $("#from").val(),
                        to: $("#to").val(),
                        date: $("#date").val(),
                    };

                    $.ajax({
                        url: "{{ route('driver.details.store') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Success!", response.message, "success");

                                // ✅ Close Modal & Reset Form
                                $("#DriverModal").modal("hide");
                                $("#driverDetailsForm")[0].reset();

                                // ✅ Remove Modal Backdrop
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");

                                // ✅ Append New Row to Table
                                let newRow = `
                        <tr id="row_${response.data.id}">
                            <td>${response.data.id}</td>
                            <td>${response.data.driver_id}</td>
                            <td>${response.data.quantity}</td>
                            <td>${response.data.price_per_ton}</td>
                            <td>${response.data.transportation_cost}</td>
                            <td>${response.data.total_price}</td>
                            <td>${response.data.driver_balance}</td>
                            <td>${response.data.from}</td>
                            <td>${response.data.to}</td>
                            <td>${response.data.date}</td>
                            <td>
                                <button class="btn btn-primary btn-sm editDriverDetails"
                                    data-id="${response.data.id}"
                                    data-quantity="${response.data.quantity}"
                                    data-price="${response.data.price_per_ton}"
                                    data-transportation="${response.data.transportation_cost}"
                                    data-from="${response.data.from}"
                                    data-to="${response.data.to}"
                                    data-balance="${response.data.driver_balance}"
                                    data-date="${response.data.date}"
                                    data-bs-toggle="modal" data-bs-target="#editDriverModal">
                                    <i class="fa fa-edit text-white"></i>
                                </button>

                                <button class="btn btn-danger btn-sm deleteDriverDetails" data-id="${response.data.id}">
                                    <i class="fa fa-trash text-white"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                                $("tbody").prepend(newRow); // ✅ Append New Data at the Top
                                setTimeout(() => location.reload(), 1000);
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = "";
                            $.each(errors, function(key, value) {
                                errorMessage += value + "\n";
                            });

                            Swal.fire("Error!", errorMessage, "error");
                        }
                    });
                });

                $(document).ready(function() {
                    /* ============================
                       ✅ HANDLE PAYMENT BUTTON CLICK
                       ============================ */
                    $(".payment-btn").click(function() {
                        let name = $(this).data("name"); // Get Name
                        let balance = $(this).data("balance"); // Get Balance
                        let id = $(this).data("id"); // Get Purchase ID
                        let field = $(this).data("field"); // Get supplier_balance or driver_balance

                        // Set values in the modal
                        $("#modalDriverName").text(name);
                        $("#paymentAmount").val(balance);
                        $("#paymentId").val(id);
                        $("#paymentField").val(field);

                        // Update modal title dynamically
                        $("#PaymentModalLabel").text(field === "supplier_balance" ? "Supplier Payment" :
                            "Driver Payment");

                        // Debugging logs
                        console.log("Modal Opened:", {
                            id,
                            field,
                            balance
                        });
                    });

                    /* ============================
                       ✅ HANDLE PAYMENT SUBMISSION
                       ============================ */
                    $("#submitPayment").click(function() {
                        let paymentId = $("#paymentId").val();
                        let paymentField = $("#paymentField").val();
                        let paymentAmount = parseFloat($("#paymentAmount").val());

                        if (!paymentId || isNaN(paymentAmount) || paymentAmount <= 0) {
                            Swal.fire("Error!", "Enter a valid payment amount.", "error");
                            return;
                        }

                        let currentBalance = parseFloat($(
                                `button[data-id="${paymentId}"][data-field="${paymentField}"]`)
                            .data("balance"));
                        if (paymentAmount > currentBalance) {
                            Swal.fire("Error!", "Amount exceeds balance!", "error");
                            return;
                        }

                        // Debugging log before sending request
                        console.log("Sending Payment:", {
                            paymentId,
                            paymentField,
                            paymentAmount
                        });

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
                                    balanceElement.text(response.new_balance.toFixed(2));

                                    // Update button attributes
                                    $(`button[data-id="${paymentId}"][data-field="${paymentField}"]`)
                                        .data("balance", response.new_balance);

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
                                console.error("AJAX Error:", xhr.responseText);
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

                // Update Operation
                $(document).on("click", ".editDriverDetails", function() {
                    let id = $(this).data("id");
                    let driver_id = $(this).data("driver-id");
                    let driver_name = $(this).data("name");
                    let driver_phone = $(this).data("phone");
                    let vehicle_number = $(this).data("vehicle-number");
                    let previous_balance = $(this).data("balance");
                    let previous_trips = $(this).data("trips");

                    let quantity = $(this).data("quantity");
                    let price = $(this).data("price");
                    let total_price = $(this).data("total-price");
                    let from = $(this).data("from");
                    let to = $(this).data("to");
                    let transportation_cost = $(this).data("transportation");
                    let driver_balance = $(this).data("driver-balance");
                    let date = $(this).data("date");

                    // Populate Modal Fields
                    $("#edit_driver_id").val(id);
                    $("#edit_driver_select").val(driver_id);
                    $("#edit_driverName").text(driver_name);
                    $("#edit_driverPhone").text(driver_phone);
                    $("#edit_driverVehicalNo").text(vehicle_number);
                    $("#edit_PreviousBalance").text(previous_balance);
                    $("#edit_PreviousTrips").text(previous_trips);

                    $("#edit_quantity").val(quantity);
                    $("#edit_price_per_ton").val(price);
                    $("#edit_total_price").val(total_price);
                    $("#edit_from").val(from);
                    $("#edit_to").val(to);
                    $("#edit_transportation_cost").val(transportation_cost);
                    $("#edit_driver_balance").val(driver_balance);
                    $("#edit_date").val(date);

                    $("#editDriverModal").modal("show");
                });


                $(document).ready(function() {
                    $("#editDriverForm").submit(function(e) {
                        e.preventDefault();

                        let formData = {
                            _token: "{{ csrf_token() }}",
                            id: $("#edit_driver_id").val(),
                            driver_id: $("#edit_driver_select").val(),
                            quantity: $("#edit_quantity").val(),
                            price_per_ton: $("#edit_price_per_ton").val(),
                            total_price: $("#edit_total_price").val(),
                            transportation_cost: $("#edit_transportation_cost").val(),
                            driver_balance: $("#edit_driver_balance").val(),
                            from: $("#edit_from").val(),
                            to: $("#edit_to").val(),
                            date: $("#edit_date").val(),
                        };

                        $.ajax({
                            url: "{{ route('driver.details.update') }}",
                            type: "PUT",
                            data: formData,
                            success: function(response) {
                                Swal.fire("Updated!", response.message, "success");
                                $("#editDriverModal").modal("hide");
                                $("#editDriverForm")[0].reset();
                                location.reload(); // Refresh to see updated data
                            },
                            error: function(xhr) {
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    });
                });




                // ✅ Delete Driver Details
                $(document).on("click", ".deleteDriverDetails", function() {
                    let id = $(this).data("id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('driver.details.delete') }}",
                                type: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire("Deleted!", response.message, "success");

                                        // ✅ Match the correct row ID format
                                        $("#classRow_" + id).remove();
                                    } else {
                                        Swal.fire("Error!", response.message, "error");
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire("Error!", "Failed to delete.", "error");
                                }
                            });
                        }
                    });
                });



            });



            //             $(document).ready(function() {
            //                 /**
            //                  * ✅ SAVE DRIVER RECORD - Adds a new driver via AJAX
            //                  */
            //                 $("#DriverForm").submit(function(e) {
            //                     e.preventDefault();
            //                     let formData = $(this).serialize();

            //                     $.ajax({
            //                         url: "{{ route('driver.driverStore') }}",
            //                         type: "POST",
            //                         data: formData,
            //                         success: function(response) {
            //                             if (response.success && response.driver) {
            //                                 let driver = response.driver;

            //                                 // ✅ Show success message
            //                                 Swal.fire({
            //                                     toast: true,
            //                                     position: "top",
            //                                     icon: "success",
            //                                     title: "Driver added successfully!",
            //                                     showConfirmButton: false,
            //                                     timer: 3000
            //                                 });

            //                                 // ✅ Close modal properly
            //                                 $("#DriverModal").modal("hide");
            //                                 $("#DriverForm")[0].reset();
            //                                 $(".modal-backdrop").remove();
            //                                 $("body").removeClass("modal-open");

            //                                 // ✅ Add new row dynamically
            //                                 let newRow = `
    //                                 <tr id="classRow_${driver.id}">
    //                                     <td>${driver.id}</td>
    //                                     <td>${driver.name}</td>
    //                                     <td>${driver.vehicle_number}</td>
    //                                     <td>${driver.phone}</td>
    //                                     <td>
    //                                         <button class="btn btn-primary btn-sm editDriver"
    //                                             data-id="${driver.id}"
    //                                             data-name="${driver.name}"
    //                                             data-vehicle_number="${driver.vehicle_number}"
    //                                             data-phone="${driver.phone}"
    //                                             data-bs-toggle="modal" data-bs-target="#editDriverModal">
    //                                             <i class="fa fa-edit text-white"></i>
    //                                         </button>
    //                                         <button class="btn btn-danger btn-sm deleteDriver" data-id="${driver.id}">
    //                                             <i class="fa fa-trash text-white"></i>
    //                                         </button>
    //                                     </td>
    //                                 </tr>`;

            //                                 $("tbody").prepend(newRow); // ✅ Add new row to top of table
            //                             }
            //                         },
            //                         error: function(xhr) {
            //                             let errors = xhr.responseJSON.errors;
            //                             let errorMessage = "";
            //                             $.each(errors, function(key, value) {
            //                                 errorMessage += value + "\n";
            //                             });

            //                             Swal.fire({
            //                                 toast: true,
            //                                 position: "top-end",
            //                                 icon: "error",
            //                                 title: errorMessage,
            //                                 showConfirmButton: false,
            //                                 timer: 5000
            //                             });
            //                         }
            //                     });
            //                 });

            //                 /**
            //                  * ✅ DELETE DRIVER RECORD - Works for both new & existing records
            //                  */
            //                 $(document).on("click", ".deleteDriver", function() {
            //                     let driverId = $(this).data("id");

            //                     Swal.fire({
            //                         title: "Are you sure?",
            //                         text: "This driver will be permanently deleted!",
            //                         icon: "warning",
            //                         showCancelButton: true,
            //                         confirmButtonColor: "#d33",
            //                         cancelButtonColor: "#3085d6",
            //                         confirmButtonText: "Yes, delete it!"
            //                     }).then((result) => {
            //                         if (result.isConfirmed) {
            //                             $.ajax({
            //                                 url: "{{ route('driver.deleteDriver', ':id') }}".replace(":id",
            //                                     driverId),
            //                                 type: "DELETE",
            //                                 data: {
            //                                     _token: "{{ csrf_token() }}"
            //                                 },
            //                                 success: function() {
            //                                     Swal.fire("Deleted!", "Driver deleted successfully.",
            //                                         "success");

            //                                     // ✅ Remove row smoothly
            //                                     $("#classRow_" + driverId).fadeOut(500, function() {
            //                                         $(this).remove();
            //                                     });
            //                                 },
            //                                 error: function(xhr) {
            //                                     Swal.fire("Error!",
            //                                         "Failed to delete driver. Try again.", "error");
            //                                     console.log(xhr.responseText);
            //                                 }
            //                             });
            //                         }
            //                     });
            //                 });

            //                 /**
            //                  * ✅ EDIT DRIVER - Works for both new & existing records
            //                  */
            //                 $(document).on("click", ".editDriver", function() {
            //                     let id = $(this).data("id");
            //                     let name = $(this).data("name");
            //                     let vehicle_number = $(this).data("vehicle_number");
            //                     let phone = $(this).data("phone");

            //                     // ✅ Fill the modal fields
            //                     $("#edit_driver_id").val(id);
            //                     $("#edit_name").val(name);
            //                     $("#edit_vehicle_number").val(vehicle_number);
            //                     $("#edit_phone").val(phone);
            //                 });

            //                 /**
            //                  * ✅ UPDATE DRIVER RECORD - Works for both new & existing records
            //                  */
            //                 $("#editDriverForm").submit(function(e) {
            //                     e.preventDefault();

            //                     let driverId = $("#edit_driver_id").val();
            //                     let formData = {
            //                         _token: "{{ csrf_token() }}",
            //                         name: $("#edit_name").val(),
            //                         vehicle_number: $("#edit_vehicle_number").val(),
            //                         phone: $("#edit_phone").val(),
            //                     };

            //                     $.ajax({
            //                         url: "{{ route('driver.UpdateDriver', ':id') }}".replace(":id", driverId),
            //                         type: "PUT",
            //                         data: formData,
            //                         success: function(response) {
            //                             if (response.success) {
            //                                 Toastify({
            //                                     text: "Driver updated successfully!",
            //                                     backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            //                                     className: "info",
            //                                     gravity: "top",
            //                                     position: "right",
            //                                     duration: 3000
            //                                 }).showToast();

            //                                 // ✅ Update table row dynamically
            //                                 $("#classRow_" + driverId).find("td:nth-child(2)").text(response
            //                                     .data.name);
            //                                 $("#classRow_" + driverId).find("td:nth-child(3)").text(response
            //                                     .data.vehicle_number);
            //                                 $("#classRow_" + driverId).find("td:nth-child(4)").text(response
            //                                     .data.phone);

            //                                 // ✅ Hide modal properly
            //                                 $("#editDriverModal").modal("hide");
            //                                 $(".modal-backdrop").remove();
            //                                 $("body").removeClass("modal-open");
            //                             } else {
            //                                 Toastify({
            //                                     text: "Error updating driver.",
            //                                     backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            //                                     className: "error",
            //                                     gravity: "top",
            //                                     position: "right",
            //                                     duration: 5000
            //                                 }).showToast();
            //                             }
            //                         },
            //                         error: function(xhr) {
            //                             console.error(xhr.responseText); // Debugging
            //                             Toastify({
            //                                 text: "Something went wrong. Please try again.",
            //                                 backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            //                                 className: "error",
            //                                 gravity: "top",
            //                                 position: "right",
            //                                 duration: 5000
            //                             }).showToast();
            //                         }
            //                     });
            //                 });

            //                 /**
            //                  * ✅ ENSURE "Add New Driver" BUTTON WORKS AFTER ADDING A DRIVER
            //                  */
            //                 $(document).on("click", '[data-bs-toggle="modal"]', function() {
            //                     let targetModal = $(this).data("bs-target");
            //                     $(targetModal).modal("show");
            //                 });

            //             });
        </script>
    @endsection
