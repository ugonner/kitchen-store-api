@extends('layouts.admin')
@section('content')

<div class="row">

    <div class="col-sm-3"></div>
    <div class="col-sm-6">

        <div class="row">

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            @if ($message = Session::get('output'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
            @endif

        </div>

        <div class="row">
            <div class="col-12">
                <h3 class="card-header">Update Order</h3>
                <div class="form-group">
                    <form action="{{route('updatecart')}}" method="post">
                        @csrf


                        <div class="form-group">
                            <label for="status">Order Status</label>
                            <input type="text" id="status" name="status" value="{{old('status',$cart->status)}}" required class="@error('status') is-invalid @enderror form-control form-control-lg">
                        </div>
                        @error('status')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror


                        <div class="form-group">
                            <label for="statusnote">Note On Status</label>
                            <textarea id="statusnote" name="statusnote" required class="@error('statusnote') is-invalid @enderror form-control form-control-lg">{{old('statusnote',$cart->statusnote)}}</textarea>
                        </div>
                        @error('statusnote')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror

                        <div class="form-group">
                            <label for="ordertime">Delivery Time</label>
                            <input type="time" id="ordertime" name="ordertime" value="{{old('ordertime',$cart->ordertime)}}" required class="@error('ordertime') is-invalid @enderror form-control form-control-lg">
                        </div>
                        @error('ordertime')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror


                        <div class="form-group">
                            <label for="orderdate">Delivery Date</label>
                            <input type="date" id="orderdate" name="orderdate" value="{{old('orderdate',$cart->orderdate)}}" required class="@error('orderdate') is-invalid @enderror form-control form-control-lg">
                        </div>
                        @error('orderdate')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror


                        <div class="form-group">
                            <label for="orderaddress">Delivery Address</label>
                            <textarea id="orderaddress" name="orderaddress" required class="@error('orderaddress') is-invalid @enderror form-control form-control-lg">{{old('orderaddress',$cart->orderaddress)}}</textarea>
                        </div>
                        @error('orderaddress')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror

                        <div class="form-group">
                            <label for="ordernote">Delivery Note</label>
                            <textarea id="ordernote" name="ordernote" required class="@error('ordernote') is-invalid @enderror form-control form-control-lg">{{old('ordernote',$cart->ordernote)}}</textarea>
                        </div>
                        @error('ordernote')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror


                        <div class="form-group">
                            <label for="ordernote">Order Category</label>
                            <select id="ordernote" name="cartcategoryid" required class="@error('cartcategoryid') is-invalid @enderror form-control form-control-lg">
                                <option value="">Select Order Type</option>
                                @foreach ($cartcategories as $cc)
                                <option value="{{$cc->id}}" @if (old('cartcategoryid',$cart->cartcategoryid)== $cc->id) selected @endif title="Description" data-content="{{$cc->description}}" data-toggle="popover" data-placement="auto">{{$cc->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('cartcategoryid')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror


                        <div class="form-group">
                            <label for="carttypeid">Order Type</label>
                            <select id="carttypeid" name="carttypeid" required class="@error('carttypeid') is-invalid @enderror form-control form-control-lg">
                                <option value="">Select Order Type</option>


                                @if (!empty($carttypes))

                                @foreach ($carttypes as $cc)
                                <option value="{{$cc->id}}" @if (old('carttypeid',$cart->carttypeid)== $cc->id) selected @endif title="Description" data-content="{{$cc->description}}" data-toggle="popover" data-placement="auto">{{$cc->name}}</option>
                                @endforeach
                                @else
                                <option value="1" @if (old('carttypeid')==1) selected @endif>Instant Pay</option>
                                <option value="2" @if (old('carttypeid')==2) selected @endif>Pay On Delivery</option>
                                @endif

                            </select>
                        </div>
                        @error('carttypeid')
                        <div class="invalid-feedback">
                            {{message}}
                        </div>
                        @enderror

                        <div class="form-group">
                            <input type="hidden" name="id" value="{{$cart->id}}">
                            <button type="submit" class="btn btn-block btn-lg btn-primary">
                                Update order
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3"></div>
</div>

@endsection