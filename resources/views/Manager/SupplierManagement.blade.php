@extends('layouts.main')

@section('main-section')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Supplier</h4>
                        <!-- Button to Open Modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SupplierModal">
                            Add New Supplier
                        </button>
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
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
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr id="classRow_{{ $supplier->id }}">
                                            <td>{{ $supplier->id }}</td>
                                            <td>{{ $supplier->supplier_name }}</td>
                                            <td>{{ $supplier->contact_person }}</td>
                                            <td>{{ $supplier->phone }}</td>
                                            <td>{{ $supplier->email }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm editClass" data-id="{{ $supplier->id }}" data-bs-toggle="modal" data-bs-target="#editClassModal">
                                                    <i class="fa fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm deleteClass" data-id="{{ $supplier->id }}">
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
        <!-- Edit Class Modal -->
        <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editClassModalLabel">Edit Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editClassForm">
                            @csrf
                            <input type="hidden" id="edit_class_id" name="class_id">

                            <div class="form-group">
                                <label for="edit_class_name">Class Name</label>
                                <input type="text" class="form-control" id="edit_class_name" name="name"
                                    placeholder="Class Name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit_class_code">Class Code</label>
                                <input type="text" class="form-control" id="edit_class_code" name="class_code"
                                    placeholder="C1, C2, etc.">
                                @error('class_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit_section">Section</label>
                                <input type="text" class="form-control" id="edit_section" name="section"
                                    placeholder="A, B, C">
                                @error('section')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit_totalstudent">Total Student</label>
                                <input type="number" class="form-control" id="edit_totalstudent" name="total_students">
                                @error('total_students')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit_class_timing">Class Timing</label>
                                <select class="form-select" id="edit_class_timing" name="class_timing">
                                    <option value="" disabled>Select Class Timing</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Evening">Evening</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="edit_status">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="" disabled>Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
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

                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Class deleted successfully.",
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
                    let className = $(this).data('class_name');
                    let classCode = $(this).data('class_code');
                    let section = $(this).data('section');
                    let totalstudent = $(this).data('total_students');
                    let classTiming = $(this).data('class_timing');
                    let status = $(this).data('status');

                    // Fill the modal fields
                    $('#edit_class_id').val(id);
                    $('#edit_class_name').val(className);
                    $('#edit_class_code').val(classCode);
                    $('#edit_section').val(section);
                    $('#edit_totalstudent').val(totalstudent);
                    $('#edit_class_timing').val(classTiming);
                    $('#edit_status').val(status);
                });
            });

            // Handle Update Form Submission
            $('#editClassForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let classId = $('#edit_class_id').val(); // Get class ID
                let formData = {
                    _token: "{{ csrf_token() }}",
                    class_name: $('#edit_class_name').val(),
                    class_code: $('#edit_class_code').val(),
                    section: $('#edit_section').val(),
                    total_students: $('#edit_totalstudent').val(),
                    class_timing: $('#edit_class_timing').val(),
                    status: $('#edit_status').val()
                };

                $.ajax({

                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Toastify({
                                text: "Class updated successfully!",
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                className: "info",
                                gravity: "top",
                                position: "right",
                                duration: 3000
                            }).showToast();

                            // Update the table row dynamically
                            $('#classRow_' + classId).find('td:nth-child(2)').text(response.data
                                .class_name);
                            $('#classRow_' + classId).find('td:nth-child(3)').text(response.data
                                .class_code);
                            $('#classRow_' + classId).find('td:nth-child(4)').text(response.data.section ??
                                'N/A');
                            $('#classRow_' + classId).find('td:nth-child(5)').text(response.data
                                .total_students);
                            $('#classRow_' + classId).find('td:nth-child(6)').text(response.data
                                .class_timing);
                            $('#classRow_' + classId).find('td:nth-child(7)').text(response.data.status);

                            // Hide modal and refresh after update
                            $('#editClassModal').modal('hide');
                            setTimeout(() => location.reload(), 3000);
                        } else {
                            Toastify({
                                text: "Error updating class.",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                className: "error",
                                gravity: "top",
                                position: "right",
                                duration: 5000
                            }).showToast();
                        }
                    },
                    error: function(xhr) {
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
