@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Supplier</h4>
                        <!-- Button to Open Modal -->
                        @if (Auth::user()->role === 'admin')
                            <!-- Button to Open Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#SupplierModal">
                                Add New Supplier
                            </button>
                        @endif

                        <table id="classTable" class="table table-bordered mt-4 table-striped">
                            <thead style="border-bottom: 2px solid black;">
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier Name</th>
                                    <th>Conatact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                    <tr id="classRow_{{ $supplier->id }}">
                                        <td>{{ $supplier->id }}</td>
                                        <td>{{ $supplier->supplier_name }}</td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->address }}</td>

                                        <td>
                                            <!-- Update Button -->
                                            <button class="btn btn-primary btn-sm editClass" data-id="{{ $supplier->id }}"
                                                data-supplier_name="{{ $supplier->supplier_name }}"
                                                data-contact_person="{{ $supplier->contact_person }}"
                                                data-phone="{{ $supplier->phone }}" data-email="{{ $supplier->email }}"
                                                data-address="{{ $supplier->address }}" data-bs-toggle="modal"
                                                data-bs-target="#editSupplierModal">
                                                <i class="fa fa-edit text-white"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button class="btn btn-primary btn-sm deleteClass"
                                                data-id="{{ $supplier->id }}">
                                                <i class="fa fa-trash text-white"></i> <!-- Red trash icon -->
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <div class="d-flex justify-content-center mt-3" style="gap: 10px;">
                            {{ $suppliers->links('pagination::bootstrap-5') }}
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
        <div class="modal fade" id="SupplierModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel">Add New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="SupplierForm">
                            @csrf
                            <div class="form-group">
                                <label for="supplier_name">Supplier Name</label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                    placeholder="Supplier Name">
                            </div>

                            <div class="form-group">
                                <label for="class_code">Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                    placeholder="Conatact Person">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone"
                                    placeholder="Phone">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Address">
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
        <!-- Edit Class Modal -->
        <!-- Edit Supplier Modal -->
        <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editSupplierForm">
                            @csrf
                            <input type="hidden" id="edit_supplier_id" name="supplier_id">

                            <div class="form-group">
                                <label for="edit_supplier_name">Supplier Name</label>
                                <input type="text" class="form-control" id="edit_supplier_name" name="supplier_name"
                                    placeholder="Supplier Name">
                            </div>

                            <div class="form-group">
                                <label for="edit_contact_person">Contact Person</label>
                                <input type="text" class="form-control" id="edit_contact_person"
                                    name="contact_person" placeholder="Contact Person">
                            </div>

                            <div class="form-group">
                                <label for="edit_phone">Phone</label>
                                <input type="number" class="form-control" id="edit_phone" name="phone"
                                    placeholder="Phone">
                            </div>

                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email"
                                    placeholder="Email">
                            </div>

                            <div class="form-group">
                                <label for="edit_address">Address</label>
                                <input type="text" class="form-control" id="edit_address" name="address"
                                    placeholder="Address">
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
                        url: "{{ route('admin.storesupplier') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: "top",
                                icon: "success",
                                title: "Supplier added successfully!",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            $('#SupplierModal').modal('hide');
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
            $(document).on('click', '.deleteClass', function() {
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
                            url: "{{ route('admin.deleteSupplier', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Supplier deleted successfully.",
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
                $('.editClass').on('click', function() {
                    let id = $(this).data('id');
                    let name = $(this).data('supplier_name');
                    let contact = $(this).data('contact_person');
                    let phone = $(this).data('phone');
                    let email = $(this).data('email');
                    let address = $(this).data('address');


                    // Fill the modal fields
                    $('#edit_supplier_id').val(id);
                    $('#edit_supplier_name').val(name);
                    $('#edit_contact_person').val(contact);
                    $('#edit_phone').val(phone);
                    $('#edit_email').val(email);
                    $('#edit_address').val(address);

                });
            });

            // Handle Update Form Submission
            $('#editSupplierForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let supplierid = $('#edit_supplier_id').val(); // Get class ID
                let formData = {
                    _token: "{{ csrf_token() }}",
                    supplier_name: $('#edit_supplier_name').val(),
                    contact_person: $('#edit_contact_person').val(), // Corrected key name
                    phone: $('#edit_phone').val(),
                    email: $('#edit_email').val(),
                    address: $('#edit_address').val(),
                };


                $.ajax({
                    url: "{{ route('admin.updateSupplier', ':id') }}".replace(':id',
                    supplierid), // Fixed route name
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Toastify({
                                text: "Supplier updated successfully!",
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                className: "info",
                                gravity: "top",
                                position: "right",
                                duration: 3000
                            }).showToast();

                            // Update the table row dynamically
                            $('#classRow_' + supplierid).find('td:nth-child(2)').text(response.data
                                .supplier_name);
                            $('#classRow_' + supplierid).find('td:nth-child(3)').text(response.data
                                .contact_person);
                            $('#classRow_' + supplierid).find('td:nth-child(4)').text(response.data.phone);
                            $('#classRow_' + supplierid).find('td:nth-child(5)').text(response.data.email);
                            $('#classRow_' + supplierid).find('td:nth-child(6)').text(response.data
                            .address);

                            // Hide modal properly
                            $('#editSupplierModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');

                            // Refresh page after 1.5 seconds
                            // setTimeout(() => location.reload(), 1500);
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
