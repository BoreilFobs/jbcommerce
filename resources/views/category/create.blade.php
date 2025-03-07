@extends('layouts.app')
@section('title', 'Create Category')
@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>error!</strong> {{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
    <div class="main d-flex justify-content-center align-items-center">
        <form action={{ url('/categories/create') }} method="POST" class="w-75" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" placeholder="Category Name" class="form-control" required>
            </div>

            <button class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
