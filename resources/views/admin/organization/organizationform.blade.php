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
<form method="POST" action="{{ route('createorganization') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Organization: Basic Information on Organization: Name, Founder/Host, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="name" class="col-form-label text-md-right">{{ __('Organization Name') }}</label>
            <input id="name" type="text"  name="name" value="{{ old('name')}}" placeholder="Eg ASA-USA" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="name" autofocus>

            @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="founder" class="col-form-label text-md-right">{{ __('Founder / Organizer') }}</label>
            <input id="founder" type="text" placeholder="Mr. Charles Umeokwonna" class="form-control form-control-lg @error('founder') is-invalid @enderror" name="founder" value="{{ old('founder') }}" required autocomplete="founder">

            @error('founder')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="detail" class="label text-md-right">{{ __('Detail') }}</label>
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail','story about organization')}}</textarea>
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
    <h2 class="text-capitalize">Add Locations that are targetted in this organization </h2>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="address">Address</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" style="height: 100px;">{{old('address','address of organization')}}</textarea>


            @error('address')
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
    <h2 class="text-capitalize">category of the organization, how can you categorize this organization </h2>
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
                <label for="organizationcategoryid" class="col-form-label text-md-right">{{ __('Organization category') }}</label>
                <select id="organizationcategoryid" class="form-control form-control-lg @error('organizationcategoryid') is-invalid @enderror" name="organizationcategoryid" required>
                    <option value="0">Please Select category</option>
                    @foreach ($organizationcategories as $category)
                    <option value="{{$category->id}}" @if  (old('categoryid') == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>

                @error('organizationcategoryid')
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
    <h2 class="text-capitalize">What is the Certification of this organization</h2>
    <div class="row">
        <div class="form-group col-sm-12">
            <label for="organizationcertificationlevelid" class="col-form-label text-md-right">{{ __('Organization Frequency') }}</label>
            <select id="organizationcertificationlevelid" class="form-control form-control-lg @error('organizationcertificationlevelid') is-invalid @enderror" name="organizationcertificationlevelid" required>
                <option value="0">None</option>
                <option value="1">Bronze</option>
                <option value="2"">Silver</option>
                <option value="3">Gold</option>
                <option value="4">Diamond</option>
            </select>

            @error('organizationcertificationlevelid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>

    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="focalareaid" class="col-form-label text-md-right">{{ __('organization peculiar focalarea') }}</label>
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

    </div>


    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="contact" class="label text-md-right">{{ __('Contact Detail') }} <small>(Phone numbers separated by commas)</small></label>
            <textarea id="contact" class="form-control form-control-lg @error('contact') is-invalid @enderror" name="contact" autocomplete="contact" style="max-height: 200px;" placeholder="Eg 07034667861, 07023456565">{{old('contact')}}</textarea>
            @error('contact')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
            @enderror
        </div>


        <div class="form-group col-sm-12">
            <label for="contacturl" class="col-form-label text-md-right">{{ __('Facility Contact URL') }}</label>
            <input id="contacturl" type="text"  name="contacturl" value="{{ old('contacturl')}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('contacturl') is-invalid @enderror" autocomplete="contacturl" autofocus>

            @error('contacturl')
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
    <h2 class="text-capitalize">What departments / Groups is the organization related to ?</h2>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 30px;">
            <h3>Add Organization To Associated Departments</h3>
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
            <h3>Add Organization to Associated Groups</h3>

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
    <h2>Organization Date, And Upload Profile Picture (Step 5 of 5)</h2>
    <div class="row">
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="dateofformation" class="">{{ __('Date Of Organization')}}</label>
                <input type="date" name="dateofformation" class="form-control form-control-lg @error('dateofformation') is-invalid @enderror " id="dateofformation" value="{{old('dateofformation',date('YYYY-mm-dd'))}}">
            </div>
            @error('dateofformation')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="timeoforganization" class="">{{ __('Time Of Organization')}}</label>
                <input type="time" name="timeoforganization" class="form-control form-control-lg @error('timeoforganization') is-invalid @enderror " id="timeoforganization" value="{{old('timeoforganization')}}">
            </div>
            @error('timeoforganization')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
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
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Stage Organization') }}
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
    // Material Select Initialization
    $(document).ready(function() {
        $('.mdb-select').materialSelect();
    });
</script>
@endsection
