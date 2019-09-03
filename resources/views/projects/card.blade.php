
    <div class="card" style="height: 200px">

        <h3 class="py-4 -ml-5 mb-3 pl-4 border-l-4 border-blue-light">
            <a href="{{ $project->path() }}" class="text-black no-underline font-normal text-lg">{{ $project->title }}</a>
        </h3>

        <div class="text-gray-500 text-sm">{{ Illuminate\Support\Str::limit($project->description, 140) }}</div>

    </div>

