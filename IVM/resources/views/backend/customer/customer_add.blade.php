@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Customer Page </h4><br><br>



                            <form method="POST" action="{{ route('customer.store') }}" id="myForm" enctype="multipart/form-data" >
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Customer Name </label>
                                    <div class="form-group col-sm-10">
                                        <input name="name" class="form-control" type="text"    >
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Customer Mobile </label>
                                    <div class="form-group col-sm-10">
                                        <input name="mobile_no" class="form-control" type="text"    >
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Customer Email </label>
                                    <div class="form-group col-sm-10">
                                        <input name="email" class="form-control" type="email"  >
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Customer Address </label>
                                    <div class="form-group col-sm-10">
                                        <input name="address" class="form-control" type="text"  >
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Customer Image </label>
                                    <div class="form-group col-sm-10">
                                        <input name="customer_image" class="form-control" type="file"  id="image">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">  </label>
                                    <div class="col-sm-10">
                                        <img id="showImage" class="rounded avatar-lg" src="{{  url('upload/no_image.jpg') }}" alt="Card image cap">
                                    </div>
                                </div>
                                <!-- end row -->




<center>
                                <input type="submit" class="btn btn-dark waves-effect waves-light" value="Add Customer">
</center>
                            </form>



                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

    <script type="text/javascript"> // This is for validation
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    name: {
                        required : true,    // Name is required
                    },
                    mobile_no: {
                        required : true,    // Mobile Number is required
                    },
                    email: {
                        required : true,    // Email is required
                    },
                    address: {
                        required : true,    // Address is required
                    },
                    customer_image: {
                        required : true,    // Image is required
                    },
                },
                messages :{
                    name: {
                        required : 'Please Enter Your Name',    // Message to display when Name is empty
                    },
                    mobile_no: {
                        required : 'Please Enter Your Mobile Number',   // Message to display when Mobile Number is empty
                    },
                    email: {
                        required : 'Please Enter Your Email',   // Message to display when Email is empty
                    },
                    address: {
                        required : 'Please Enter Your Address',  // Message to display when Address is empty
                    },
                    customer_image: {
                        required : 'Please Select Image',   // Message to display when Image is empty
                    },
                },
                errorElement : 'span',
                errorPlacement: function (error,element) {  // This is for showing error message in the bottom of the input field
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){  // This is for showing red border around input field when input field is empty
                    $(element).addClass('is-invalid');
                },
                unhighlight : function(element, errorClass, validClass){    // This is for removing red border around input field when input field is not empty
                    $(element).removeClass('is-invalid');
                },
            });
        });

    </script>


    <script type="text/javascript"> // This is for showing image before upload

        $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();  // Object of FileReader
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);    // Showing image as a preview before upload
                }
                reader.readAsDataURL(e.target.files['0']);  // Converting image to base64
            });
        });
    </script>



@endsection
