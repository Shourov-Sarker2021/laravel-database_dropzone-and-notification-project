@extends('layouts.app')
@section('content') 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>Item : List</title>
 
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
 
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <!-- Styles -->
    
</head>
 
<body class="antialiased" style="background-color:#21aade">
    <div class="container-lg mx-auto py-4 px-lg-5">
    <div class="mx-auto p-4 p-lg-5">
        <div class="d-flex justify-content-center fw-bold h3 text-dark" style="margin-top:10 px">
            Item List Page
        </div>
    </div>
    <div class="container-lg mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-4 bg-white border-bottom border-secondary">
                <a href="{{ route('item.create') }}"
                    class="btn btn-success btn-sm fw-bold text-uppercase mb-2">Add New Product</a>
 
                @if ($message = Session::get('success'))
                    <div class="bg-success border-top border-4 border-success text-white px-4 py-3 mb-3 shadow"
                        role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm" style="color:cornsilk">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <table id="datatable_tbl" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    <form action="{{ route('item.destroy', $item->id) }}" method="POST">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('item.show', $item->id) }}">Show</a>
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('item.edit', $item->id) }}">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this ?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
    <script type='text/javascript' src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/datatables.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable_tbl').DataTable();
        });
    </script>
</body>
 
</html>

@endsection