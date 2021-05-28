<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{csrf_token()}}" />
    
    <title>Starter Template for Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
        <h2 class="text-center my-2">CSV Data Manipulation</h2>
        <button class="btn btn-primary pointer" id="add-btn">Add Data</button>
        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">

                </tbody>
            </table>
        </div>
    </main><!-- /.container -->

    <!-- Modal -->

    <div class="modal" id="formModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form">
                        <div class="form-group">
                            <label for="inputId" class="form-label">ID <small class="text-danger">*</small></label>
                            <input type="number" id="inputId" name="id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="form-label">Name <small class="text-danger">*</small></label>
                            <input type="text" id="inputName" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress" class="form-label">Address <small class="text-danger">*</small></label>
                            <input type="text" id="inputAddress" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputMobile" class="form-label">Mobile <small class="text-danger">*</small></label>
                            <input type="number" id="inputMobile" name="mobile" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="form-label">Email <small class="text-danger">*</small></label>
                            <input type="email" id="inputEmail" name="email" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary submit-btn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            initRows();

            $('#add-btn').on('click', function() {
                $('#modalLabel').text('Add Row')
                $('.submit-btn').attr('onclick', 'createRow()')
                $('#formModal').modal('toggle');

            })
        })

        function initRows() {
            $.ajax({  
                type: 'GET',  
                url: "http://localhost:8000/api/data",  
                contentType: "application/json; charset=utf-8",  
                dataType: 'json',  
                headers: {  
                    'Accept': 'application/json',  
                    'Content-Type': 'application/json',  
                },  
                success: function(response) {  
                    if (response.error == false && response.code == 200) {
                        const data = response.results;
                        $rows = '';

                        data.forEach(function(ele, index) {
                            $rows+= `
                                <tr>
                                    <th scope="row">${ele.id}</th>
                                    <td>${ele.full_name}</td>
                                    <td>${ele.address}</td>
                                    <td>${ele.mobile}</td>
                                    <td>${ele.email}</td>
                                    <td>
                                        <button class="btn btn-warning" onclick="editRow(${index + 1})">Edit</button>
                                        <button class="btn btn-danger" onclick="deleteRow(${index + 1})">Delete</button>
                                    </td>
                                </tr>
                            `;
                        })
                        
                        $('#table-body').html($rows);
                    }
                },  
                error: function(XMLHttpRequest, textStatus, errorThrown) {  
                    alert('error');  
                }  
            });  
        }

        function createRow() {
            let formData = {
                id: $('#inputId').val(),
                full_name: $('#inputName').val(),
                address: $('#inputAddress').val(),
                mobile: $('#inputMobile').val(),
                email: $('#inputEmail').val(),
            };

            $.ajax({  
                type: 'POST',  
                url: "http://localhost:8000/api/data/",  
                contentType: "application/json; charset=utf-8",  
                data: JSON.stringify(formData),
                dataType: 'json',  
                headers: {  
                    'Accept': 'application/json',  
                    'Content-Type': 'application/json',  
                },
                success: function(response) {  
                    if (response.error == false && response.code == 200) {
                        alert('Row Added');
                        initRows();
                        $('#formModal').modal('toggle');
                        $("input[type=text], input[type=number], input[type=email]").val("");
                    }
                },  
                error: function(XMLHttpRequest, textStatus, errorThrown) {  
                    alert('error');  
                }  
            });  
        }

        function editRow(index) {
            $.ajax({  
                type: 'GET',  
                url: "http://localhost:8000/api/data/" + index,  
                contentType: "application/json; charset=utf-8",  
                dataType: 'json',  
                headers: {  
                    'Accept': 'application/json',  
                    'Content-Type': 'application/json',  
                },  
                success: function(response) {  
                    if (response.error == false && response.code == 200) {
                        const data = response.results;
                        $('#modalLabel').text('Edit Row')
                        $('#inputId').val(data.id)
                        $('#inputName').val(data.full_name)
                        $('#inputAddress').val(data.address)
                        $('#inputMobile').val(data.mobile)
                        $('#inputEmail').val(data.email)

                        $('.submit-btn').attr('onclick', 'updateRow('+ index +')')
                        $('#formModal').modal('toggle');
                    }
                },  
                error: function(XMLHttpRequest, textStatus, errorThrown) {  
                    alert('error');
                }  
            });  
        }

        function updateRow(index) {
            let formData = {
                id: $('#inputId').val(),
                full_name: $('#inputName').val(),
                address: $('#inputAddress').val(),
                mobile: $('#inputMobile').val(),
                email: $('#inputEmail').val(),
            };

            $.ajax({  
                type: 'PUT',  
                url: "http://localhost:8000/api/data/" + index,  
                contentType: "application/json; charset=utf-8",  
                data: JSON.stringify(formData),
                dataType: 'json',  
                headers: {  
                    'Accept': 'application/json',  
                    'Content-Type': 'application/json',  
                    // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(response) {  
                    if (response.error == false && response.code == 200) {
                        alert('Updated Row');
                        initRows();
                        $('#formModal').modal('toggle');
                        $("input[type=text], input[type=number], input[type=email]").val("");
                    }
                },  
                error: function(XMLHttpRequest, textStatus, errorThrown) {  
                    alert('error');  
                }  
            });  
        }

        function deleteRow(index) {
            $.ajax({  
                type: 'DELETE',  
                url: "http://localhost:8000/api/data/" + index,  
                contentType: "application/json; charset=utf-8",  
                dataType: 'json',  
                headers: {  
                    'Accept': 'application/json',  
                    'Content-Type': 'application/json',  
                },  
                success: function(response) {  
                    if (response.error == false && response.code == 200) {
                        alert('Deleted Row');
                        initRows();
                    }
                },  
                error: function(XMLHttpRequest, textStatus, errorThrown) {  
                    alert('error');  
                }  
            });  
        }
    </script>

</html>
