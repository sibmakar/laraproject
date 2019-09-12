<div class="card flex flex-col" style="height: 250px">

    <h3 class="py-4 -ml-5 mb-3 pl-4 border-l-4 border-blue-light">
        <a href="{{ $project->path() }}" class=" no-underline font-normal text-lg">{{ $project->title }}</a>
    </h3>

    <div class="efault text-sm mb-4 flex-1">{{ Illuminate\Support\Str::limit($project->description, 140) }}</div>
    @can('manage', $project)
        <footer>
            <form action="{{ $project->path() }}" method="POST" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs ">Delete</button>
            </form>
        </footer>
    @endcan
</div>

