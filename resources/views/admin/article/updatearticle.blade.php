
@extends('layouts.admin')

@section('content')
<div class="container">
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

    <div class="row justify-content-center">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">

            <div class="form-group">
                <form method="POST" action="{{ route('editarticle') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title" class="col-form-label text-md-right">{{ __('Title') }}</label>
                                <input id="title" type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" name="title" value="{{old('title',$article->title)}}" required autocomplete="name" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col">
                            <label for="categoryid" class="col-form-label text-md-right">{{ __('Category') }}</label>
                            <select id="categoryid" class="form-control form-control-lg @error('categoryid') is-invalid @enderror" name="categoryid" required>
                                <option value="">Please Select role</option>
                                @foreach ($categories as $cat)
                                <option value="{{$cat->id}}" @if ($article->categoryid == $cat->id) selected @endif>{{$cat->name}}</option>
                                @endforeach
                            </select>

                            @error('categoryid')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="mytextarea">{{__('Detail')}}</label>
                            <textarea name="detail" id="mytextarea" class="w-100" style="height: 500px;">{{old('detail',$article->detail)}}</textarea>

                        </div>

                        <script type="text/javascript">
                            tinymce.init({
                                selector: '#mytextarea'
                            });
                        </script>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <img src="{{asset($article->imageurl)}}" class="img-fluid">
                        </div>
                        <div id="imageurldiv" class="col-sm-4 offset-2"> </div>
                    </div>

                    <div class="row">
                        <div class="form-group custom-file">
                            <label for="imageurl" class="custom-file-label">{{ __('Upload Article Picture')}}</label>
                            <input type="file" name="imageurl" class="custom-file-input form-control-file @error('imageurl') is-invalid @enderror " id="imageurl">
                        </div>
                        @error('imageurl')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="row">
                        <h2 class="text-center">Choose Departments</h2>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas) - count($article_focalareas))}}">
                            </div>

                            @php
                            for ($fs=0; $fs < count($focalareas); $fs++){
                                for ($ufs=0; $ufs < count($article_focalareas); $ufs++){
                                    if($focalareas[$fs]->id == $article_focalareas[$ufs]->focalareaid){
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

                        </div>


                        <div class="col-sm-6">
                            <h3>Original Departments</h3>
                            @foreach ($article_focalareas as $af)
                            <div class="alert alert-primary">{{$af->focalareaname}}</div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <h2 class="text-center">Choose Groups</h2>
                    </div>
                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">
                                <input type="hidden" class="form-control" name="clusterscount" value="{{(count($clusters) - count($article_clusters))}}">
                            </div>


                            @php
                                for ($cl=0; $cl<count($clusters); $cl++){
                                    for ($ucs=0; $ucs<count($article_clusters); $ucs++){
                                        if($clusters[$cl]->id == $article_clusters[$ucs]->clusterid){
                                            $clusters[$cl]['selected'] = true;
                                        }
                                    }

                                }
                            @endphp
                            @for ($cl=0; $cl< count($clusters); $cl++)

                            <div class="row">
                                @if ((isset($clusters[$cl]['selected'])) && ($clusters[$cl]['selected'] == true))
                                <span></span>
                                <!--<div class="form-group custom-control custom-checkbox">
                                    <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" checked />
                                    <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $clusters[$cl]->name}}</label>
                                </div>-->

                                @else

                                <div class="form-group custom-control custom-switch">
                                    <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" />
                                    <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Join '). $clusters[$cl]->name}}</label>
                                </div>
                                @endif
                            </div>
                            @endfor

                        </div>


                        <div class="col-sm-6">
                            <h3>Original Groups</h3>
                            @foreach ($article_clusters as $ac)
                            <div class="alert alert-primary">{{$ac->clustername}}</div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-sm-8 imgPreview"> </div>
                    </div>

                        <div class="custom-file">
                            <input type="file" name="articlefiles[]" class="custom-file-input" id="images" multiple="multiple">
                            <label class="custom-file-label" for="images">Choose image</label>
                        </div>

                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" name="articleid" value="{{$article->id}}">
                            <input type="hidden" name="old_imageurl" value="{{$article->imageurl}}">
                        </div>
                    </div>
                        <div class="form-group row">
                            <div class="">
                                <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg btn-block">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>


    <div class="row" style="margin-top: 40px;">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_article_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="articleid" value="{{$article->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($article_focalareas)}}">
                        @for ($fa=0; $fa< count($article_focalareas); $fa++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="userfocalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}"  value="{{$article_focalareas[$fa]->focalareaid}}" />
                                <label for="userfocalarea{{$fa}}" class="custom-control-label">{{ __('Leave '). $article_focalareas[$fa]->focalareaname}}</label>
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
                <form method="POST" action="{{route('remove_article_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="articleid" value="{{$article->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($article_clusters)}}">
                        @for ($cl=0; $cl< count($article_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$article_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $article_clusters[$cl]->clustername}}</label>
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

    @if(!empty($articlefiles))
    <div class="row">
            <div class="row">
                <h2 class="header">Articles Files </h2>
            </div>
            <div class="row">
                @foreach ($articlefiles as $af)
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

                <form action="{{route('deleteArticleFile')}}" method="post"  class="">
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
