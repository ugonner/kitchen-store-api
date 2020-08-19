@extends('layouts.admin')

@section('content')

<div class="container">
<div class="row">
<div class="col-sm-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Advert</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Groups</a>
        <!--<a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Edit Advert Files</a>
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
<form method="POST" action="{{ route('editadvert') }}" enctype="multipart/form-data">
@csrf

<div id="demo" class="carousel slide" data-interval="false">

<!-- The slideshow -->
<div class="carousel-inner">
<div class="carousel-item active">
    <h2>Advert: Basic Information on Advert: Title, Story </h2>

    <div class="form-row">
        <div class="form-group col-sm-12">
            <label for="title" class="col-form-label text-md-right">{{ __('Advert Title') }}</label>
            <input id="title" type="text"  name="title" value="{{ old('title',$advert->title)}}" placeholder="Eg New Yam Festival" class="form-control form-control-lg @error('title') is-invalid @enderror" required autocomplete="title" autofocus>

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
            <textarea id="detail" class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail" required autocomplete="detail" style="height: 200px;">{{old('detail',$advert->detail)}}</textarea>
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

    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-capitalize">This is the image uploaded for this advert, can be changed in next steps</h2>
            <div><img src="{{asset($advert->imageurl)}}" class="img-fluid rounded" alt="image on {{$advert->title}}"></div>
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
    <h2 class="text-capitalize">category of the advert, how can you categorize this advert </h2>
    <div class="row">
        <div class="form-group col-sm-12">
            <label for="categoryid" class="col-form-label text-md-right">{{ __('category') }}</label>
            <select id="categoryid" class="form-control form-control-lg @error('categoryid') is-invalid @enderror" name="categoryid" required>
                <option value="0">Please Select category</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}" @if  (old('categoryid') == $category->id || ($advert->categoryid == $category->id)) selected @endif>{{$category->name}}</option>
                @endforeach
            </select>

            @error('categoryid')
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
        <h2 class="text-capitalize">What is the Placement, Department and Group of this advert </h2>
        <div class="row">
            <div class="form-group col-sm-12">
                <label for="placementid" class="col-form-label text-md-right">{{ __('Advert Frequency') }}</label>
                <select id="placementid" class="form-control form-control-lg @error('placementid') is-invalid @enderror" name="placementid" required>
                    <option value="">None</option>
                    <option value="1" @if(old('placementid',$advert->placementid) == '1') selected @endif>Bome Page Top</option>
                    <option value="2" @if(old('placementid',$advert->placementid) == '2') selected @endif>Front Page Side</option>
                    <option value="3" @if(old('placementid',$advert->placementid) == '3') selected @endif>Sub Page Top</option>
                    <option value="4" @if(old('placementid',$advert->placementid) == '4') selected @endif>Sub Page Side</option>
                </select>

                @error('placementid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>

        </div>


        <div class="row">
            <div class="form-group col-sm-6">
                <label for="focalareaid" class="col-form-label text-md-right">{{ __('advert peculiar focalarea') }}</label>
                <select id="focalareaid" class="form-control form-control-lg @error('focalareaid') is-invalid @enderror" name="focalareaid" required>
                    <option value="0">Please Select A Focalarea</option>
                    @foreach ($focalareas as $focalarea)
                    <option value="{{$focalarea->id}}" @if  (old('focalareaid',$advert->focalareaid) == $focalarea->id) selected @endif>{{$focalarea->name}}</option>
                    @endforeach
                </select>

                @error('focalareaid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>


            <div class="form-group col-sm-6">
                <label for="clusterid" class="col-form-label text-md-right">{{ __('advert peculiar cluster') }}</label>
                <select id="clusterid" class="form-control form-control-lg @error('clusterid') is-invalid @enderror" name="clusterid" required>
                    <option value="0">Please Select A Cluster</option>
                    @foreach ($clusters as $cluster)
                    <option value="{{$cluster->id}}" @if  (old('clusterid',$advert->clusterid) == $cluster->id) selected @endif>{{$cluster->name}}</option>
                    @endforeach
                </select>

                @error('clusterid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>

        </div>


        <div class="row">
            <div class="form-group col-sm-12">
                <label for="adverturl" class="col-form-label text-md-right">{{ __('Advert URL') }}</label>
                <input id="adverturl" type="text"  name="adverturl" value="{{ old('adverturl',$advert->adverturl)}}" placeholder="http://www.abc.com" class="form-control form-control-lg @error('adverturl') is-invalid @enderror" autocomplete="adverturl" autofocus>

                @error('adverturl')
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
    <h2 class="text-capitalize">What departments / Groups is the advert related to ?</h2>

    <div class="row">
        <div class="col-sm-6" style="padding-left: 30px;">
            <h3>Add Advert To Associated Departments</h3>
            <div class="form-group">
                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas))}}">
            </div>

            @php
            for ($fs=0; $fs < count($focalareas); $fs++){
            for ($ufs=0; $ufs < count($advert_focalareas); $ufs++){
            if($focalareas[$fs]->id == $advert_focalareas[$ufs]->focalareaid){
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
                        <a href="" title="Related Departments" data-content="@foreach ($advert_focalareas as $efa) <div class='alert alert-primary'>{{$efa->focalareaname}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();" >
                            <small class="material-icons">more_vert</small>
                        </a>
                    </h6>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <h3>Add Advert to Associated Groups</h3>

            <div class="form-group">
                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
            </div>


            @php
            for ($cl=0; $cl<count($clusters); $cl++){
            for ($ucs=0; $ucs<count($advert_clusters); $ucs++){
            if($clusters[$cl]->id == $advert_clusters[$ucs]->clusterid){
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
                        <a href="" title="Related groups" data-content="@foreach ($advert_clusters as $ecs) <div class='alert alert-primary'>{{$ecs->clustername}}</div>@endforeach" data-html="true" data-toggle="popover" data-placement="auto" data-trigger="focus" role="popover" onclick="event.preventDefault();">
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
    <h2>Advert Date, Time And Upload Profile Picture (Step 5 of 5)</h2>

    <div class="row">
        <div class="row">
            <div class="col-sm-12" id="imageurldiv">
                <div><img src="{{asset($advert->imageurl)}}" class="img-fluid w-25" alt="image on {{$advert->title}}" /></div>
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
    <h2 class="text-capitalize">You can then successfully save this advert (Step 5 of 5)</h2>
    <div class="row">
        <!--<div class="row">
            <div class="col-sm-12"><div class="imgPreview"></div> </div>
        </div>
        <div class="form-group custom-file">
            <label for="images" class="custom-file-label">{{ __('Add Advert Files ')}}</label>
            <input type="file" name="advertfiles[]" class="custom-file-input" id="images" multiple="multiple">
        </div>-->
    </div>
    <!-- Left and right controls -->
    <div class="row">
        <div class="col text-left">
            <a class="" href="#demo" data-slide="prev">
                <span class="material-icons">west</span>
            </a>
        </div>
        <div class="col text-center">
            <input type="hidden" name="advertid" value="{{$advert->id}}" />
            <input type="hidden" name="old_imageurl" value="{{$advert->imageurl}}" />
            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                {{ __('Update Advert') }}
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
    <h3 class="text-capitalize">groups and departments associated with this advert</h3>
    <div class="row">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_advert_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="advertid" value="{{$advert->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($advert_focalareas)}}">
                        @for ($ef=0; $ef< count($advert_focalareas); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="advertfocalarea{{$ef}}" class="custom-control-input" type="checkbox" name="focalarea{{$ef}}"  value="{{$advert_focalareas[$ef]->focalareaid}}" />
                                <label for="advertfocalarea{{$ef}}" class="custom-control-label">{{ __('Leave '). $advert_focalareas[$ef]->focalareaname}}</label>
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
                <form method="POST" action="{{route('remove_advert_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="advertid" value="{{$advert->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($advert_clusters)}}">
                        @for ($cl=0; $cl< count($advert_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$advert_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $advert_clusters[$cl]->clustername}}</label>
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



<!--
<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
    <h3 class="text-capitalize">Files uploaded for this advert</h3>
    @if(!empty($advertfiles))
    <div class="row">
        <div class="row">
            <h2 class="header">adverts Files </h2>
        </div>
        <div class="row">
            @foreach ($advertfiles as $af)
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

                <form action="{{route('deleteadvertFile')}}" method="post"  class="">
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
    <h3 class="text-capitalize">locations and sublocations associated with this advert</h3>
    <div class="row">
        <div class="col-sm-6">

            <h2>Sublocations</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_advert_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="advertid" value="{{$advert->id}}">
                        <input type="hidden" name="sublocationscount" value="{{count($advert_sublocations)}}">
                        @for ($cl=0; $cl< count($advert_sublocations); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usersublocation{{$cl}}" class="custom-control-input" type="checkbox" name="sublocation{{$cl}}"  value="{{$advert_sublocations[$cl]->sublocationid}}" />
                                <label for="usersublocation{{$cl}}" class="custom-control-label">{{ __('Leave '). $advert_sublocations[$cl]->sublocationname}}</label>
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
                <form method="POST"  action="{{route('remove_advert_from_locations')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="advertid" value="{{$advert->id}}">
                        <input type="hidden" name="locationscount" value="{{count($advert_locations)}}">
                        @for ($ef=0; $ef< count($advert_locations); $ef++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="advertlocation{{$ef}}" class="custom-control-input" type="checkbox" name="location{{$ef}}"  value="{{$advert_locations[$ef]->locationid}}" />
                                <label for="advertlocation{{$ef}}" class="custom-control-label">{{ __('Leave '). $advert_locations[$ef]->locationname}}</label>
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

</div>-->
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

                        reader.onload = function(advert) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', advert.target.result).appendTo(imgPreviewPlaceholder);
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
