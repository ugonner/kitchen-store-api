@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Donation</a>
        <!--<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Groups</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Edit Donation Files</a>
        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Edit Locations</a>-->
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
<form method="POST" action="{{ route('editdonation') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">

    <h2 class="text-capitalize">Add Donation Info,  A Brief About The Donation</h2>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="description">Reason For Donation</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" style="height: 100px;">{{old('description',$donation->description)}}</textarea>


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
                <option value="{{$focalarea->id}}" @if  (old('focalareaid',$donation->focalareaid) == $focalarea->id) selected @endif>{{$focalarea->name}}</option>
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
                <input id="amount" type="number"  name="amount" value="{{ old("amount",$donation->amount)}}" placeholder="0.00" class="form-control form-control-lg @error("amount") is-invalid @enderror" required autocomplete="amount" autofocus>

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
                <option value="N" @if (old('redeemed',$donation->redeemed)== "N") selected @endif >Not Yet</option>
                <option value="Y" @if (old('redeemed',$donation->redeemed)== "Y") selected @endif >Redeemed</option>
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
    <h2>Update Donation, (After Every Checks, Now Save Changes )</h2>



    <input type="hidden" name="donationid" value="{{$donation->id}}">
    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Save Donation') }}
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

</div>




<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">

</div>




<div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">

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

                        reader.onload = function(donation) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', donation.target.result).appendTo(imgPreviewPlaceholder);
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
