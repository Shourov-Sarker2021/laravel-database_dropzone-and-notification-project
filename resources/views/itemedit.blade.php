@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>Item : Edit</title>
 
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
 
    <!-- KEY : DROPZONE starts -->
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <!-- KEY : DROPZONE ends -->
    <!-- Styles -->
    
</head>
 
<body class="antialiased">
    <div class="container-lg mx-auto py-4 px-lg-5">
     
        <div class="mx-auto p-4 p-lg-5">
            <div class="d-flex justify-content-center font-weight-bold h3 text-dark">
                Item Edit
            </div>
        </div>
     
    <div class="py-12">
        <div class="container-lg mx-auto px-sm-5 px-lg-7">
            <div class="bg-white overflow-hidden shadow-lg rounded px-4 py-4">
                <a href="{{ route('item.index') }}" class="btn btn-light">
                    Back
                </a>
 
                @if ($message = Session::get('error'))
                    <div class="alert alert-success rounded-0 border-0 border-top border-success" role="alert">
                        <div class="d-flex">
                            <div>
                                <p class="text-sm text-danger">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{ route('item.update', $item->id) }}" name="form-edit" id="form-edit" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="mb-2 font-weight-bold text-muted small">Name<span
                                class="text-danger"> *
                            </span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Name"
                            maxlength="100" value="{{ $item->name }}">
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
                            value="{{ $item->sku }}">
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
                            placeholder="Enter Price" maxlength="50" value="{{ $item->price }}">
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
                            <button type="submit" id="submit-all" class="btn btn-primary btn-sm fw-bold text-uppercase rounded-pill mt-2">
                                Update
                            </button>
                        </div>
                    </div>
                    <!-- KEY : DROPZONE ends -->                   
                </form>
                <div class="mb-12 d-flex gap-5">
                    <label for="image" class="block mb-2 text-sm font-bold text-gray-700">Images</label>
                    @foreach ($item->getImagesHasMany as $image)
                        <div class="image-container">
                            <img src="{{ asset('images/' . $image->image) }}" alt="sample" height="150" width="150" />
                            <form action="{{ route('image.delete', $image->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this ?');">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
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
        var minFiles = 0; // minimum file must be to upload
        var maxFiles = 3; // maximum file allows to upload
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
                return time + file.name;
            },
            success: function(file, response) {
                console.log('success file');
                console.log(file);
                console.log(response);
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function(file) {
                console.log('remove calls');
                console.log('remove file');
                console.log(file);
                // remove uploaded file from table and storage folder starts
                var filename = ''
                if (file.hasOwnProperty('upload')) {
                    filename = file.upload.filename;
                } else {
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
                $('form').find('input[name="document[]"][value="' + filename + '"]').remove();
            },
            maxfilesexceeded: function(file) {
                // this.removeAllFiles();
                // this.addFile(file);
                // myDropZone.removeFile(file);
            },
            init: function() {
                console.log('init calls');
                myDropzone = this;
                // Read Files from tables and storage folder starts
                $.ajax({
                    url: "{{ url('readFiles') }}/{{ $item->id }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(key, value) {
                            var mockFile = {
                                name: value.name,
                                size: value.size,
                                accepted: true,
                                kind: 'image'
                            };
                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.files.push(mockFile);
                            myDropzone.emit("thumbnail", mockFile, value.path);
                            // myDropzone.createThumbnailFromUrl(mockFile, value.path,
                            //     function() {
                            //         myDropzone.emit("complete", mockFile);
                            //     });
                            myDropzone.emit("complete", mockFile);
 
                            $('form').append('<input type="hidden" name="document[]" value="' +
                                value.name + '">');
                            uploadedDocumentMap[value.name] = value.name;
                        });
                    }
                });
                // Read Files from tables and storage folder ends
 
                // maxfiles files limit upload validation starts
                this.on("maxfilesexceeded", function(file) {
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
                    console.log(imagelength)
                    if (imagelength < minFiles) {
                        alert("Minimum " + minFiles + " file needs to upload...!");
                        return false;
                    } else {
                        $('#form-edit').submit();
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