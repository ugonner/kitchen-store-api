@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">

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


<div class="form-group">
<form method="POST" action="{{ route('createproduct') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Product: Basic Information on Product: Title And Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-12">
            <label for="title" class="col-form-label text-md-right">{{ __('Product Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title')}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="title" autofocus>

            @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="detail" class="label text-md-right">{{ __('Detail About Product') }}</label>
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail','story about product')}}</textarea>
            @error('detail')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
            @enderror
        </div>
    </div>

    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="1">Continue <span class=" material-icons">east</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>

</div>


<div class="carousel-item">
    <h2 class="text-capitalize">Add Interesting and Catchy Picture of the Product</h2>

    <div class="row">
        <div id="imageurldiv" class="col-sm-8"></div>
        <div class="col-sm-6"></div>
    </div>
    <div class="row">
        <div class="form-group custom-file">
            <label for="imageurl" class="custom-file-label">{{ __('Change Profile Picture')}}</label>
            <input type="file" name="imageurl" class="custom-file-input @error('imageurl') is-invalid @enderror " id="imageurl">
        </div>
        @error('imageurl')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <!-- Left and right controls -->
    <div class="form-row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="2">Continue <span class=" material-icons">east</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>


<div class="carousel-item">
    <h2 class="text-capitalize">category of the product, how can you categorize this product </h2>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="categoryid" class="col-form-label text-md-right">{{ __('category') }}</label>
            <select id="categoryid" class="form-control form-control-lg @error('categoryid') is-invalid @enderror" name="categoryid" required>
                <option value="0">Please Select category</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}" @if  (old('categoryid') == $category->id) selected @endif>{{$category->name}}</option>
                @endforeach
            </select>

            @error('categoryid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="productcategoryid" class="col-form-label text-md-right">{{ __('Product category') }}</label>
                <select id="productcategoryid" class="form-control form-control-lg @error('productcategoryid') is-invalid @enderror" name="productcategoryid" required>
                    <option value="0">Please Select category</option>
                    @foreach ($productcategories as $category)
                    <option value="{{$category->id}}" @if  (old('categoryid') == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>

                @error('productcategoryid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>

    </div>

    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="3"> Continue <span class="material-icons">eat</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <h2 class="text-capitalize">What is the quantity and price of the product</h2>



    <div class="row">
        <div class="form-group col-sm-12">
            <label for="quantity" class="col-form-label text-md-right">{{ __('Product Quantity In Stock') }}</label>
            <input id="quantity" type="number"  name="quantity" value="{{ old('quantity')}}" placeholder="Eg 123" class="form-control form-control-lg @error('quantity') is-invalid @enderror" autocomplete="quantity" autofocus>

            @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-12">
            <label for="price" class="col-form-label text-md-right">{{ __('Product Price') }}</label>
            <input id="price" type="number"  name="price" value="{{ old('price')}}" placeholder="Eg 1500.00" class="form-control form-control-lg @error('price') is-invalid @enderror" autocomplete="price" autofocus>

            @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-12">
            <label for="discountrate" class="col-form-label text-md-right">{{ __('Product Discount Rate') }}</label>
            <input id="discountrate" type="number"  name="discountrate" value="{{ old('discountrate')}}" placeholder="Eg 10%" class="form-control form-control-lg @error('discountrate') is-invalid @enderror" autocomplete="discountrate" autofocus>

            @error('discountrate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>

    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="4">Continue<span class="material-icons">arrow_right_alt</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <h2 class="text-capitalize">What departments / Groups is the product related to ?</h2>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 30px;">
            <h3>Add Product To Associated Departments</h3>
            <div class="form-group">
                <input type="hidden" class="form-control" name="focalareascount" value="{{count($focalareas)}}">
            </div>
            @for ($fa=0; $fa< count($focalareas); $fa++)

            @if (old('focalarea'.$fa) == $focalareas[$fa]->id)
            <div class="row">
                <div class="form-group custom-control custom-switch">
                    <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalareaid{{$fa}}"  value="{{$focalareas[$fa]->id}}" checked />
                    <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Leave '). $focalareas[$fa]->name}}</label>
                </div>
            </div>

            @else

            <div class="row">
                <div class="form-group custom-control custom-switch">
                    <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}" value="{{$focalareas[$fa]->id}}"/>
                    <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Join '). $focalareas[$fa]->name}}</label>
                </div>
            </div>
            @endif

            @endfor
        </div>

        <div class="col-sm-6">
            <h3>Add Product to Associated Groups</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
            </div>
            @for ($cl=0; $cl< count($clusters); $cl++)

            @if (old('cluster'.$cl) == $clusters[$cl]->id)
            <div class="form-group custom-control custom-checkbox">
                <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" checked />
                <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $clusters[$cl]->name}}</label>
            </div>

            @else

            <div class="form-group custom-control custom-switch">
                <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" />
                <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Join '). $clusters[$cl]->name}}</label>
            </div>
            @endif

            @endfor

        </div>
    </div>

    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="5">Continue<span class="material-icons">arrow_right_alt</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <h2>Upload Other Interesting Files/Pictures (Step 5 of 5)</h2>
    <div class="row">
        <div class="imgPreview col-sm-6"></div>
        <div class="col-sm-6"></div>
    </div>
    <div class="row">
        <div class="form-group custom-file">
            <label for="images" class="custom-file-label">{{ __('Add Other Product\'s Files')}}</label>
            <input type="file" name="productfiles[]" class="custom-file-input @error('productfiles') is-invalid @enderror " id="images" multiple>
        </div>
        @error('productfiles')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Stage Product') }}
            </button>

        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>

</div>



</div>

<div class="form-group row">

</div>
</form>
</div>
</div>
</div>
<div class="col-sm-2">

</div>
</div>

</div>

<script type="text/javascript">


    $(document).ready(function() {

        $(function() {
            // Multiple images preview with JavaScript
            var multiImgPreview = function(input, imgPreviewPlaceholder) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };


            $('#images').on('change', function() {
                multiImgPreview(this, 'div.imgPreview');
            });
            $('#imageurl').on('change', function() {
                multiImgPreview(this, 'div#imageurldiv');
            });
        });



        // Material Select Initialization
        $('.mdb-select').materialSelect();
    });
</script>
@endsection
