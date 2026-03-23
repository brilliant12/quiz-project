@extends('layouts.app')

@section('title', 'Create Gallery')

@section('content')
<style>
    .dataTables_paginate {
    text-align: center;
}

.dataTables_paginate .paginate_button {
    background-color: #f1f1f1;
    border: 1px solid #ddd;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
}

.dataTables_paginate .paginate_button.previous,
.dataTables_paginate .paginate_button.next {
    background-color: #007bff;
    color: white;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #0056b3;
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}
 
</style>
    <div class="container">
        <h2>Create Gallery</h2>
        <form id="galleryForm">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control">
                        <option value="media">Media</option>
                        <option value="photos">Photos</option>
                        <option value="team">Team</option>
                        <option value="about">About</option>
                    </select>

                    <div class="text-danger"></div>

                </div>

                <div class="form-group col-md-6">
                    <label for="name">Gallery Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        required>

                    <div class="text-danger"></div>

                </div>

                <div class="form-group col-md-6">
                    <label for="about_us">About Us</label>
                    <textarea name="about_us" id="about_us" class="form-control" rows="4" required></textarea>

                    <div class="text-danger"></div>

                </div>
                <div class="form-group col-md-6">
                    <label for="about_us">upload Image</label>
                    <input type="file" name="image" id="image" class="form-control" rows="4" required>
                    <div class="text-danger"></div>

                </div>

            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">Create Gallery</button>
        </form>

        <h2 class="mt-4">Gallery List</h2>
        <table id="galleryTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>About Us</th>
                    {{-- <th>Status</th> --}}
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>


@endsection
@push('script')

<script>
    let i=1;
      var table = $('#galleryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('galleries.fetch') }}",
            columns: [
                { data: 'id',
                  name: 'id',
                 render:function(){
                    return i++;
                 }
                
                },
                { data: 'category', name: 'category' },
                { data: 'name', name: 'name' },
                { data: 'about_us', name: 'about_us' },
                {
                    data: 'image',
                    name: 'image',
                    render: function(data) {
                        return data ? '<img src="/images/' + data + '" style="max-width: 100px;">' : 'N/A';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    $(document).ready(function() {
    
        $('#galleryForm').on('submit', function(e) {
            e.preventDefault();
            if ($("#galleryForm")[0].checkValidity() === false) {
     
            } else {
                $('#submitBtn').prop('disabled', true);
                $('.text-danger').text('');
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('galleries.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);
                        table.ajax.reload(); // Reload table data
                        $('#galleryForm')[0].reset();
                        $('#submitBtn').prop('disabled', false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).closest('.form-group').find('.text-danger').text(value[0]);
                                toastr.error(xhr.responseJSON.message);
                                $('#submitBtn').prop('disabled', false);
                            });
                        }
                    }
                });
            }
            $("#galleryForm").addClass("was-validated");
        });
    });

    // Ensure table variable is in the global scope
    function changeStatus(element) {
       // console.log(element.id); return false;
    // Disable the button to prevent multiple clicks
    $("#" + element.id).attr('disabled', true);

    let id = element.dataset.id;
    $.ajax({
        type: "POST",
        url: "{{ url('update-status') }}",
        data: { id },
        success: function(response) {
            if (response.status) {
                toastr.success(response.message);
                table.ajax.reload(); // Reload the DataTable
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('An error occurred. Please try again.');
        },
        complete: function() {
            // Re-enable the button after the request is complete
            $("#" + element.id).attr('disabled', false);
        }
    });
}


    function deleteData(element) {
        let status = confirm('Are you sure you want to delete?');
        let itemId = element.dataset.id;
        if (status) {
            $.ajax({
                type: "POST",
                url: "{{ url('delete-gellery') }}",
                data: { id: itemId },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        table.ajax.reload(); // Reload table data
                    }
                }
            });
        }
    }

    function editData(element) {
        console.log(element.dataset.id);
    }
</script>
@endpush
