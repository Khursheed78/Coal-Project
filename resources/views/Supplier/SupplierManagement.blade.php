@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Supplier</h4>
                        @if (Auth::user()->role === 'admin')
                            <!-- Button to Open Modal -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#SupplierModal">
                                        Add New Supplier
                                    </button>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="searchPhone" class="form-control" placeholder="Search by phone number">
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
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="supplierTableBody">
                                    @foreach ($suppliers as $supplier)
                                        <tr id="classRow_{{ $supplier->id }}">
                                            <td>{{ $supplier->id }}</td>
                                            <td>{{ $supplier->supplier_name }}</td>
                                            <td>{{ $supplier->contact_person }}</td>
                                            <td>{{ $supplier->phone }}</td>
                                            <td>{{ $supplier->email }}</td>
                                            <td>
                                                <div style="max-width: 200px; word-wrap: break-word; white-space: normal;">
                                                    {{ $supplier->address }}
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm editClass"
                                                    data-id="{{ $supplier->id }}"
                                                    data-supplier_name="{{ $supplier->supplier_name }}"
                                                    data-contact_person="{{ $supplier->contact_person }}"
                                                    data-phone="{{ $supplier->phone }}"
                                                    data-email="{{ $supplier->email }}"
                                                    data-address="{{ $supplier->address }}" data-bs-toggle="modal"
                                                    data-bs-target="#editSupplierModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm deleteClass"
                                                    data-id="{{ $supplier->id }}">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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

                /**
                 * ✅ SEARCH SUPPLIERS
                 * - Fetches supplier data based on phone number input.
                 */

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
                        url: "{{ route('supplier.searchByPhone') }}",
                        type: "GET",
                        data: {
                            phone: phoneNumber
                        },
                        success: function(response) {
                            if (response.success) {
                                let suppliers = response.suppliers;
                                let tableBody = $("#supplierTableBody");
                                tableBody.empty(); // Clear table before appending new data

                                if (suppliers.length === 0) {
                                    tableBody.append(
                                        `<tr><td colspan="7" class="text-center text-danger">No suppliers found!</td></tr>`
                                    );
                                } else {
                                    $.each(suppliers, function(index, supplier) {
                                        let newRow = `
                            <tr id="classRow_${supplier.id}">
                                <td>${supplier.id}</td>
                                <td>${supplier.supplier_name}</td>
                                <td>${supplier.contact_person || 'N/A'}</td>
                                <td>${supplier.phone}</td>
                                <td>${supplier.email || 'N/A'}</td>
                                <td>
                                    <div style="max-width: 200px; word-wrap: break-word; white-space: normal;">
                                        ${supplier.address || 'N/A'}
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm editClass"
                                        data-id="${supplier.id}"
                                        data-supplier_name="${supplier.supplier_name}"
                                        data-contact_person="${supplier.contact_person || ''}"
                                        data-phone="${supplier.phone}"
                                        data-email="${supplier.email || ''}"
                                        data-address="${supplier.address || ''}"
                                        data-bs-toggle="modal" data-bs-target="#editSupplierModal">
                                        <i class="fa fa-edit text-white"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteClass" data-id="${supplier.id}">
                                        <i class="fa fa-trash text-white"></i>
                                    </button>
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

                /**
                 * ✅ ADD SUPPLIER
                 * - Handles supplier creation form submission.
                 */

                $("#SupplierForm").submit(function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('supplier.store') }}",
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

                            // Close the modal properly
                            $("#SupplierModal").modal("hide");

                            // Remove modal backdrop (Fixes dark overlay issue)
                            $(".modal-backdrop").remove();
                            $("body").removeClass("modal-open");

                            // Reset the form
                            $("#SupplierForm")[0].reset();

                            // Append new supplier row to the table
                            let newRow = `
                    <tr id="classRow_${response.supplier.id}">
                        <td>${response.supplier.id}</td>
                        <td>${response.supplier.supplier_name}</td>
                        <td>${response.supplier.contact_person}</td>
                        <td>${response.supplier.phone}</td>
                        <td>${response.supplier.email}</td>
                        <td>
                            <div style="max-width: 200px; word-wrap: break-word; white-space: normal;">
                                ${response.supplier.address}
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm editClass"
                                data-id="${response.supplier.id}"
                                data-supplier_name="${response.supplier.supplier_name}"
                                data-contact_person="${response.supplier.contact_person}"
                                data-phone="${response.supplier.phone}"
                                data-email="${response.supplier.email}"
                                data-balance="${response.supplier.balance}"
                                data-address="${response.supplier.address}"
                                data-bs-toggle="modal" data-bs-target="#editSupplierModal">
                                <i class="fa fa-edit text-white"></i>
                            </button>
                            <button class="btn btn-danger btn-sm deleteClass" data-id="${response.supplier.id}">
                                <i class="fa fa-trash text-white"></i>
                            </button>
                        </td>
                    </tr>`;

                            $("tbody").append(newRow); // Append row without reloading

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
                 * ✅ DELETE SUPPLIER
                 * - Deletes a supplier record with confirmation.
                 */
                $(document).on("click", ".deleteClass", function() {
                    let id = $(this).data("id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This supplier will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('supplier.delete', ':id') }}".replace(
                                    ":id", id),
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

                                    // Remove the row smoothly
                                    $("#classRow_" + id).fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire("Error!",
                                        "Failed to delete supplier. Try again.", "error"
                                    );
                                    console.log("AJAX Error:", xhr.responseText);
                                }
                            });
                        }
                    });
                });

                /**
                 * ✅ EDIT SUPPLIER
                 * - Populates modal fields for editing supplier details.
                 */
                $(document).on("click", ".editClass", function() {
                    let id = $(this).data("id");
                    let name = $(this).data("supplier_name");
                    let contact = $(this).data("contact_person");
                    let phone = $(this).data("phone");
                    let email = $(this).data("email");
                    let balance = $(this).data("balance");
                    let address = $(this).data("address");

                    // Fill the modal fields
                    $("#edit_supplier_id").val(id);
                    $("#edit_supplier_name").val(name);
                    $("#edit_contact_person").val(contact);
                    $("#edit_phone").val(phone);
                    $("#edit_email").val(email);
                    $("#edit_balance").val(balance);
                    $("#edit_address").val(address);
                });

                /**
                 * ✅ UPDATE SUPPLIER
                 * - Handles supplier update form submission.
                 */
                $("#editSupplierForm").submit(function(e) {
                    e.preventDefault();
                    let supplierId = $("#edit_supplier_id").val();
                    let formData = {
                        _token: "{{ csrf_token() }}",
                        supplier_name: $("#edit_supplier_name").val(),
                        contact_person: $("#edit_contact_person").val(),
                        phone: $("#edit_phone").val(),
                        email: $("#edit_email").val(),
                        balance: $("#edit_balance").val(),
                        address: $("#edit_address").val(),
                    };

                    $.ajax({
                        url: "{{ route('supplier.update', ':id') }}".replace(":id", supplierId),
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
                                $("#classRow_" + supplierId).find("td:nth-child(2)").text(response
                                    .data.supplier_name);
                                $("#classRow_" + supplierId).find("td:nth-child(3)").text(response
                                    .data.contact_person);
                                $("#classRow_" + supplierId).find("td:nth-child(4)").text(response
                                    .data.phone);
                                $("#classRow_" + supplierId).find("td:nth-child(5)").text(response
                                    .data.email);
                                $("#classRow_" + supplierId).find("td:nth-child(6)").text(response
                                    .data.address);
                                $("#classRow_" + supplierId).find("td:nth-child(7)").text(response
                                    .data.balance);

                                // Hide modal properly
                                $("#editSupplierModal").modal("hide");
                                $(".modal-backdrop").remove();
                                $("body").removeClass("modal-open");

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
            });
        </script>
    @endsection
