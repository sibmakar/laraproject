@extends('layouts.app')

@section('content')
    <form action="{{$project->path()}}" method="POST"
          class="lg:w-1/2 p-6 md:py-12 md:px-16 bg-white mx-auto rounded shadow">
        <h1 class="text-2xl text-center font-normal mb-10">Edit your project</h1>
        @csrf
        @method('PATCH')
        @include('projects.form', ['buttonText' => 'Update Project'])
    </form>
@endsection
