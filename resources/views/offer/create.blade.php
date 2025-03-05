@extends('layouts.app')
@section('title', 'Create Offers')

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
        <form action={{ url('/offers/create') }} method="POST" class="w-75" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="form-group">
                <label for="name">Offer Name</label>
                <input type="text" name="name" placeholder="Offer Name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" placeholder="Offer's Category" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">price</label>
                <input type="number" name="price" placeholder="Offer's Price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="instock">instock</label>
                <input type="text" name="instock" placeholder="Is the offer instock?" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="image">Offer Image</label>
                <input type="file" name="image" placeholder="Select the offer image" class="form-control" required>
            </div>
            <button class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
