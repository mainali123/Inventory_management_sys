@extends('admin.admin_master')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Supplier</h4><br><br>

                            <form method="post" action="{{ route('supplier.store') }}" id="myForm">
                                @csrf
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="form-group col-sm-10">
                                        <input name="name" class="form-control" type="text">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Mobile No.</label>
                                    <div class="form-group col-sm-10">
                                        <input name="mobile_no" class="form-control" type="text">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Email</label>
                                    <div class="form-group col-sm-10">
                                        <input name="email" class="form-control" type="email">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Address</label>
                                    <div class="form-group col-sm-10">
                                        <input name="address" class="form-control" type="text">
                                    </div>
                                </div>
                                <!-- end row -->
                                <center>
                                    <input type="submit" class="btn btn-dark waves-effect waves-light" value="Add Supplier">
                                </center>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>


    <script type="text/javascript">
        // Same as the customer_add.blade.php page
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    name: {
                        required : true,
                        minlength : 2,
                        lettersonly : true,
                        number : false,
                    },
                    mobile_no: {
                        required : true,
                        minlength : 10,
                        maxlength : 10,
                        number : true,
                    },
                    email: {
                        required : true,
                        email : 'Please Enter A Valid Email',
                    },
                    address: {
                        required : true,
                        lettersonly : true,
                    },
                },
                messages :{
                    name: {
                        required : 'Please Enter Your Name',
                        minlength : 'Your Name Must Be At Least 2 Characters',
                        lettersonly : 'Please Enter Only Letters',
                        number : 'Please Enter Only Letters',
                    },
                    mobile_no: {
                        required : 'Please Enter Your Mobile Number',
                        minlength : 'Your Mobile Number Must Be 10 Digits',
                        maxlength : 'Your Mobile Number Must Be 10 Digits',
                        number : 'Please Enter Only Number',
                    },
                    email: {
                        required : 'Please Enter Your Email',
                        email : 'Please Enter A Valid Email',
                    },
                    address: {
                        required : 'Please Enter Your Address',
                        lettersonly : 'Please Enter Only Letters',
                    },
                },
                errorElement : 'span',
                errorPlacement: function (error,element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){
                    $(element).addClass('is-invalid');
                },
                unhighlight : function(element, errorClass, validClass){
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>

@endsection
