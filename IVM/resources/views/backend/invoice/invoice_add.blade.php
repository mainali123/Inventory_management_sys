@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Invoice</h4><br><br>


                            <div class="row">

                                <div class="col-md-1">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">Inv No</label>
                                        <input class="form-control example-date-input" name="invoice_no" type="text" value="{{ $invoice_no }}"  id="invoice_no" readonly style="background-color:#ddd" >
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">Date</label>
                                        <input class="form-control example-date-input" value="{{ $date }}" name="date" type="date"  id="date">
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">Category Name </label>
                                        <select name="category_id" id="category_id" class="form-select select2" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>
                                            @foreach($category as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">Product Name </label>
                                        <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>

                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-1">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">Stock(Pic/Kg)</label>
                                        <input class="form-control example-date-input" name="current_stock_qty" type="text"  id="current_stock_qty" readonly style="background-color:#ddd" >
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label" style="margin-top:43px;">  </label>


                                        <i class="btn btn-secondary btn-rounded waves-effect waves-light fas fa-plus-circle addeventmore">Add More</i>
                                    </div>
                                </div>

                            </div> <!-- // end row  -->

                        </div> <!-- End card-body -->
                        <!--  ---------------------------------- -->

                        <div class="card-body">
                            <form method="post" id="myForm" action="{{ route('invoice.store') }}">
                                @csrf
                                <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Product Name </th>
                                        <th width="7%">PSC/KG</th>
                                        <th width="10%">Unit Price </th>
                                        <th width="15%">Total Price</th>
                                        <th width="7%">Action</th>

                                    </tr>
                                    </thead>

                                    <tbody id="addRow" class="addRow">

                                    </tbody>

                                    <tbody>
                                    <tr>
                                        <td colspan="4"> Discount</td>
                                        <td>
                                            <input type="text" name="discount_amount" id="discount_amount" class="form-control estimated_amount" placeholder="Discount Amount"  >
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4"> Grand Total</td>
                                        <td>
                                            <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;" >
                                        </td>
                                        <td></td>
                                    </tr>

                                    </tbody>
                                </table><br>


                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <textarea name="description" class="form-control" id="description" placeholder="Write Description Here"></textarea>
                                    </div>
                                </div><br>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label> Paid Status </label>
                                        <select name="paid_status" id="paid_status" class="form-select">
                                            <option value="">Select Status </option>
                                            <option value="full_paid">Full Paid </option>
                                            <option value="full_due">Full Due </option>
                                            <option value="partial_paid">Partial Paid </option>

                                        </select>
                                        <br>
                                        <input type="text" name="paid_amount" class="form-control paid_amount" placeholder="Enter Paid Amount" style="display:none;">
                                    </div>

                                <div class="form-group col-md-9">
                                    <label> Customer Name  </label>
                                    <select name="customer_id" id="customer_id" class="form-select">
                                        <option value="">Select Customer </option>
                                        @foreach($costomer as $cust)
                                            <option value="{{ $cust->id }}">{{ $cust->name }} - {{ $cust->mobile_no }}</option>
                                        @endforeach
                                        <option value="0">New Customer </option>
                                    </select>
                                </div>
                        </div> <!-- // end row --> <br>

                        <!-- Hide Add Customer Form -->
                        <div class="row new_customer" style="display:none">
                            <div class="form-group col-md-4">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Write Customer Name">
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Write Customer Mobile No">
                            </div>

                            <div class="form-group col-md-4">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Write Customer Email">
                            </div>
                        </div>
                        <!-- End Hide Add Customer Form -->

                        <br>

                                <div class="form-group">
                                    <center>
                                    <button type="submit" class="btn btn-dark btn-rounded waves-effect waves-light" id="storeButton"> Invoice Store</button>
                                    </center>

                                </div>

                            </form>

                        </div> <!-- End card-body -->

                    </div>
                </div> <!-- end col -->
            </div>

        </div>
    </div>

    <script id="document-template" type="text/x-handlebars-template">

        <tr class="delete_add_more_item" id="delete_add_more_item">
            <input type="hidden" name="date" value="@{{date}}">
            <input type="hidden" name="invoice_no" value="@{{invoice_no}}">


            <td>
                <input type="hidden" name="category_id[]" value="@{{category_id}}">
                @{{ category_name }}
            </td>

            <td>
                <input type="hidden" name="product_id[]" value="@{{product_id}}">
                @{{ product_name }}
            </td>

            <td>
                <input type="number" min="1" class="form-control selling_qty text-right" name="selling_qty[]" value="">
            </td>

            <td>
                <input type="number" class="form-control unit_price text-right" name="unit_price[]" value="">
            </td>

            <td>
                <input type="number" class="form-control selling_price text-right" name="selling_price[]" value="0" readonly>
            </td>

            <td>
                <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
            </td>

        </tr>

    </script>


    <script type="text/javascript">
        $(document).ready(function(){   // this function executes when the document is ready
            $(document).on("click",".addeventmore", function(){ //  Binding a click event to the ".addeventmore" element

                // Retrieving values from form inputs
                var date = $('#date').val();
                var invoice_no = $('#invoice_no').val();
                var category_id  = $('#category_id').val();
                var category_name = $('#category_id').find('option:selected').text();
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();

                // Validating form inputs
                if(date == ''){
                    $.notify("Date is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                }

                if(category_id == ''){
                    $.notify("Category is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                }
                if(product_id == ''){
                    $.notify("Product Field is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                }

                // Compiling and appending data to template
                var source = $("#document-template").html();
                var tamplate = Handlebars.compile(source);
                var data = {
                    date:date,
                    invoice_no:invoice_no,
                    category_id:category_id,
                    category_name:category_name,
                    product_id:product_id,
                    product_name:product_name

                };
                var html = tamplate(data);
                $("#addRow").append(html);
            });

            // Binding a click event to the ".removeeventmore" element
            $(document).on("click",".removeeventmore",function(event){
                $(this).closest(".delete_add_more_item").remove();
                totalAmountPrice();
            });

            // Binding keyup and click events to ".unit_price" and ".selling_qty" elements
            $(document).on('keyup click','.unit_price,.selling_qty', function(){
                var unit_price = $(this).closest("tr").find("input.unit_price").val();
                var qty = $(this).closest("tr").find("input.selling_qty").val();
                var total = unit_price * qty;
                $(this).closest("tr").find("input.selling_price").val(total);
                $('#discount_amount').trigger('keyup');
            });
            $(document).on('keyup','#discount_amount',function(){
                totalAmountPrice();
            });

            // Calculate sum of amount in invoice
            // Defining a function to calculate total amount price
            function totalAmountPrice(){
                var sum = 0;
                $(".selling_price").each(function(){
                    var value = $(this).val();
                    if(!isNaN(value) && value.length != 0){
                        sum += parseFloat(value);
                    }
                });
                var discount_amount = parseFloat($('#discount_amount').val());
                if(!isNaN(discount_amount) && discount_amount.length != 0){
                    sum -= parseFloat(discount_amount);
                }
                $('#estimated_amount').val(sum);
            }

        });


    </script>

    <script type="text/javascript">
        // Binding a change event to "#category_id" element
        $(function(){
            $(document).on('change','#category_id',function(){
                var category_id = $(this).val();
                // Ajax request to get products based on category id selected
                $.ajax({
                    url:"{{ route('get-product') }}",
                    type: "GET",
                    data:{category_id:category_id},
                    success:function(data){ // data is the response from the controller method
                        var html = '<option value="">Select Category</option>';
                        $.each(data,function(key,v){
                            html += '<option value=" '+v.id+' "> '+v.name+'</option>';
                        });
                        $('#product_id').html(html);    // Appending data to the "#product_id" element
                    }
                })
            });
        });

    </script>

    <script type="text/javascript">
        $(function(){   // this function executes when the document is ready
            $(document).on('change','#product_id',function(){
                var product_id = $(this).val();
                $.ajax({
                    url:"{{ route('check-product-stock') }}",   // this is the route to the controller method
                    type: "GET",    // this is the method to be used
                    data:{product_id:product_id},   // this is the data to be sent to the controller method
                    success:function(data){
                        $('#current_stock_qty').val(data);
                    }
                });
            });
        });

    </script>

    <script type="text/javascript">
        $(document).on('change','#paid_status', function(){ // Binding a change event to "#paid_status" element
            var paid_status = $(this).val();
            if (paid_status == 'partial_paid') {
                $('.paid_amount').show();
            }else{
                $('.paid_amount').hide();
            }
        });
        $(document).on('change','#customer_id', function(){ // Binding a change event to "#customer_id" element
            var customer_id = $(this).val();
            if (customer_id == '0') {
                $('.new_customer').show();
            }else{
                $('.new_customer').hide();
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({ // validation using jquery to the form with id myForm
                rules: {
                    paid_status: {
                        required : true,    // This field is required
                    },
                    description: {
                        required : true,     // This field is required
                    },
                    customer_id: {
                        required : true,     // This field is required
                    },
                },
                messages :{
                    paid_status: {
                        required : 'Please Select Paid Status',    // Message to be displayed if the name field is empty
                    },
                    description: {
                        required : 'Please Enter Description',    // Message to be displayed if the name field is empty
                    },
                    customer_id: {
                        required : 'Please Select Customer',    // Message to be displayed if the name field is empty
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
