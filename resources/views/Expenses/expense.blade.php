@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Expense</h4>
                        <!-- Button to Open Modal -->
                        @if (Auth::user()->role === 'admin')
                            <!-- Button to Open Modal -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#ExpenseModal">
                                        Add New Expense
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
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Mode of Expense</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr id="expenseRow_{{ $expense->id }}">
                                            <td>{{ $expense->id }}</td>
                                            <td>{{ $expense->title }}</td>
                                            <td>{{ $expense->amount }}</td>
                                            <td>{{ $expense->description }}</td>
                                            <td>{{ $expense->expense_date }}</td>
                                            <td>{{ $expense->supplier->supplier_name }}</td>
                                            <!-- Payment Button -->
                                            <!-- Payment Button -->
                                            <td>
                                                <button class="btn btn-primary btn-sm editDriver"
                                                    data-id="{{ $expense->id }}" data-name="{{ $expense->name }}"
                                                    data-vehicle_number="{{ $expense->vehicle_number }}"
                                                    data-phone="{{ $expense->phone }}" data-phone="{{ $expense->phone }}"
                                                    data-phone="{{ $expense->balance }}"
                                                    data-balance="{{ $expense->balance }}"
                                                    data-number_of_trips="{{ $expense->number_of_trips }}"
                                                    data-number_of_trips="{{ $expense->number_of_trips }}"
                                                    data-number="{{ $expense->number }}" data-bs-toggle="modal"
                                                    data-bs-target="#editDriverModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deletedriver" data-id="{{ $expense->id }}">
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
        <div class="modal fade" id="ExpenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="expenseModalLabel">Add New Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="ExpenseForm">
                            @csrf
                            <div class="form-group">
                                <label for="title">Expense Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter Expense Title" value="{{ old('title') }}">
                            </div>

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                    placeholder="Enter Amount" value="{{ old('amount') }}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter Description (Optional)">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="expense_date">Expense Date</label>
                                <input type="date" class="form-control" id="expense_date" name="expense_date"
                                    value="{{ old('expense_date') }}">
                            </div>

                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                </select>
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


        {{-- <!-- Payment Modal -->
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

                        <!-- Submit Payment Button -->
                        <button type="button" class="btn btn-primary mt-2" id="submitPayment">Pay Now</button>
                    </div>
                </div>
            </div>
        </div> --}}


        {{-- <!-- Edit Driver Modal -->
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
        </div> --}}

        {{-- Ajax --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script>
            $(document).ready(function() {
                $(".payment-btn").click(function() {
                    let driverName = $(this).attr("data-driver-name");
                    let balance = $(this).attr("data-balance");

                    console.log("Driver Name:", driverName);
                    console.log("Balance:", balance);

                    // Set modal values
                    $("#modalDriverName").text(driverName);
                    $("#paymentAmount").val(balance); // Show balance in input field
                });
            });
        </script> --}}
        <script>
            $(document).ready(function() {
                $(".payment-btn").click(function() {
                    let driverName = $(this).attr("data-driver-name");
                    let balance = $(this).attr("data-balance");
                    let driverId = $(this).attr("data-driver-id"); // Add driver ID in button

                    $("#modalDriverName").text(driverName);
                    $("#paymentAmount").val(balance);
                    $("#submitPayment").data("driver-id", driverId); // Store driver ID for later
                });

                $("#submitPayment").click(function() {
                    let driverId = $(this).data("driver-id");
                    let paymentAmount = $("#paymentAmount").val();

                    $.ajax({
                        url: "{{ route('update.balance') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            driver_id: driverId,
                            payment_amount: paymentAmount
                        },
                        success: function(response) {
                            if (response.success) {
                                alert("Payment successful! New balance: $" + response.new_balance +
                                    " | Trips: " + response.new_trips);
                                location.reload(); // Refresh to update balance & trips
                            } else {
                                alert("Error updating balance.");
                            }
                        }
                    });
                });
            });
        </script>


        {{-- <script>
            $(document).ready(function() {
                $('.payment-btn').on('click', function() {
                    var driverName = $(this).data('driver-name');
                    var balance = $(this).data('balance');

                    $('#modalDriverName').text(driverName);
                    $('#modalBalance').text(balance);
                });
            });
        </script> --}}

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
                $('#ExpenseForm').submit(function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('expense.store') }}", // Update with the correct route for storing expenses
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: "top",
                                icon: "success",
                                title: "Expense added successfully!",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            $('#ExpenseModal').modal(
                            'hide'); // Hide modal after successful submission
                            $('#ExpenseForm')[0].reset(); // Reset the form

                            setTimeout(function() {
                                location.reload(); // Refresh the page to update the list
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
    let id = $(this).data('id'); // Get expense ID
    let row = $(this).closest('tr'); // Find the closest table row

    Swal.fire({
        title: "Are you sure?",
        text: "This expense will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('expense.delete', ':id') }}".replace(':id', id),
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Expense deleted successfully.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Remove row smoothly
                    row.fadeOut(500, function() {
                        $(this).remove();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete Expense. Try again.",
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
                            $('#classRow_' + driverid).find('td:nth-child(3)').text(response.data
                                .vehicle_number);
                            $('#classRow_' + driverid).find('td:nth-child(4)').text(response.data.phone);
                            $('#classRow_' + driverid).find('td:nth-child(7)').text(response.data.balance);
                            $('#classRow_' + driverid).find('td:nth-child(6)').text(response.data
                                .number_of_trips);


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
