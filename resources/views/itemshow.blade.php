@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Item : Show</title>
 
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
 
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Styles -->
     
</head>
 
<body class="antialiased">
    <div class="container-lg mx-auto py-4 px-lg-5">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center font-semibold text-xl text-gray-800 leading-tight">
            Item Show
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
 
 
 
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $item->name }}" readonly
                        disabled>
                </div>
 
                <div class="mb-4">
                    <label for="sku" class="block mb-2 text-sm font-bold text-gray-700">Sku</label>
                    <input type="text" name="sku" class="form-control" value="{{ $item->sku }}" readonly
                        disabled>
                </div>
 
                <div class="mb-4">
                    <label for="price" class="block mb-2 text-sm font-bold text-gray-700">Price</label>
                    <input type="text" name="price" id="price" class="form-control" value="{{ $item->price }}"
                        readonly disabled>
                </div>
                 
                <div class="mb-12 d-flex gap-5">
                    <label for="image" class="block mb-2 text-sm font-bold text-gray-700">Document</label>
                    @foreach ($item->getImagesHasMany as $image)
                        <img class="justify-content-center" src="{{ asset('images/' . $image->image) }}"
                            alt="sample" height="150" width="150" />
                    @endforeach
                </div>
                <br><br><br>
                <a href="{{ route('item.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Back
                </a>
            </div>
        </div>
    </div>
    </div>
    <script type='text/javascript' src="{{ asset('js/bootstrap.min.js') }}"></script>
 
</body>
 
</html>
@endsection