<?php

namespace App\Livewire;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;

class CreateAdherant extends Component  implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
             TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                 TextInput::make('prenom')
                    ->required()
                    ->maxLength(255),
             DatePicker::make('date_naissance')
                    ->required(),
           TextInput::make('lieu_residence')
                    ->required()
                    ->maxLength(255),
                TextInput::make('adresse')
                    ->required()

            ])->columns(4)
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }



    public function render()
    {
        return view('livewire.create-adherant');
    }
}
