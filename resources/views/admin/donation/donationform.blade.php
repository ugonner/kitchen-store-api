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
<form method="POST" action="{{ route('createdonation') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">

    <h2 class="text-capitalize">Add Donation Info , Please We'd Appreciate A Brief About Your Donation</h2>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="description">Reason For Donation</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" style="height: 100px;">{{old('description','description of donation')}}</textarea>


            @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-6">
            <label for="focalareaid" class="col-form-label text-md-right">{{ __('donation peculiar focalarea') }}</label>
            <select id="focalareaid" class="form-control form-control-lg @error('focalareaid') is-invalid @enderror" name="focalareaid" required>
                <option value="0">Please Select A Focalarea</option>
                @foreach ($focalareas as $focalarea)
                <option value="{{$focalarea->id}}" @if  (old('focalareaid') == $focalarea->id) selected @endif>{{$focalarea->name}}</option>
                @endforeach
            </select>

            @error('focalareaid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>


        <div class="row">
            <div class="form-group col-sm-12">
                <label for="amount" class="col-form-label text-md-right">{{ __('Amount') }}</label>
                <input id="amount" type="number"  name="amount" value="{{ old("amount")}}" placeholder="0.00" class="form-control form-control-lg @error("amount") is-invalid @enderror" required autocomplete="amount" autofocus>

                @error("amount")
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>

        </div>



    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="redeemed" class="col-form-label text-md-right">{{ __('Donation Frequency') }}</label>
            <select id="redeemed" class="form-control form-control-lg @error('redeemed') is-invalid @enderror" name="redeemed" required>
                <option value="N">Not Yet</option>
                <option value="Y">Redeemed</option>
            </select>

            @error('redeemed')
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


    <h2>Basic Information on Donor: Contact is important </h2>

    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="name" class="col-form-label text-md-right">{{ __('Donor Name') }}</label>
            <input id="name" type="text"  name="name" value="{{ old('name')}}" placeholder="Mr. Ugonna Ume" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="name" autofocus>

            @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="email" class="col-form-label text-md-right">{{ __('Email') }}</label>
            <input id="email" type="text" placeholder="abc@email.com" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">

        <div class="form-group col-sm-6">
            <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
            <input id="password" type="password"  name="password" value="{{ old("password")}}" placeholder="Eg ASA-USA" class="form-control form-control-lg @error("password") is-invalid @enderror" required autocomplete="password" autofocus>

            @error("password")
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="password_confirmation" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password"  class="form-control form-control-lg @error("password_confirmation") is-invalid @enderror" name="password_confirmation" value="{{ old("password_confirmation") }}" required autocomplete="password_confirmation">

            @error("password_confirmation")
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="mobile" class="col-form-label text-md-right">{{ __('Mobile Number') }}</label>
            <input id="mobile" type="tel"  name="mobile" value="{{ old("mobile")}}" placeholder="07034667861" class="form-control form-control-lg @error("mobile") is-invalid @enderror" required autocomplete="mobile" autofocus>

            @error("mobile")
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

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
    <h2>Make Your Donation, We Deeply Appreciate Your Benevolence (Step 5 of 5)</h2>
    <div class="row">
        <div class="col-sm-12" id="imageurldiv">
            
        </div>
    </div>

    <div class="row">

        <div class="form-group custom-file">
            <label for="imageurl" class="custom-file-label">{{ __('Add A Profile Picture / Logo To Identify Donor')}}</label>
            <input type="file" name="imageurl" class="custom-file-input @error('imageurl') is-invalid @enderror " id="imageurl">
        </div>
        @error('imageurl')
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
                {{ __('Stage Donation') }}
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

                        reader.onload = function(organization) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', organization.target.result).appendTo(imgPreviewPlaceholder);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };


            $('#imageurl').on('change', function() {
                multiImgPreview(this, 'div#imageurldiv');
            });
        });



        // Material Select Initialization
        $('.mdb-select').materialSelect();
    });
</script>
<script type="text/javascript">
    // Material Select Initialization
    $(document).ready(function() {
        $('.mdb-select').materialSelect();
    });
</script>
@endsection
