
<div class="card flex flex-col mt-3" >
    <h3 class="py-4 -ml-5 mb-2 pl-4 border-l-4 border-blue-lighter">
        Invite a User
    </h3>
    <form action="{{ $project->path() }}/invitation" method="POST">
        @csrf
        <div class="mb-2">
            <input type="email" name="email"
                   class="border border-gray-500 rounded w-full p-2 bg-card text-default"
                   placeholder="Email address">
        </div>
        <button type="submit" class="button w-full">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>
