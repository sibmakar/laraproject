@extends('layouts.app')

@section('content')
    <form action="/projects" method="POST" class="lg:w-1/2 p-6 md:py-12 md:px-16 bg-white mx-auto rounded shadow">
        @csrf
        <h1 class="text-2xl text-center font-normal mb-10">Let's start something new</h1>
        @include('projects.form', [
        'project' => new App\Project(),
        'buttonText' => 'Create Project'
        ])
    </form>

    {{--<form action="/projects"  method="POST" class="lg:w-1/2 p-6 md:py-12 md:px-16 bg-white mx-auto rounded shadow">--}}
    {{--    @csrf--}}
    {{--    <h1 class="text-2xl text-center font-normal mb-10">Let's start something new</h1>--}}
    {{--    <div class="field mb-6">--}}
    {{--        <label for="title"--}}
    {{--               class="label text-sm mb-2 block">Title</label>--}}

    {{--        <div class="control">--}}
    {{--            <input type="text"--}}
    {{--                   class="input p-2 bg-transparent border border-gray-500 rounded w-full"--}}
    {{--                   name="title"--}}
    {{--                   placeholder="Title">--}}
    {{--        </div>--}}
    {{--    </div>--}}


    {{--    <div class="field">--}}
    {{--        <label for="description" class="label text-sm mb-2 block">Description</label>--}}
    {{--        <div class="control">--}}
    {{--            <textarea--}}
    {{--                rows="10"--}}
    {{--                class="textarea p-2 bg-transparent border border-gray-500 rounded w-full text-sm"--}}
    {{--                name="description" placeholder="Description"></textarea>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="field">--}}
    {{--        <div class="control">--}}
    {{--            <button type="submit" class="button is_link mr-2">Create Project</button>--}}
    {{--            <a href="/projects">Cancel</a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</form>--}}
@endsection
