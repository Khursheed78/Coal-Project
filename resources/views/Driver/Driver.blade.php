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
                                        Add New Driver
                                    </button>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" id="searchPhone" class="form-control" placeholder="Search by phone number">
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
                                        <th>Vehicle Number</th>
                                        <th>Phone</th>
                                        <th>No of Trips</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="driverTableBody">
                                    @foreach ($drivers as $driver)
                                        <tr id="classRow_{{ $driver->id }}">
                                            <td>{{ $driver->id }}</td>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->vehicle_number }}</td>
                                            <td>{{ $driver->phone }}</td>
                                            <td>{{ $driver->no_of_trips }}</td>
                                            <td>{{ $driver->balance }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm editDriver"
                                                    data-id="{{ $driver->id }}"
                                                    data-name="{{ $driver->name }}"
                                                    data-vehicle_number="{{ $driver->vehicle_number }}"
                                                    data-phone="{{ $driver->phone }}"
                                                    data-no_of_trips="{{ $driver->no_of_trips }}"
                                                    data-balance="{{ $driver->balance }}"
                                                    data-bs-toggle="modal" data-bs-target="#editDriverModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deleteDriver" data-id="{{ $driver->id }}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel">Add New Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="DriverForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Driver Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Driver Name">
                            </div>

                            <div class="form-group">
                                <label for="vehicle_number">Vehicle Number</label>
                                <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                    placeholder="Vehicle Number">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone"
                                       placeholder="Phone">
                            </div><div class="form-group">
                                <label for="no_of_trips">No of Trips</label>
                                <input type="number" class="form-control" id="no_of_trips" name="no_of_trips"
                                    placeholder="No of Trips">
                            </div><div class="form-group">
                                <label for="balance">Balance</label>
                                <input type="number" class="form-control" id="balance" name="balance"
                                    placeholder="Balance">
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="modal fade" id="PaymentModal" tabindex="-1" aria-labelledby="PaymentModalLabel" aria-hidden="true">
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

                        <!-- Submit Payment Button -->
                        <button type="button" class="btn btn-primary mt-2" id="submitPayment">Pay Now</button>
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
        <!-- Edit Driver Modal -->
        <div class="modal fade" id="editDriverModal" tabindex="-1" aria-labelledby="DriverModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DriverModalLabel">Edit Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDriverForm">
                            @csrf
                            <input type="hidden" id="edit_driver_id" name="driver_id">

                            <div class="form-group">
                                <label for="edit_name">Driver Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="Driver Name">
                            </div>

                            <div class="form-group">
                                <label for="edit_vehicle_number">Vehicle Number</label>
                                <input type="text" class="form-control" id="edit_vehicle_number"
                                    name="vehicle_number" placeholder="Vehicle Number">
                            </div>
                            <div class="form-group">
                                <label for="edit_phone">Phone</label>
                                <input type="number" class="form-control" id="edit_phone" name="phone"
                                    placeholder="Phone">
                            </div>
                            <div class="form-group">
                                <label for="edit_no_of_trips">No of Trips</label>
                                <input type="number" class="form-control" id="edit_no_of_trips" name="no_of_trips"
                                    placeholder="No of Trips">
                            </div>
                            <div class="form-group">
                                <label for="edit_balance">Phone</label>
                                <input type="number" class="form-control" id="edit_balance" name="balance"
                                    placeholder="Balance">
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
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
            $(document).ready(function () {
    $("#searchBtn").click(function () {
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
            data: { phone: phoneNumber },
            success: function (response) {
                if (response.success) {
                    let drivers = response.drivers;
                    let tableBody = $("#driverTableBody");
                    tableBody.empty(); // Clear existing data

                    if (drivers.length === 0) {
                        tableBody.append(`<tr><td colspan="5" class="text-center text-danger">No drivers found!</td></tr>`);
                    } else {
                        $.each(drivers, function (index, driver) {
                            let newRow = `
                                <tr id="classRow_${driver.id}">
                                    <td>${driver.id}</td>
                                    <td>${driver.name}</td>
                                    <td>${driver.vehicle_number}</td>
                                    <td>${driver.phone}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editDriver"
                                            data-id="${driver.id}"
                                            data-name="${driver.name}"
                                            data-vehicle_number="${driver.vehicle_number}"
                                            data-phone="${driver.phone}"
                                            data-bs-toggle="modal" data-bs-target="#editDriverModal">
                                            <i class="fa fa-edit text-white"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteDriver" data-id="${driver.id}">
                                            <i class="fa fa-trash text-white"></i>
                                        </button>
                                    </td>
                                </tr>`;
                            tableBody.append(newRow);
                        });
                    }
                }
            },
            error: function (xhr) {
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
                /**
                 * ✅ SAVE DRIVER RECORD - Adds a new driver via AJAX
                 */
                $("#DriverForm").submit(function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('driver.driverStore') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success && response.driver) {
                                let driver = response.driver;

                                // ✅ Show success message
                                Swal.fire({
                                    toast: true,
                                    position: "top",
                                    icon: "success",
                                    title: "Driver added successfully!",
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                // ✅ Close modal properly
                                $("#DriverModal").modal("hide");
                                $("#DriverForm")[0].reset();
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");

                                // ✅ Add new row dynamically
                                let newRow = `
                                <tr id="classRow_${driver.id}">
                                    <td>${driver.id}</td>
                                    <td>${driver.name}</td>
                                    <td>${driver.vehicle_number}</td>
                                    <td>${driver.phone}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editDriver"
                                            data-id="${driver.id}"
                                            data-name="${driver.name}"
                                            data-vehicle_number="${driver.vehicle_number}"
                                            data-phone="${driver.phone}"
                                            data-bs-toggle="modal" data-bs-target="#editDriverModal">
                                            <i class="fa fa-edit text-white"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteDriver" data-id="${driver.id}">
                                            <i class="fa fa-trash text-white"></i>
                                        </button>
                                    </td>
                                </tr>`;

                                $("tbody").prepend(newRow); // ✅ Add new row to top of table
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = "";
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

                /**
                 * ✅ DELETE DRIVER RECORD - Works for both new & existing records
                 */
                $(document).on("click", ".deleteDriver", function() {
                    let driverId = $(this).data("id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This driver will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('driver.deleteDriver', ':id') }}".replace(":id",
                                    driverId),
                                type: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function() {
                                    Swal.fire("Deleted!", "Driver deleted successfully.",
                                        "success");

                                    // ✅ Remove row smoothly
                                    $("#classRow_" + driverId).fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire("Error!",
                                        "Failed to delete driver. Try again.", "error");
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
                });

                /**
                 * ✅ EDIT DRIVER - Works for both new & existing records
                 */
                $(document).on("click", ".editDriver", function() {
                    let id = $(this).data("id");
                    let name = $(this).data("name");
                    let vehicle_number = $(this).data("vehicle_number");
                    let phone = $(this).data("phone");
                    let no_of_trips = $(this).data("no_of_trips");
                    let balance = $(this).data("balance");

                    // ✅ Fill the modal fields
                    $("#edit_driver_id").val(id);
                    $("#edit_name").val(name);
                    $("#edit_vehicle_number").val(vehicle_number);
                    $("#edit_phone").val(phone);
                    $("#edit_no_of_trips").val(no_of_trips);
                    $("#edit_balance").val(balance);
                });

                /**
                 * ✅ UPDATE DRIVER RECORD - Works for both new & existing records
                 */
                $("#editDriverForm").submit(function(e) {
                    e.preventDefault();

                    let driverId = $("#edit_driver_id").val();
                    let formData = {
                        _token: "{{ csrf_token() }}",
                        name: $("#edit_name").val(),
                        vehicle_number: $("#edit_vehicle_number").val(),
                        phone: $("#edit_phone").val(),
                        no_of_trips: $("#edit_no_of_trips").val(),
                        balance: $("#edit_balance").val(),
                    };

                    $.ajax({
                        url: "{{ route('driver.UpdateDriver', ':id') }}".replace(":id", driverId),
                        type: "PUT",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Toastify({
                                    text: "Driver updated successfully!",
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                    className: "info",
                                    gravity: "top",
                                    position: "right",
                                    duration: 3000
                                }).showToast();

                                // ✅ Update table row dynamically
                                $("#classRow_" + driverId).find("td:nth-child(2)").text(response
                                    .data.name);
                                $("#classRow_" + driverId).find("td:nth-child(3)").text(response
                                    .data.vehicle_number);
                                $("#classRow_" + driverId).find("td:nth-child(4)").text(response
                                    .data.phone);
                                $("#classRow_" + driverId).find("td:nth-child(5)").text(response
                                    .data.no_of_trips);
                                $("#classRow_" + driverId).find("td:nth-child(6)").text(response
                                    .data.balance);

                                // ✅ Hide modal properly
                                $("#editDriverModal").modal("hide");
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");
                            } else {
                                Toastify({
                                    text: "Error updating driver.",
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

                /**
                 * ✅ ENSURE "Add New Driver" BUTTON WORKS AFTER ADDING A DRIVER
                 */
                $(document).on("click", '[data-bs-toggle="modal"]', function() {
                    let targetModal = $(this).data("bs-target");
                    $(targetModal).modal("show");
                });

            });
        </script>
    @endsection
