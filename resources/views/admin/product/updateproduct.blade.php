@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Event</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Groups</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Edit Event Files</a>
        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Edit Locations</a>
    </div>
</div>
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


<div class="tab-content" id="v-pills-tabContent">

<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
<div class="row">






<div class="form-group">
<form method="POST" action="{{ route('editproduct') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Product: Basic Information on Product: Title And Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-12">
            <label for="title" class="col-form-label text-md-right">{{ __('Product Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title',$product->title)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="title" autofocus>

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
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail',$product->detail)}}</textarea>
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
                <option value="{{$category->id}}" @if  (old('categoryid',$product->categoryid) == $category->id || ($product->categoryid == $category->id)) selected @endif>{{$category->name}}</option>
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
                <label for="productcategoryid" class="col-form-label text-md-right">{{ __('Event category') }}</label>
                <select id="productcategoryid" class="form-control form-control-lg @error('productcategoryid') is-invalid @enderror" name="productcategoryid" required>
                    <option value="0">Please Select category</option>
                    @foreach ($productcategories as $category)
                    <option value="{{$category->id}}" @if  (old('productcategoryid',$product->productcategoryid) == $category->id) selected @endif>{{$category->name}}</option>
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
            <input id="quantity" type="number"  name="quantity" value="{{ old('quantity',$product->quantity)}}" placeholder="Eg 123" class="form-control form-control-lg @error('quantity') is-invalid @enderror" autocomplete="quantity" autofocus>

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
            <input id="price" type="number"  name="price" value="{{ old('price',$product->price)}}" placeholder="Eg 1500.00" class="form-control form-control-lg @error('price') is-invalid @enderror" autocomplete="price" autofocus>

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
            <input id="discountrate" type="number"  name="discountrate" value="{{ old('discountrate',$product->discountrate)}}" placeholder="Eg 10%" class="form-control form-control-lg @error('discountrate') is-invalid @enderror" autocomplete="discountrate" autofocus>

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
            <h3>Add Event To Associated Departments</h3>
            <div class="form-group">
                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas))}}">
            </div>

            @php
            for ($fs=0; $fs < count($focalareas); $fs++){
                for ($ufs=0; $ufs < count($product_focalareas); $ufs++){
                    if($focalareas[$fs]->id == $product_focalareas[$ufs]->focalareaid){
                        $focalareas[$fs]['selected'] = true;
                    }
                }

            }
            @endphp
            @for ($fa=0; $fa< count($focalareas); $fa++)
            <div class="row">
                @if ((isset($focalareas[$fa]['selected'])) && ($focalareas[$fa]['selected'] == true))
                <span></span>
                @else
                <div class="form-group custom-control custom-switch">
                    <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}" value="{{$focalareas[$fa]->id}}"/>
                    <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Join '). $focalareas[$fa]->name}}</label>
                </div>
                @endif
            </div>
            @endfor

            <div class="row">
                <div class="col-sm-12">
                    <h6>
                        Original Departments
                        <a href="" title="Related Departments" data-content="@foreach ($product_focalareas as $efa) <div class='alert alert-primary'>{{$efa->focalareaname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();" >
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <h3>Add Event to Associated Groups</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
            </div>


            @php
            for ($cl=0; $cl<count($clusters); $cl++){
                for ($ucs=0; $ucs<count($product_clusters); $ucs++){
                    if($clusters[$cl]->id == $product_clusters[$ucs]->clusterid){
                        $clusters[$cl]['selected'] = true;
                    }
                }

            }
            @endphp
            @for ($cl=0; $cl< count($clusters); $cl++)

            <div class="row">
                @if ((isset($clusters[$cl]['selected'])) && ($clusters[$cl]['selected'] == true))
                <span></span>

                @else

                <div class="form-group custom-control custom-switch">
                    <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" />
                    <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Join '). $clusters[$cl]->name}}</label>
                </div>
                @endif
            </div>
            @endfor
            <div class="row">
                <div class="col-sm-12">
                    <h6>
                        Original Groups
                        <a href="" title="Related groups" data-content="@foreach ($product_clusters as $ecs) <div class='alert alert-primary'>{{$ecs->clustername}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>
                </div>
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
            <input type="hidden" name="productid" value="{{$product->id}}" />
            <input type="hidden" name="old_imageurl" value="{{$product->imageurl}}" />
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
                {{ __('Update Product') }}
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




