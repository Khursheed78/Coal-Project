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
                                        <th>Driver Name</th>
                                        <th>Vehicle Number</th>
                                        <th>Phone</th>
                                        <th>No of Trips</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $driver)
                                        <tr id="classRow_{{ $driver->id }}">
                                            <td>{{ $driver->id }}</td>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->vehicle_number }}</td>
                                            <td>{{ $driver->phone }}</td>
                                            <td>{{ $driver->number_of_trips }}</td>
                                            <td>{{ $driver->balance }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm editDriver"
                                                    data-id="{{ $driver->id }}" data-name="{{ $driver->name }}"
                                                    data-vehicle_number="{{ $driver->vehicle_number }}"
                                                    data-phone="{{ $driver->phone }}" data-phone="{{ $driver->phone }}"
                                                    data-phone="{{ $driver->balance }}"
                                                    data-balance="{{ $driver->balance }}"
                                                    data-number_of_trips="{{ $driver->number_of_trips }}"
                                                    data-number_of_trips="{{ $driver->number_of_trips }}"
                                                    data-number="{{ $driver->number }}" data-bs-toggle="modal"
                                                    data-bs-target="#editDriverModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deletedriver"
                                                    data-id="{{ $driver->id }}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3" style="gap: 10px;">
                            {{-- {{ $suppliers->links('pagination::bootstrap-5') }} --}}
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
                        <form id="SupplierForm">
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
                            </div>

                            <div class="form-group">
                                <label for="number_of_trips">Number of Trips</label>
                                <input type="number" class="form-control" id="number_of_trips" name="number_of_trips"
                                    placeholder="Number of Trips">
                            </div>

                            <div class="form-group">
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

        <!-- Edit Driver Modal -->
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
                                <label for="edit_number_of_trips">Number of Trips</label>
                                <input type="number" class="form-control" id="edit_number_of_trips"
                                    name="number_of_trips" placeholder="Number of Trips">
                            </div>

                            <div class="form-group">
                                <label for="edit_balance">Balance</label>
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
                                                <button class="btn btn-primary btn-sm editDriver"
                                                    data-id="${customer.id}"
                                                    data-supplier_name="${customer.customer}"
                                                    data-contact_person="${customer.contact_person}"
                                                    data-phone="${customer.phone}"
                                                    data-email="${customer.email}"
                                                    data-balance="${customer.balance}"
                                                    data-address="${customer.address}"
                                                    data-bs-toggle="modal" data-bs-target="#editDriverModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deletedriver"
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
            // Save Data
            $(document).ready(function() {
                $('#SupplierForm').submit(function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('driver.driverStore') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: "top",
                                icon: "success",
                                title: "Driver added successfully!",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            $('#DriverModal').modal('hide');
                            $('#SupplierForm')[0].reset();

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
            $(document).on('click', '.deletedriver', function() {
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
                            url: "{{ route('driver.deleteDriver', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Driver deleted successfully.",
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
                                    text: "Failed to delete class. Try again.",
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
                $('.editDriver').on('click', function() {
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let vehicle_number = $(this).data('vehicle_number');
                    let phone = $(this).data('phone');
                    let balance = $(this).data('balance');
                    let number_of_trips = $(this).data('number_of_trips');


                    // Fill the modal fields
                    $('#edit_driver_id').val(id);
                    $('#edit_name').val(name);
                    $('#edit_vehicle_number').val(vehicle_number);
                    $('#edit_phone').val(phone);
                    $('#edit_number_of_trips').val(number_of_trips);
                    $('#edit_balance').val(balance);


                });
            });

            // Handle Update Form Submission
            $('#editDriverForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let driverid = $('#edit_driver_id').val(); // Get class ID
                let formData = {
                    _token: "{{ csrf_token() }}",
                    name: $('#edit_name').val(),
                    vehicle_number: $('#edit_vehicle_number').val(), // Corrected key name
                    phone: $('#edit_phone').val(),
                    balance: $('#edit_balance').val(),
                    number_of_trips: $('#edit_number_of_trips').val(),
                };
                $.ajax({
                    url: "{{ route('driver.UpdateDriver', ':id') }}".replace(':id',
                    driverid), // Fixed route name
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

                            // Update the table row dynamically
                            $('#classRow_' + driverid).find('td:nth-child(2)').text(response.data.name);
                            $('#classRow_' + driverid).find('td:nth-child(3)').text(response.data.vehicle_number);
                            $('#classRow_' + driverid).find('td:nth-child(4)').text(response.data.phone);
                            $('#classRow_' + driverid).find('td:nth-child(7)').text(response.data.balance);
                            $('#classRow_' + driverid).find('td:nth-child(6)').text(response.data.number_of_trips);


                            // Hide modal properly
                            $('#editDriverModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');

                            // Refresh page after 1.5 seconds
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Toastify({
                                text: "Error updating supplier.",
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
