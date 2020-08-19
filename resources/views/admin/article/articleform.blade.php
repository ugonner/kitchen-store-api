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
                <form method="POST" action="{{ route('createarticle') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group form-row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title" class="col-form-label text-md-right">{{ __('Title') }}</label>
                                <input id="title" type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="name" autofocus>

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
                                <option value="{{$cat->id}}" @if (old('categoryid') == $cat->id) selected @endif>{{$cat->name}}</option>
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
                            <textarea id="mytextarea" name="detail">{{old('detail','Hello, World!')}}</textarea>

                        </div>

                        @error('detail')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                        @enderror

                            <script type="text/javascript">
                                tinymce.init({
                                    selector: '#mytextarea'
                                });
                            </script>
                    </div>


                    <div class="row">
                        <div id="imagepreview" class="col-sm-6 imgPreview"></div>
                        <div id="imagepreview" class="col-sm-6"></div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="imageurl">{{ __('Upload Profile Picture')}}</label>
                            <input type="file" name="imageurl" class="form-control-file @error('imageurl') is-invalid @enderror " id="imageurl">
                        </div>
                        @error('imageurl')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Add Article To Associated Departments</h3>
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="focalareascount" value="{{count($focalareas)}}">
                            </div>
                            @for ($fa=0; $fa< count($focalareas); $fa++)
                            <div class="row">
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
                            </div>
                            @endfor
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Add Article to Associated Groups</h3>

                            <div class="form-group">
                                <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
                            </div>
                            @for ($cl=0; $cl< count($clusters); $cl++)
                            <div class="row">
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
                            </div>
                            @endfor

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="">
                            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
<script type="text/javascript">
    // Material Select Initialization
    $(document).ready(function() {

            var previewImage = function(input, imagePreviewDiv){
                try{
                    if(input.files){
                        var reader = new FileReader();
                        reader.onload = function(event){
                            var img = $.parseHTML('<img src=""/>');
                            $(img).attr('src',event.target.result).attr('class','img-fluid').appendTo(imagePreviewDiv);

                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }catch (err){
                    alert('err');
                }
            };

            $('#imageurl').on('change', function() {
                previewImage(this, 'div.imgPreview');
            });

        $('.mdb-select').materialSelect();
    });
</script>
@endsection
