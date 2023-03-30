@extends('admin.admin_master')
@section('admin')
    // this is the link for the jquery library
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Unit Page </h4><br><br>


                            // the form is submitted to the route unit.store
                            <form method="post" action="{{ route('unit.store') }}" id="myForm" >
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Unit Name </label>
                                    <div class="form-group col-sm-10">
                                        <input name="name" class="form-control" type="text"    >
                                    </div>
                                </div>
                                <!-- end row -->

<center>
                                // the submit button
                                <input type="submit" class="btn btn-dark waves-effect waves-light" value="Add Unit">
</center>
                            </form>



                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

    // The javascript code is for the validation while adding the unit. The validation is done using the jquery.
    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    name: {
                        required : true,
                    },

                },
                messages :{
                    name: {
                        required : 'Please Enter Your Name',
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
