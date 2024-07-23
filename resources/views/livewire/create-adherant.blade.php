<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

        <form wire:submit="create">
            {{ $this->form }}

            <button type="submit">
                Submit
            </button>
        </form>

        <x-filament-actions::modals />

</div>
