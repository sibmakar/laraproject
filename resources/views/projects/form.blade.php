<div class="field mb-6">
    <label for="title"
           class="label text-sm mb-2 block text-default">Title</label>

    <div class="control">
        <input type="text"
               class="input p-2 bg-transparent border border-default rounded w-full bg-card text-default"
               name="title"
               placeholder="Title"
               value="{{ $project->title }}" required>
    </div>
</div>


<div class="field">
    <label for="description" class="label text-sm mb-2 block text-default">Description</label>
    <div class="control">
            <textarea
                rows="10"
                class="textarea p-2 bg-transparent border border-gray-500 rounded w-full text-sm bg-card text-default"
                name="description" placeholder="Description" required>{{ $project->description }}</textarea>
    </div>
</div>
<div class="field">
    <div class="control">
        <button type="submit" class="button is_link mr-2 ">{{$buttonText}}</button>
        <a href="{{ $project->path() }}" class="text-default">Cancel</a>
    </div>


@include('errors')
