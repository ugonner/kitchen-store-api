@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Organization</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Groups</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Edit Organization Files</a>
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
<form method="POST" action="{{ route('editorganization') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Organization: Basic Information on Organization: Title, Organizer/Host, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="name" class="col-form-label text-md-right">{{ __('Organization Title') }}</label>
            <input id="name" type="text"  name="name" value="{{ old('name',$organization->name)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('name') is-invalid @enderror" required autocomplete="name" autofocus>

            @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="founder" class="col-form-label text-md-right">{{ __('Organizer / Host') }}</label>
            <input id="founder" type="text" placeholder="Eg, Igboukwu Town" class="form-control form-control-lg @error('founder') is-invalid @enderror" name="founder" value="{{ old('founder',$organization->founder) }}" required autocomplete="email">

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
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail',$organization->detail)}}</textarea>
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
            <label for="address">Venue Address</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" style="height: 100px;">{{old('address',$organization->address)}}</textarea>


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
                <option value="{{$category->id}}" @if  (old('categoryid') == $category->id || ($organization->categoryid == $category->id)) selected @endif>{{$category->name}}</option>
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
                    <option value="{{$category->id}}" @if  (old('organizationcategoryid',$organization->organizationcategoryid) == $category->id) selected @endif>{{$category->name}}</option>
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
    <h2 class="text-capitalize">What is the Certification level of the organization</h2>
    <div class="row">
        <div class="form-group col">
            <label for="rganizationcertificationlevelid" class="col-form-label text-md-right">{{ __('Organization Frequency') }}</label>
            <select id="frequency" class="form-control form-control-lg @error('organizationcertificationlevelid') is-invalid @enderror" name="organizationcertificationlevelid" required>
                <option value="0" @if(old('organizationcertificationlevelid',$organization->organizationcertificationlevelid) == '0') selected @endif>None</option>
                <option value="1" @if(old('organizationcertificationlevelid',$organization->organizationcertificationlevelid) == '1') selected @endif>Bronze</option>
                <option value="2" @if(old('organizationcertificationlevelid',$organization->organizationcertificationlevelid) == '2') selected @endif>Silver</option>
                <option value="3" @if(old('organizationcertificationlevelid',$organization->organizationcertificationlevelid) == '3') selected @endif>Gold</option>
                <option value="4" @if(old('organizationcertificationlevelid',$organization->organizationcertificationlevelid) == '4') selected @endif>Diamond</option>
            </select>

            @error('organizationcertificationlevelid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
            @enderror
        </div>

            <div class="form-group col-sm-12">
                <label for="focalareaid" class="col-form-label text-md-right">{{ __('organization peculiar focalarea') }}</label>
                <select id="focalareaid" class="form-control form-control-lg @error('focalareaid') is-invalid @enderror" name="focalareaid" required>
                    <option value="0">Please Select A Focalarea</option>
                    @foreach ($focalareas as $focalarea)
                    <option value="{{$focalarea->id}}" @if  (old('focalareaid',$organization->focalareaid) == $focalarea->id) selected @endif>{{$focalarea->name}}</option>
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
            <textarea id="contact" class="form-control form-control-lg @error('contact') is-invalid @enderror" name="contact" autocomplete="contact" style="max-height: 200px;" placeholder="Eg 07034667861, 07023456565">{{old('contact',$organization->contact)}}</textarea>
            @error('contact')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
            @enderror
        </div>


        <div class="form-group col-sm-12">
            <label for="contacturl" class="col-form-label text-md-right">{{ __('Facility Contact URL') }}</label>
            <input id="contacturl" type="text"  name="contacturl" value="{{ old('contacturl',$organization->contacturl)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('contacturl') is-invalid @enderror" autocomplete="contacturl" autofocus>

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
                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas))}}">
            </div>

            @php
            for ($fs=0; $fs < count($focalareas); $fs++){
            for ($ufs=0; $ufs < count($organization_focalareas); $ufs++){
            if($focalareas[$fs]->id == $organization_focalareas[$ufs]->focalareaid){
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
                        <a href="" title="Related Departments" data-content="@foreach ($organization_focalareas as $efa) <div class='alert alert-primary'>{{$efa->focalareaname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();" >
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <h3>Add Organization to Associated Groups</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
            </div>


            @php
            for ($cl=0; $cl<count($clusters); $cl++){
            for ($ucs=0; $ucs<count($organization_clusters); $ucs++){
            if($clusters[$cl]->id == $organization_clusters[$ucs]->clusterid){
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
                        <a href="" title="Related groups" data-content="@foreach ($organization_clusters as $ecs) <div class='alert alert-primary'>{{$ecs->clustername}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
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
    <h2>Organization Date, Time And Upload Profile Picture (Step 5 of 5)</h2>
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="">
                <label for="dateofformation" class="">{{ __('Date Of Organization')}}</label>
                <input type="date" name="dateofformation" class="form-control form-control-lg @error('dateofformation') is-invalid @enderror " id="dateofformation" value="{{old('dateofformation',$organization->dateofformation)}}">
            </div>
            @error('dateofformation')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="row">
            <div class="col-sm-12" id="imageurldiv">
                <div><img src="{{asset($organization->imageurl)}}" class="img-fluid w-25" alt="image on {{$organization->name}}" /></div>
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
    <h2 class="text-capitalize">You can add your interesting files to this organization (Step 5 of 5)</h2>
    <div class="row">
        <div class="row">
            <div class="col-sm-12"><div class="imgPreview"></div> </div>
        </div>
        <div class="form-group custom-file">
            <label for="images" class="custom-file-label">{{ __('Add Organization Files ')}}</label>
            <input type="file" name="organizationfiles[]" class="custom-file-input" id="images" multiple="multiple">
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
            <input type="hidden" name="organizationid" value="{{$organization->id}}" />
            <input type="hidden" name="old_imageurl" value="{{$organization->imageurl}}" />
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Update Organization') }}
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
    <h3 class="text-capitalize">groups and departments associated with this organization</h3>
    <div class="row">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_organization_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="organizationid" value="{{$organization->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($organization_focalareas)}}">
                        @for ($ef=0; $ef< count($organization_focalareas); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="organizationfocalarea{{$ef}}" class="custom-control-input" type="checkbox" name="focalarea{{$ef}}"  value="{{$organization_focalareas[$ef]->focalareaid}}" />
                                <label for="organizationfocalarea{{$ef}}" class="custom-control-label">{{ __('Leave '). $organization_focalareas[$ef]->focalareaname}}</label>
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
                <form method="POST" action="{{route('remove_organization_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="organizationid" value="{{$organization->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($organization_clusters)}}">
                        @for ($cl=0; $cl< count($organization_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$organization_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $organization_clusters[$cl]->clustername}}</label>
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
    <h3 class="text-capitalize">Files uploaded for this organization</h3>
    @if(!empty($organizationfiles))
    <div class="row">
        <div class="row">
            <h2 class="header">organizations Files </h2>
        </div>
        <div class="row">
            @foreach ($organizationfiles as $af)
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

                <form action="{{route('deleteorganizationFile')}}" method="post"  class="">
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
    <h3 class="text-capitalize">locations and sublocations associated with this organization</h3>
    <div class="row">
        <div class="col-sm-6">

            <h2>Sublocations</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_organization_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="organizationid" value="{{$organization->id}}">
                        <input type="hidden" name="sublocationscount" value="{{count($organization_sublocations)}}">
                        @for ($cl=0; $cl< count($organization_sublocations); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usersublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$organization_sublocations[$cl]->sublocationid}}" />
                                <label for="usersublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $organization_sublocations[$cl]->sublocationname}}</label>
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
                <form method="POST"  action="{{route('remove_organization_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="organizationid" value="{{$organization->id}}">
                        <input type="hidden" name="locationscount" value="{{count($organization_locations)}}">
                        @for ($ef=0; $ef< count($organization_locations); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="organizationlocation{{$ef}}" class="custom-control-input" type="checkbox" name="location{{$ef}}"  value="{{$organization_locations[$ef]->locationid}}" />
                                <label for="organizationlocation{{$ef}}" class="custom-control-label">{{ __('Leave '). $organization_locations[$ef]->locationname}}</label>
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

                        reader.onload = function(organization) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', organization.target.result).appendTo(imgPreviewPlaceholder);
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
