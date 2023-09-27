@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>Item : Create</title>
 
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
 
    <!-- KEY : DROPZONE starts -->
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <!-- KEY : DROPZONE ends -->
 
</head>
 
<body class="antialiased" style="background-color:#21aade">
    <div class="container-lg mx-auto py-4 px-lg-5">
        <div>
        <div class="d-flex justify-content-center font-weight-bold h3 text-dark">
            Item Create Page
        </div>
    </div>
    <div class="py-12">
        <div class="container-lg mx-auto px-sm-5 px-lg-7">
            <div class="bg-white overflow-hidden shadow-lg rounded px-4 py-4">
                <a href="{{ route('item.index') }}" class="btn btn-primary">
                    Back
                </a>
 
                @if ($message = Session::get('error'))
                    <div class="alert alert-success rounded-0 border-0 border-top border-success"
                        role="alert">
                        <div class="d-flex">
                            <div>
                                <p class="text-sm text-danger">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{ route('item.store') }}" name="form-create" id="form-create" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="mb-2 font-weight-bold text-muted small">Name<span
                                class="text-danger"> *
                            </span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Name"
                            maxlength="50" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
 
                    <div class="mb-4">
                        <label for="sku" class="mb-2 font-weight-bold text-muted small">Sku<span
                                class="text-danger"> *
                            </span></label>
                        <input type="text" name="sku" class="form-control" placeholder="Enter SKU" maxlength="5"
                            value="{{ old('sku') }}">
                        @error('sku')
                            <span class="text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
 
                    <div class="mb-4">
                        <label for="price" class="mb-2 font-weight-bold text-muted small">Price<span
                                class="text-danger"> *
                            </span></label>
                        <input type="text" name="price" id="price" class="form-control"
                            placeholder="Enter Price" maxlength="50" value="{{ old('price') }}">
                        @error('price')
                            <span class="text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <!-- KEY : DROPZONE starts -->
                    <div class="form-group">
                        <label for="document">Documents</label>
                        <div class="needsclick dropzone" id="document-dropzone">
                        </div>
                        <div>
                            <button type="submit" id="submit-all" class="btn btn-primary btn-sm fw-bold text-uppercase mt-2">
                                Save
                            </button>
                        </div>
                    </div>
                    <!-- KEY : DROPZONE ends -->
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- KEY : DROPZONE starts -->
    <script type='text/javascript' src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/dropzone.min.js') }}"></script>
 
    <script type="text/javascript">
        var uploadedDocumentMap = {}        
        var minFiles = 1; // minimum file must be to upload
        var maxFiles = 5; // maximum file allows to upload
        var myDropzone = Dropzone.options.documentDropzone = {
            url: "{{ route('uploads') }}",
            minFiles: minFiles,
            maxFiles: maxFiles,
            autoProcessQueue: true,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: ".jpeg,.jpg,.png",
            timeout: 5000,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
               return time+file.name;
            },
            success: function(file, response) {
                console.log('success file');
                console.log(file);
                console.log(response);
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function(file) {
                console.log('remove file');
                console.log(file);                                
                // remove uploaded file from table and storage folder starts
                var filename = ''
                if (file.hasOwnProperty('upload')) {
                    filename = file.upload.filename;
                }else{
                    filename = file.name;
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ url('image/delete') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        filename: filename,                        
                    },
                    sucess: function(data) {
                        console.log('removed success: ' + data);
                    }
                });
 
                // remove file name from uploadedDocumentMap object
                Reflect.deleteProperty(uploadedDocumentMap, file.name);
                 
                file.previewElement.remove();
                // remove uploaded file from table and storage folder ends
                // additional delete from multiple hidden files
                $('form').find('input[name="document[]"][value="' + filename + '"]').remove()
            },
            maxfilesexceeded: function(file) {
                //this.removeAllFiles();
                //this.addFile(file);
                //myDropZone.removeFile(file);
            },
            init: function() {
                console.log('init calls');
                // maxfiles files limit upload validation starts
                this.on("maxfilesexceeded", function(file) { // Maximum file upload validations                   
                    alert("Maximum " + maxFiles + " files are allowed to upload...!");
                    return false;
                });
                // maximum files limit upload validation ends
                 
                // minimum files limit upload validation starts
                var submitButton = document.querySelector("#submit-all");
                myDropzone = this;
                submitButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    var imagelength = Object.keys(uploadedDocumentMap).length;
                    if(imagelength < minFiles ){
                        alert("Minimum "+minFiles+" file needs to upload...!");
                        return false;
                    }else{
                        $('#form-create').submit();
                    }
                    /*Dropzone.forElement(".dropzone").options.autoProcessQueue = false;
                    if (myDropzone.getQueuedFiles().length >= minFiles) {
                        //myDropzone.processQueue();
                        Dropzone.forElement(".dropzone").options.autoProcessQueue = true;                        
                        Dropzone.forElement(".dropzone").processQueue();
                        $('#form-create').submit();
                    } else { // Minimum file upload validations
                        Dropzone.forElement(".dropzone").options.autoProcessQueue = false;
                        alert("Minimum "+minFiles+" file needs to upload...!");
                        return false;
                    }*/
                });
                // minimum files limit upload validation ends
            },
            error: function(file, response) {
                console.log('error file')
                console.log(file)
                console.log(response)
                $(file.previewElement).remove(); // removed files if validation fails
                return false;
            }
        }
 
    </script>
    <!-- KEY : DROPZONE ends -->
</body>
 
</html>
@endsection