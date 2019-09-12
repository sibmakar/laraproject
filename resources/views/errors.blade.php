@if($errors->{ $bag ?? 'default' }->any())

    <ul class="field text-red-500 text-sm mt-5 list-reset">

        @foreach($errors->{ $bag ?? 'default' }->all() as $error)
            <li class="">{{$error}}</li>
        @endforeach

    </ul>
@endif
