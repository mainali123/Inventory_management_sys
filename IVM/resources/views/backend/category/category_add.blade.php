@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Category Page </h4><br><br>



                            <form method="post" action="{{ route('category.store') }}" id="myForm" >
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Category Name </label>
                                    <div class="form-group col-sm-10">
                                        <input name="name" class="form-control" type="text"    >
                                    </div>
                                </div>
                                <!-- end row -->

<center>
                                <input type="submit" class="btn btn-dark waves-effect waves-light" value="Add Category">
</center>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>

{{--    The javascript code is for the validation while adding the category. The validation is done using the jquery.--}}
    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({ // validation using jquery to the form with id myForm
                rules: {
                    name: {
                        required : true,    // the name field is required
                    },

                },
                messages :{
                    name: {
                        required : 'Please Enter Your Name',    // Message to be displayed if the name field is empty
                    },

                },
                errorElement : 'span',  // This is used to display the error message in the span tag
                errorPlacement: function (error,element) {  // This function is used to display the error message below the input field
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){  // This function is used to highlight the input field if the validation fails
                    $(element).addClass('is-invalid');
                },
                unhighlight : function(element, errorClass, validClass){    // This function is used to remove the highlight from the input field if the validation passes
                    $(element).removeClass('is-invalid');
                },
            });
        });

    </script>



@endsection
