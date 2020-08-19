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
<form method="POST" action="{{ route('createevent') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Event: Basic Information on Event: Title, Organizer/Host, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="title" class="col-form-label text-md-right">{{ __('Event Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title')}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="title" autofocus>

            @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="organizer" class="col-form-label text-md-right">{{ __('Organizer / Host') }}</label>
            <input id="organizer" type="text" placeholder="Eg, Igboukwu Town" class="form-control form-control-lg @error('organizer') is-invalid @enderror" name="organizer" value="{{ old('email') }}" required autocomplete="email">

            @error('organizer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="detail" class="label text-md-right">{{ __('Detail') }}</label>
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail','story about event')}}</textarea>
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
    <h2 class="text-capitalize">Add Locations that are targetted in this event </h2>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="venue">Venue Address</label>
            <textarea name="venue" class="form-control @error('venue') is-invalid @enderror" id="venue" style="height: 100px;">{{old('venue','venue of event')}}</textarea>


            @error('venue')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
    </div>

    <div class="row" style="padding-left: 30px;">
        <div class="col-sm-6">
            <h3>Add Event To Associated Location</h3>
            <div class="form-group">
                <input type="hidden" class="form-control" name="locationscount" value="{{count($locations)}}">
            </div>
            @for ($fa=0; $fa< count($locations); $fa++)
            <div class="row">
                @if  (old('location'.$fa) == $locations[$fa]->id)
                <div class="row">
                    <div class="form-group custom-control custom-switch">
                        <input  id="location{{$fa}}" class="custom-control-input" type="checkbox" name="locationid{{$fa}}"  value="{{$locations[$fa]->id}}" checked />
                        <label for="location{{$fa}}" class="custom-control-label">{{ __('Leave '). $locations[$fa]->name}}</label>
                    </div>
                </div>

                @else

                <div class="row">
                    <div class="form-group custom-control custom-switch">
                        <input  id="location{{$fa}}" class="custom-control-input" type="checkbox" name="location{{$fa}}" value="{{$locations[$fa]->id}}"/>
                        <label for="location{{$fa}}" class="custom-control-label">{{ __('Join '). $locations[$fa]->name}}</label>
                    </div>
                </div>
                @endif
            </div>
            @endfor
        </div>

        <div class="col-sm-6">
            <h3>Add Event to Associated Sublocationss</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="sublocationscount" value="{{count($sublocations)}}">
            </div>
            @for ($cl=0; $cl< count($sublocations); $cl++)
            <div class="row">
                @if  (old('sublocation'.$cl) == $sublocations[$cl]->id)
                <div class="form-group custom-control custom-checkbox">
                    <input  id="sublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$sublocations[$cl]->id}}" checked />
                    <label for="sublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $sublocations[$cl]->name}}</label>
                </div>

                @else

                <div class="form-group custom-control custom-switch">
                    <input  id="sublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$sublocations[$cl]->id}}" />
                    <label for="sublocation{{$cl}}" class="custom-control-label">{{ __('Join '). $sublocations[$cl]->name}}</label>
                </div>
                @endif
            </div>
            @endfor

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
    <h2 class="text-capitalize">category of the event, how can you categorize this event </h2>
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
                    <label for="eventcategoryid" class="col-form-label text-md-right">{{ __('Event category') }}</label>
                    <select id="eventcategoryid" class="form-control form-control-lg @error('eventcategoryid') is-invalid @enderror" name="eventcategoryid" required>
                        <option value="0">Please Select category</option>
                        @foreach ($eventcategories as $category)
                        <option value="{{$category->id}}" @if  (old('categoryid') == $category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>

                    @error('eventcategoryid')
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
    <h2 class="text-capitalize">What is the frequency of occurance of the event</h2>
    <div class="row">
        <div class="form-group col">
            <label for="frequency" class="col-form-label text-md-right">{{ __('Event Frequency') }}</label>
            <select id="frequency" class="form-control form-control-lg @error('frequency') is-invalid @enderror" name="frequency" required>
                <option value="One Time">One Time</option>
                <option value="Daily">Daily</option>
                <option value=Weekly0">Weekly</option>
                <option value="Monthly">Monthly</option>
                <option value="Quarterly">Quarterly</option>
                <option value="Semi Annual">Semi Annual</option>
                <option value="Annual">Annual</option>
            </select>

            @error('frequency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
        <div class="form-group col">
            <label for="fee" class="col-form-label text-md-right">{{ __('Is This A Paid Event') }}</label>
            <select id="fee" class="form-control form-control-lg @error('fee') is-invalid @enderror" name="fee" required>
                <option value="N">Free! (No Gate Fee)</option>
                <option value="Y">Paid</option>
            </select>

            @error('fee')
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
            <label for="contacturl" class="col-form-label text-md-right">{{ __('Event Contact URL') }}</label>
            <input id="contacturl" type="text"  name="contacturl" value="{{ old('contacturl')}}" placeholder="http://www.abc.com" class="form-control form-control-lg @error('contacturl') is-invalid @enderror" autocomplete="contacturl" autofocus>

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
    <h2 class="text-capitalize">What departments / Groups is the event related to ?</h2>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 30px;">
            <h3>Add Event To Associated Departments</h3>
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
            <h3>Add Event to Associated Groups</h3>

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
    <h2>Event Date, Time And Upload Profile Picture (Step 5 of 5)</h2>
    <div class="row">
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="dateofevent" class="">{{ __('Date Of Event')}}</label>
                <input type="date" name="dateofevent" class="form-control form-control-lg @error('dateofevent') is-invalid @enderror " id="dateofevent" value="{{old('dateofevent',date('YYYY-mm-dd'))}}">
            </div>
            @error('dateofevent')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="timeofevent" class="">{{ __('Time Of Event')}}</label>
                <input type="time" name="timeofevent" class="form-control form-control-lg @error('timeofevent') is-invalid @enderror " id="timeofevent" value="{{old('timeofevent')}}">
            </div>
            @error('timeofevent')
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
                {{ __('Stage Event') }}
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
