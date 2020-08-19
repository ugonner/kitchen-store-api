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
<form method="POST" action="{{ route('editevent') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Event: Basic Information on Event: Title, Organizer/Host, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="title" class="col-form-label text-md-right">{{ __('Event Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title',$event->title)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="title" autofocus>

            @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="organizer" class="col-form-label text-md-right">{{ __('Organizer / Host') }}</label>
            <input id="organizer" type="text" placeholder="Eg, Igboukwu Town" class="form-control form-control-lg @error('organizer') is-invalid @enderror" name="organizer" value="{{ old('organizer',$event->organizer) }}" required autocomplete="email">

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
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail',$event->detail)}}</textarea>
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
            <textarea name="venue" class="form-control @error('venue') is-invalid @enderror" id="venue" style="height: 100px;">{{old('venue',$event->venue)}}</textarea>


            @error('venue')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
    </div>

    <div class="row" style="padding-left: 30px;">
        <div class="col-sm-6">
            <h3 class="btn" data-toggle="collapse" data-target="#locationsdiv">Add Event to Associated Sublocations <br> <span class="material-icons">caret</span> </h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="locationscount" value="{{count($locations)}}">
            </div>


            @php
            for ($l=0; $l<count($locations); $l++){
                for ($el=0; $el<count($event_locations); $el++){
                    if($locations[$l]->id == $event_locations[$el]->locationid){
                        $locations[$l]->selected = true;
                    }
                }

            }
            @endphp
            <div class="collapse" id="locationsdiv">
            @for ($ll=0; $ll< count($locations); $ll++)

            <div class="row">
                @if ((isset($locations[$ll]->selected)) && ($locations[$ll]->selected == true))
                <span></span>

                @else

                <div class="form-group custom-control custom-switch">
                    <input  id="location{{$ll}}" class="custom-control-input" type="checkbox" name="location{{$ll}}"  value="{{$locations[$ll]->id}}" />
                    <label for="location{{$ll}}" class="custom-control-label">{{ __('Join '). $locations[$ll]->name}}</label>
                </div>
                @endif
            </div>
            @endfor
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h6>
                        Original locations
                        <a href="" title="Related locations" data-content="@foreach ($event_locations as $ac) <div class='alert alert-primary'>{{$ac->locationname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <h3 class="btn" data-toggle="collapse" data-target="#sublocationsdiv">Add Event to Associated Sublocations <br> <span class="material-icons">caret</span> </h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="sublocationscount" value="{{(count($sublocations))}}">
            </div>


            <div class="collapse" id="sublocationsdiv">
            @php
            for ($sl=0; $sl<count($sublocations); $sl++){
            $sublocations[$sl]->selected = false;
                for ($ucs=0; $ucs<count($event_sublocations); $ucs++){
                    if($sublocations[$sl]->id == $event_sublocations[$ucs]->sublocationid){
                        $sublocations[$sl]->selected = true;
                    }
                }

            }
            @endphp
            @for ($sl=0; $sl< count($sublocations); $sl++)

            <div class="row">
                @if ((isset($sublocations[$sl]->selected)) && ($sublocations[$sl]->selected == true))
                <span></span>

                @else

                <div class="form-group custom-control custom-switch">
                    <input  id="sublocation{{$sl}}" class="custom-control-input" type="checkbox" name="sublocation{{$sl}}"  value="{{$sublocations[$sl]->id}}" />
                    <label for="sublocation{{$sl}}" class="custom-control-label">{{ __('Join '). $sublocations[$sl]->name}}</label>
                </div>
                @endif
            </div>
            @endfor
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>
                        Original Sublocations
                        <a href="" title="Related sublocations" data-content="@foreach ($event_sublocations as $ac) <div class='alert alert-primary'>{{$ac->sublocationname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
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
                <option value="{{$category->id}}" @if  (old('categoryid') == $category->id || ($event->categoryid == $category->id)) selected @endif>{{$category->name}}</option>
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
                    <option value="{{$category->id}}" @if  (old('eventcategoryid',$event->eventcategoryid) == $category->id) selected @endif>{{$category->name}}</option>
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
                <option value="One Time" @if(old('frequency',$event->frequency) == 'One Time') selected @endif>One Time</option>
                <option value="Daily" @if(old('frequency',$event->frequency) == 'Daily') selected @endif>Daily</option>
                <option value=Weekly0" @if(old('frequency',$event->frequency) == 'Weekly') selected @endif>Weekly</option>
                <option value="Monthly" @if(old('frequency',$event->frequency) == 'Monthly') selected @endif>Monthly</option>
                <option value="Quarterly" @if(old('frequency',$event->frequency) == 'Quarterly') selected @endif>Quarterly</option>
                <option value="Semi Annual" @if(old('frequency',$event->frequency) == 'Semi Annual') selected @endif>Semi Annual</option>
                <option value="Annual" @if(old('frequency',$event->frequency) == 'Annual') selected @endif>Annual</option>
                <option value="Biennual" @if(old('frequency',$event->frequency) == 'Biennual') selected @endif>Biannual</option>
                <option value="Perennial" @if(old('frequency',$event->frequency) == 'Perennial') selected @endif>Annual</option>
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
                <option value="N" @if(old('fee',$event->fee) == 'N') selected @endif>Free! (No Gate Fee)</option>
                <option value="Y"  @if(old('fee',$event->fee) == 'Y') selected @endif>Paid</option>
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
            <textarea id="contact" class="form-control form-control-lg @error('contact') is-invalid @enderror" name="contact" autocomplete="contact" style="max-height: 200px;" placeholder="Eg 07034667861, 07023456565">{{old('contact',$event->contact)}}</textarea>
            @error('contact')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
            @enderror
        </div>


        <div class="form-group col-sm-12">
            <label for="contacturl" class="col-form-label text-md-right">{{ __('Event Contact URL') }}</label>
            <input id="contacturl" type="text"  name="contacturl" value="{{ old('contacturl',$event->contacturl)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('contacturl') is-invalid @enderror" autocomplete="contacturl" autofocus>

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
                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas))}}">
            </div>

            @php
            for ($fs=0; $fs < count($focalareas); $fs++){
                for ($ufs=0; $ufs < count($event_focalareas); $ufs++){
                    if($focalareas[$fs]->id == $event_focalareas[$ufs]->focalareaid){
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
                        <a href="" title="Related Departments" data-content="@foreach ($event_focalareas as $efa) <div class='alert alert-primary'>{{$efa->focalareaname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();" >
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
                        for ($ucs=0; $ucs<count($event_clusters); $ucs++){
                            if($clusters[$cl]->id == $event_clusters[$ucs]->clusterid){
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
                        <a href="" title="Related groups" data-content="@foreach ($event_clusters as $ecs) <div class='alert alert-primary'>{{$ecs->clustername}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
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
    <h2>Event Date, Time And Upload Profile Picture (Step 5 of 5)</h2>
    <div class="row">
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="dateofevent" class="">{{ __('Date Of Event')}}</label>
                <input type="date" name="dateofevent" class="form-control form-control-lg @error('dateofevent') is-invalid @enderror " id="dateofevent" value="{{old('dateofevent',$event->dateofevent)}}">
            </div>
            @error('dateofevent')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 form-group">
            <div class="">
                <label for="timeofevent" class="">{{ __('Time Of Event')}}</label>
                <input type="time" name="timeofevent" class="form-control form-control-lg @error('timeofevent') is-invalid @enderror " id="timeofevent" value="{{old('timeofevent',$event->timeofevent)}}">
            </div>
            @error('timeofevent')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="row">
            <div class="col-sm-12" id="imageurldiv">
                <div><img src="{{asset($event->imageurl)}}" class="img-fluid w-25" alt="image on {{$event->title}}" /></div>
            </div>
        </div>
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
            <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="6">Continue<span class="material-icons">arrow_right_alt</span></button>
        </div>
        <div class="col text-right">
            <a class="" href="#demo" data-slide="next">
                <span class="material-icons">east</span>
            </a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <h2 class="text-capitalize">You can add your interesting files to this event (Step 5 of 5)</h2>
    <div class="row">
        <div class="row">
            <div class="col-sm-12"><div class="imgPreview"></div> </div>
        </div>
        <div class="form-group custom-file">
            <label for="images" class="custom-file-label">{{ __('Add Event Files ')}}</label>
            <input type="file" name="eventfiles[]" class="custom-file-input" id="images" multiple="multiple">
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
            <input type="hidden" name="eventid" value="{{$event->id}}" />
            <input type="hidden" name="old_imageurl" value="{{$event->imageurl}}" />
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Update Event') }}
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

</form>
</div>
</div>
</div>




<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <h3 class="text-capitalize">groups and departments associated with this event</h3>
    <div class="row">
    <div class="col-sm-6">
        <h2>Departments</h2>
        <div class="form-group">
            <form method="POST"  action="{{route('remove_event_from_groups')}}">
                @csrf
                <div>
                    <input type="hidden" name="eventid" value="{{$event->id}}">
                    <input type="hidden" name="focalareascount" value="{{count($event_focalareas)}}">
                    @for ($ef=0; $ef< count($event_focalareas); $ef++)
                    <div class="row">
                        <div class="form-group custom-control custom-switch">
                            <input  id="eventfocalarea{{$ef}}" class="custom-control-input" type="checkbox" name="focalarea{{$ef}}"  value="{{$event_focalareas[$ef]->focalareaid}}" />
                            <label for="eventfocalarea{{$ef}}" class="custom-control-label">{{ __('Leave '). $event_focalareas[$ef]->focalareaname}}</label>
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
            <form method="POST" action="{{route('remove_event_from_groups')}}">
                @csrf
                <div>
                    <input type="hidden" name="eventid" value="{{$event->id}}">
                    <input type="hidden" name="clusterscount" value="{{count($event_clusters)}}">
                    @for ($cl=0; $cl< count($event_clusters); $cl++)
                    <div class="row">
                        <div class="form-group custom-control custom-checkbox">
                            <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$event_clusters[$cl]->clusterid}}" />
                            <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $event_clusters[$cl]->clustername}}</label>
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
    <h3 class="text-capitalize">Files uploaded for this event</h3>
    @if(!empty($eventfiles))
    <div class="row">
        <div class="row">
            <h2 class="header">events Files </h2>
        </div>
        <div class="row">
            @foreach ($eventfiles as $af)
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

                <form action="{{route('deleteeventFile')}}" method="post"  class="">
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
    <h3 class="text-capitalize">locations and sublocations associated with this event</h3>
    <div class="row">
        <div class="col-sm-6">

            <h2>Sublocations</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_event_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="eventid" value="{{$event->id}}">
                        <input type="hidden" name="sublocationscount" value="{{count($event_sublocations)}}">
                        @for ($cl=0; $cl< count($event_sublocations); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usersublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$event_sublocations[$cl]->sublocationid}}" />
                                <label for="usersublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $event_sublocations[$cl]->sublocationname}}</label>
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
                <form method="POST"  action="{{route('remove_event_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="eventid" value="{{$event->id}}">
                        <input type="hidden" name="locationscount" value="{{count($event_locations)}}">
                        @for ($ef=0; $ef< count($event_locations); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="eventlocation{{$ef}}" class="custom-control-input" type="checkbox" name="location{{$ef}}"  value="{{$event_locations[$ef]->locationid}}" />
                                <label for="eventlocation{{$ef}}" class="custom-control-label">{{ __('Leave '). $event_locations[$ef]->locationname}}</label>
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
