@extends('layouts.app')
@section('title', 'Messages')
@section('content')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">phone</th>
                <th scope="col">Object</th>
                <th scope="col">Message</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr>
                    <th scope="row">{{ $message->id }}</th>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->phone }}</td>
                    <td>{{ $message->object }}</td>
                    <td>{{ $message->message }}</td>
                    <td><a title="delete message" href={{ url('/message/delete/' . $message->id) }}><i
                                class="fa fa-trash text-danger fs-1"></i></a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