<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <h3 class="text-capitalize">groups and departments associated with this product</h3>
    <div class="row">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_product_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="productid" value="{{$product->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($product_focalareas)}}">
                        @for ($ef=0; $ef< count($product_focalareas); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="productfocalarea{{$ef}}" class="custom-control-input" type="checkbox" name="focalarea{{$ef}}"  value="{{$product_focalareas[$ef]->focalareaid}}" />
                                <label for="productfocalarea{{$ef}}" class="custom-control-label">{{ __('Leave '). $product_focalareas[$ef]->focalareaname}}</label>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Leave Departments'}}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <h2>Groups</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_product_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="productid" value="{{$product->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($product_clusters)}}">
                        @for ($cl=0; $cl< count($product_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$product_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $product_clusters[$cl]->clustername}}</label>
                            </div>

                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Leave Groups'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
    <h3 class="text-capitalize">Files uploaded for this product</h3>
    @if(!empty($productfiles))
    <div class="row">
        <div class="row">
            <h2 class="header">products Files </h2>
        </div>
        <div class="row">
            @foreach ($productfiles as $af)
            <div class="col-sm-3">

                <div>
                    <button class="btn bg-transparent" title="Description" data-content="{{$af->description}}" data-placement="auto" data-toggle="popover" data-trigger="focus" role="popover-toggle"><span class="material-icons">more_vert</span> </button>
                </div>
                @if (preg_match('/^image*/i', $af->filetype))
                <img src="{{asset($af->fileurl)}}" class="img-fluid" />
                @else
                <button class="btn btn-block bg-transparent">{{$af->description}}</button>
                <form action="" method="post" class="">
                    @csrf
                    <input type="hidden" name="fileid" value="{{$af->id}}">
                    <input type="hidden" name="objectid" value="{{$af->objectid}}">
                    <button type="submit" class="btn bg-transparent"><span class="material-icons">download</span> download</button>
                </form>
                @endif

                <form action="{{route('deleteproductFile')}}" method="post"  class="">
                    @csrf
                    <input type="hidden" name="fileid" value="{{$af->id}}">
                    <input type="hidden" name="objectid" value="{{$af->objectid}}">
                    <input type="hidden" name="fileurl" value="{{$af->fileurl}}">
                    <button type="submit" class="btn bg-transparent"><span class="material-icons">delete</span> delete</button>

                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>




<div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
    <h3 class="text-capitalize">locations and sublocations associated with this product</h3>
    <div class="row">
        <div class="col-sm-6">

            <h2>Sublocations</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_product_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="productid" value="{{$product->id}}">
                        <input type="hidden" name="sublocationscount" value="{{count($product_sublocations)}}">
                        @for ($cl=0; $cl< count($product_sublocations); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usersublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$product_sublocations[$cl]->sublocationid}}" />
                                <label for="usersublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $product_sublocations[$cl]->sublocationname}}</label>
                            </div>

                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Eject Sublocations'}}</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-sm-6">
            <h2>locations</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_product_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="productid" value="{{$product->id}}">
                        <input type="hidden" name="locationscount" value="{{count($product_locations)}}">
                        @for ($ef=0; $ef< count($product_locations); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="productlocation{{$ef}}" class="custom-control-input" type="checkbox" name="location{{$ef}}"  value="{{$product_locations[$ef]->locationid}}" />
                                <label for="productlocation{{$ef}}" class="custom-control-label">{{ __('Leave '). $product_locations[$ef]->locationname}}</label>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Eject locations'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

                        reader.onload = function(product) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', product.target.result).appendTo(imgPreviewPlaceholder);
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
