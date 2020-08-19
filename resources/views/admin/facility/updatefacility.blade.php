@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Facility</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Groups</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Edit Facility Files</a>
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
<form method="POST" action="{{ route('editfacility') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Facility: Basic Information on Facility: Title, Organizer/Host, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-12">
            <label for="title" class="col-form-label text-md-right">{{ __('Facility Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title',$facility->title)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('title') is-invalid @enderror" required autocomplete="title" autofocus>

            @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="detail" class="label text-md-right">{{ __('Detail') }}</label>
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail',$facility->detail)}}</textarea>
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
    <h2 class="text-capitalize">Add Locations that are targetted in this facility </h2>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="address">Venue Address</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" style="height: 100px;">{{old('address',$facility->address)}}</textarea>


            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="locationid" class="col-form-label text-md-right">{{ __('Location') }}</label>
            <select id="locationid" class="form-control form-control-lg @error('locationid') is-invalid @enderror" name="locationid" required>
                <option value="0">Please Select A Location</option>
                @foreach ($locations as $location)
                <option value="{{$location->id}}" @if  (old('locationid',$facility->locationid) == $location->id) selected @endif>{{$location->name}}</option>
                @endforeach
            </select>

            @error('locationid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>
        <div class="form-group col-sm-6">
            <label for="sublocationid" class="col-form-label text-md-right">{{ __('sublocation') }}</label>
            <select id="sublocationid" class="form-control form-control-lg @error('sublocationid') is-invalid @enderror" name="sublocationid" required>
                <option value="0">Please Select A SubLocation</option>
                @foreach ($sublocations as $sublocation)
                <option value="{{$sublocation->id}}" @if  (old('sublocationid',$facility->sublocationid) == $sublocation->id) selected @endif>{{$sublocation->name}}</option>
                @endforeach
            </select>

            @error('sublocationid')
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
    <h2 class="text-capitalize">category of the facility, how can you categorize this facility </h2>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="categoryid" class="col-form-label text-md-right">{{ __('category') }}</label>
            <select id="categoryid" class="form-control form-control-lg @error('categoryid') is-invalid @enderror" name="categoryid" required>
                <option value="0">Please Select category</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}" @if  (old('categoryid') == $category->id || ($facility->categoryid == $category->id)) selected @endif>{{$category->name}}</option>
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
                <label for="facilitycategoryid" class="col-form-label text-md-right">{{ __('Facility category') }}</label>
                <select id="facilitycategoryid" class="form-control form-control-lg @error('facilitycategoryid') is-invalid @enderror" name="facilitycategoryid" required>
                    <option value="0">Please Select category</option>
                    @foreach ($facilitycategories as $category)
                    <option value="{{$category->id}}" @if  (old('facilitycategoryid',$facility->facilitycategoryid) == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>

                @error('facilitycategoryid')
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
    <h2 class="text-capitalize">Add Extra Contact Detail + What is the Certification level of the facility</h2>

    <div class="row">
        <div class="col-sm-12 form-group">
            <label for="contact" class="label text-md-right">{{ __('Contact Detail') }} <small>(Phone numbers separated by commas)</small></label>
            <textarea id="contact" class="form-control form-control-lg @error('contact') is-invalid @enderror" name="contact" autocomplete="contact" style="max-height: 200px;" placeholder="Eg 07034667861, 07023456565">{{old('contact',$facility->contact)}}</textarea>
            @error('contact')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
            @enderror
        </div>


        <div class="form-group col-sm-12">
            <label for="contacturl" class="col-form-label text-md-right">{{ __('Facility Contact URL') }}</label>
            <input id="contacturl" type="text"  name="contacturl" value="{{ old('contacturl',$facility->contacturl)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('contacturl') is-invalid @enderror" autocomplete="contacturl" autofocus>

            @error('contacturl')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col">
            <label for="rganizationcertificationlevelid" class="col-form-label text-md-right">{{ __('Facility Frequency') }}</label>
            <select id="frequency" class="form-control form-control-lg @error('facilitycertificationlevelid') is-invalid @enderror" name="facilitycertificationlevelid" required>
                <option value="0" @if(old('facilitycertificationlevelid',$facility->facilitycertificationlevelid) == '0') selected @endif>None</option>
                <option value="1" @if(old('facilitycertificationlevelid',$facility->facilitycertificationlevelid) == '1') selected @endif>Bronze</option>
                <option value="2" @if(old('facilitycertificationlevelid',$facility->facilitycertificationlevelid) == '2') selected @endif>Silver</option>
                <option value="3" @if(old('facilitycertificationlevelid',$facility->facilitycertificationlevelid) == '3') selected @endif>Gold</option>
                <option value="4" @if(old('facilitycertificationlevelid',$facility->facilitycertificationlevelid) == '4') selected @endif>Diamond</option>
            </select>

            @error('facilitycertificationlevelid')
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
    <h2 class="text-capitalize">What departments / Groups is the facility related to ?</h2>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 30px;">
            <h3>Add Facility To Associated Departments</h3>
            <div class="form-group">
                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas))}}">
            </div>

            @php
            for ($fs=0; $fs < count($focalareas); $fs++){
            for ($ufs=0; $ufs < count($facility_focalareas); $ufs++){
            if($focalareas[$fs]->id == $facility_focalareas[$ufs]->focalareaid){
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
                        <a href="" title="Related Departments" data-content="@foreach ($facility_focalareas as $efa) <div class='alert alert-primary'>{{$efa->focalareaname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();" >
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <h3>Add Facility to Associated Groups</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
            </div>


            @php
            for ($cl=0; $cl<count($clusters); $cl++){
            for ($ucs=0; $ucs<count($facility_clusters); $ucs++){
            if($clusters[$cl]->id == $facility_clusters[$ucs]->clusterid){
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
                        <a href="" title="Related groups" data-content="@foreach ($facility_clusters as $ecs) <div class='alert alert-primary'>{{$ecs->clustername}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
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
    <h2>Facility Target Locations And Upload Profile Picture (Step 5 of 5)</h2>


    <div class="row" style="padding-left: 30px;">
        <div class="col-sm-6">
            <h3 class="btn" data-toggle="collapse" data-target="#locationsdiv">Add Event to Associated Sublocations <br> <span class="material-icons">caret</span> </h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="locationscount" value="{{count($locations)}}">
            </div>


            @php
            for ($l=0; $l<count($locations); $l++){
            for ($el=0; $el<count($facility_locations); $el++){
            if($locations[$l]->id == $facility_locations[$el]->locationid){
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
                        <a href="" title="Related locations" data-content="@foreach ($facility_locations as $ac) <div class='alert alert-primary'>{{$ac->locationname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
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
                for ($ucs=0; $ucs<count($facility_sublocations); $ucs++){
                if($sublocations[$sl]->id == $facility_sublocations[$ucs]->sublocationid){
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
                        <a href="" title="Related sublocations" data-content="@foreach ($facility_sublocations as $ac) <div class='alert alert-primary'>{{$ac->sublocationname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
        </div>
    </div>




    <div class="row">
        <div class="row">
            <div class="col-sm-12" id="imageurldiv">
                <div><img src="{{asset($facility->imageurl)}}" class="img-fluid w-25" alt="image on {{$facility->title}}" /></div>
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
    <h2 class="text-capitalize">You can add interesting files to this facility (Step 5 of 5)</h2>
    <div class="row">

        <div class="row">
            <div class="col-sm-12"><div class="imgPreview"></div> </div>
        </div>
        <div class="form-group custom-file">
            <label for="images" class="custom-file-label">{{ __('Add Facility Files ')}}</label>
            <input type="file" name="facilityfiles[]" class="custom-file-input" id="images" multiple="multiple">
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
            <input type="hidden" name="facilityid" value="{{$facility->id}}" />
            <input type="hidden" name="old_imageurl" value="{{$facility->imageurl}}" />
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Update Facility') }}
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
    <h3 class="text-capitalize">groups and departments associated with this facility</h3>
    <div class="row">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_facility_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="facilityid" value="{{$facility->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($facility_focalareas)}}">
                        @for ($ef=0; $ef< count($facility_focalareas); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="facilityfocalarea{{$ef}}" class="custom-control-input" type="checkbox" name="focalarea{{$ef}}"  value="{{$facility_focalareas[$ef]->focalareaid}}" />
                                <label for="facilityfocalarea{{$ef}}" class="custom-control-label">{{ __('Leave '). $facility_focalareas[$ef]->focalareaname}}</label>
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
                <form method="POST" action="{{route('remove_facility_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="facilityid" value="{{$facility->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($facility_clusters)}}">
                        @for ($cl=0; $cl< count($facility_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$facility_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $facility_clusters[$cl]->clustername}}</label>
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
    <h3 class="text-capitalize">Files uploaded for this facility</h3>
    @if(!empty($facilityfiles))
    <div class="row">
        <div class="row">
            <h2 class="header">facilitys Files </h2>
        </div>
        <div class="row">
            @foreach ($facilityfiles as $af)
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

                <form action="{{route('deletefacilityFile')}}" method="post"  class="">
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
    <h3 class="text-capitalize">locations and sublocations associated with this facility</h3>
    <div class="row">
        <div class="col-sm-6">

            <h2>Sublocations</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_facility_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="facilityid" value="{{$facility->id}}">
                        <input type="hidden" name="sublocationscount" value="{{count($facility_sublocations)}}">
                        @for ($cl=0; $cl< count($facility_sublocations); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usersublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$facility_sublocations[$cl]->sublocationid}}" />
                                <label for="usersublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $facility_sublocations[$cl]->sublocationname}}</label>
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
                <form method="POST"  action="{{route('remove_facility_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="facilityid" value="{{$facility->id}}">
                        <input type="hidden" name="locationscount" value="{{count($facility_locations)}}">
                        @for ($ef=0; $ef< count($facility_locations); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="facilitylocation{{$ef}}" class="custom-control-input" type="checkbox" name="location{{$ef}}"  value="{{$facility_locations[$ef]->locationid}}" />
                                <label for="facilitylocation{{$ef}}" class="custom-control-label">{{ __('Leave '). $facility_locations[$ef]->locationname}}</label>
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

                        reader.onload = function(facility) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', facility.target.result).appendTo(imgPreviewPlaceholder);
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
