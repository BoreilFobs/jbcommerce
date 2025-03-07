@extends('layouts.app')
@section('title', 'Categories')
@section('content')

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row">{{ $category->id }}</th>
                    <td>{{ $category->name }}</td>
                    <td><a title="delete category" href={{ url('/categories/delete/' . $category->id) }}><i
                                class="fa fa-trash text-danger fs-1"></i></a>
                        <a title="update category" href={{ url('/categories/update/' . $category->id) }}><i
                                class="fa fa-pen text-warning mx-4 fs-1"></i></a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
