@extends('admin.admin_master')
@section('admin')

{{--     link to the jquery library--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Edit Product Page </h4><br><br>


{{--                             the form is submitted to the route product.update--}}
                            <form method="post" action="{{ route('product.update') }}" id="myForm" >
                                @csrf

{{--                                 the id of the product is passed to the route--}}
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Product Name </label>
                                    <div class="form-group col-sm-10">

{{--                                         the value of the product is passed to the form--}}
                                        <input name="name" value="{{ $product->name }}" class="form-control" type="text"    >
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Supplier Name </label>
                                    <div class="col-sm-10">
                                        <select name="supplier_id" class="form-select" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>

{{--                                             the supplier is fetched from the database--}}
                                            @foreach($supplier as $supp)
{{--                                                 the supplier is selected if it is the same as the supplier of the product--}}
                                                <option value="{{ $supp->id }}" {{ $supp->id == $product->supplier_id ? 'selected' : '' }}   >{{ $supp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Unit Name </label>
                                    <div class="col-sm-10">
                                        <select name="unit_id" class="form-select" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>

{{--                                             the unit is fetched from the database--}}
                                            @foreach($unit as $uni)
{{--                                                 the unit is selected if it is the same as the unit of the product--}}
                                                <option value="{{ $uni->id }}" {{ $uni->id == $product->unit_id ? 'selected' : '' }} >{{ $uni->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->



                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Category Name </label>
                                    <div class="col-sm-10">
                                        <select name="category_id" class="form-select" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>

{{--                                             the category is fetched from the database--}}
                                            @foreach($category as $cat)
{{--                                                 the category is selected if it is the same as the category of the product--}}
                                                <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

{{--                                 the button is used to submit the form--}}
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Product">
                            </form>



                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

{{--     the script is used to validate the form--}}
    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    name: {
                        required : true,
                    },
                    supplier_id: {
                        required : true,
                    },
                    unit_id: {
                        required : true,
                    },
                    category_id: {
                        required : true,
                    },
                },
                messages :{
                    name: {
                        required : 'Please Enter Your Product Name',
                    },
                    supplier_id: {
                        required : 'Please Select One Supplier',
                    },
                    unit_id: {
                        required : 'Please Select One Unit',
                    },
                    category_id: {
                        required : 'Please Select One Category',
                    },
                },
                errorElement : 'span',
                errorPlacement: function (error,element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){
                    $(element).addClass('is-invalid'); //                                                            //
                },
                unhighlight : function(element, errorClass, validClass){
                    $(element).removeClass('is-invalid');
                },
            });
        });

    </script>

@endsection
