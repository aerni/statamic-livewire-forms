<div>
    <form wire:submit.prevent="submit">
        {{-- Add your fields here binding them like this: wire:model.lazy="fields.your_field_name" --}}
        {{-- <input autocomplete="name" type="text" wire:model.lazy="fields.name" /> --}}
        {{-- @error('fields.name')<div>{{ $message }}</div>@enderror --}}
        <button>Submit</button>
        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li class="mb-1">{{ $error }}</li>
            @endforeach
        </ul>
        @endif
        @if($success)
        <div>
            <p>Thanks!</p>
        </div>
        @endif
    </form>
</div>