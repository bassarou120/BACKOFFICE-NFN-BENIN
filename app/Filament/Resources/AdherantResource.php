<?php

namespace App\Filament\Resources;

use AnourValar\EloquentSerialize\Tests\Models\Post;
use App\Filament\Resources\AdherantResource\Pages;
use App\Filament\Resources\AdherantResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\RoleCommune;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;


class AdherantResource extends Resource
{
    protected static ?string $model = Adherant::class;

    protected static ?string $navigationGroup = 'Gestion Adhérent / Membre';

    protected static ?int $navigationSort=1;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('prenom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_naissance')
                    ->required(),
                Forms\Components\TextInput::make('lieu_residence')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adresse')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),



                Forms\Components\Select::make('genre')
                    ->options([

                        'MASCULIN' => 'MASCULIN',
                        'FEMININ' => 'FEMININ',
                    ]),



                Forms\Components\Select::make('departement_id')
                    ->relationship('departement','libelle')
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated( function (Set $set){
                        $set('commune_id',null);
                        $set('arrondissement_id',null);
                        $set('quartier_id',null);

                })

                    ->required()
                    ,
                Forms\Components\Select::make('commune_id')
//                    ->relationship('commune','libelle')
                        ->options(fn (Get $get): Collection => Commune::query()
                        ->where('departement_id',$get('departement_id'))
                        ->pluck("libelle","id")
                    )
                    ->afterStateUpdated( function (Set $set){

                        $set('arrondissement_id',null);
                        $set('quartier_id',null);

                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                   ,
                Forms\Components\Select::make('arrondissement_id')
                    ->required()

//                    ->relationship('arrondissement','libelle')
                    ->options(fn (Get $get): Collection => Arrondissement::query()
                        ->where('commune_id',$get('commune_id'))
                        ->pluck("libelle","id")
                    )
                    ->afterStateUpdated( function (Set $set){


                        $set('quartier_id',null);

                    })
                    ->live()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('quartier_id')
                    ->required()
                    ->relationship('quartier','libelle')
                    ->searchable()
                    ->preload(),

//                Forms\Components\TextInput::make('photo_identite')
//                    ->required()
//                    ->maxLength(255),

                FileUpload::make('photo_identite')
                    ->columnSpan('full')

                    ->directory(function ($record) {

                         $lastid= Adherant::  latest()->first()->id+1;
//                         dd($lastid,$record->id);

                        return 'adherant_photo/' . ($record ? $record->id : $lastid);
                    })
                    ->fetchFileInformation(false)

//                    ->directory('/adherant_photo')
                    ->required(),

//                Forms\Components\TextInput::make('photo_identite')
//                    ->required()
//                    ->maxLength(255),
                FileUpload::make('piece_photo_identite')
                    ->columnSpan('full')
//                    ->directory('/adherant_photo_id')
                    ->fetchFileInformation(false)
                    ->directory(function ($record) {

                        $lastid= Adherant::  latest()->first()->id+1;

                        return '/adherant_photo_id/' . ($record ? $record->id : $lastid);
                    })
                    ->required(),

//                Forms\Components\TextInput::make('piece_photo_identite')
//                    ->required()
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('niveau_instruction')
//                    ->required()
//                    ->maxLength(255),


                Forms\Components\Select::make('niveau_instruction')
                    ->required()
                    ->options([
                        'CEP' => 'CEP',
                        'BEPC' => 'BEPC',
                        'BAC' => 'BAC',
                        'LICENCE' => 'LICENCE',
                        'MASTER' => 'MASTER',
                        'DOCTORAT' => 'DOCTORAT',
                        'AUTRE' => 'AUTRE',
                    ]),

                Forms\Components\Select::make('categorie_socio')
                    ->label('Catégorie socio professionelle')
                    ->options([

                        'Artisans' => 'Artisans',
                        'Employés' => 'Employés',
                        'Ouviriers' => 'Ouviriers',
                        'Agriculteurs exploitants' => 'Agriculteurs exploitants',
                        'Elèves' => 'Elèves',
                        'Enseignants' => 'Enseignants',
                        'Etudiants' => 'Etudiants',
                        'AUTRE' => 'AUTRE',
                    ]),

                Forms\Components\TextInput::make('activite_profession')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('ambition_politique')
                    ->required()
                    ->columnSpanFull(),
//                Forms\Components\TextInput::make('status')
//                    ->required()
//                    ->maxLength(255)
//                    ->default('EN ATTENTE DE VALIDATION'),

                Forms\Components\Select::make('status')
                    ->label('status')
                    ->options([

                        'EN ATTENTE DE VALIDATION' => 'EN ATTENTE DE VALIDATION',
                        'APPROUVER' => 'APPROUVER',
                        'NON APPROUVER' => 'NON APPROUVER',


                    ]) ->default('EN ATTENTE DE VALIDATION'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identifiant')

                    ->searchable(),
                Tables\Columns\ImageColumn ::make('photo_identite')
                ->label("Photo")
                    ->width(60)
                    ->height(60)
                ,
                Tables\Columns\TextColumn::make('nom')
                     ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('prenom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),


                Tables\Columns\TextColumn::make('date_naissance')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lieu_residence')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('categorie_socio')
                    ->label('Catégorie socio professionelle')
                    ->sortable()
                    ->searchable(),


//                Tables\Columns\TextColumn::make('adresse')
//                    ->searchable(),


                Tables\Columns\TextColumn::make('departement.libelle')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('commune.libelle')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('arrondissement.libelle')

                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('quartier.libelle')
                    ->numeric()
                    ->sortable(),


//                Tables\Columns\TextColumn::make('photo_identite')
//                    ->searchable(),
                Tables\Columns\TextColumn::make('piece_photo_identite')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('niveau_instruction')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('activite_profession')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //

                Tables\Filters\SelectFilter::make("Departement")
                    ->preload()
                    ->searchable()
                ->relationship('departement','libelle'),

                Tables\Filters\SelectFilter::make("Commune")
                    ->preload()
                    ->searchable()
                ->relationship('commune','libelle'),

                Tables\Filters\SelectFilter::make("Arrondisement")
                    ->preload()
                    ->searchable()
                ->relationship('arrondissement','libelle'),

                Tables\Filters\SelectFilter::make("Quartier")
                    ->preload()
                    ->searchable()
                    ->relationship('quartier','libelle'),

                SelectFilter::make('genre')
                    ->options([

                        'MASCULIN' => 'MASCULIN',
                        'FEMININ' => 'FEMININ',
                    ]),

                SelectFilter::make('categorie_socio')
                    ->label('Catégorie socio professionelle')
                    ->options([

                        'Artisans' => 'Artisans',
                        'Employés' => 'Employés',
                        'Ouviriers' => 'Ouviriers',
                        'Agriculteurs exploitants' => 'Agriculteurs exploitants',
                        'Elèves' => 'Elèves',
                        'Enseignants' => 'Enseignants',
                        'Etudiants' => 'Etudiants',
                        'AUTRE' => 'AUTRE',
                    ]),

                SelectFilter::make('niveau_instruction')
                    ->options([

                        'CEP' => 'CEP',
                        'BEPC' => 'BEPC',
                        'BAC' => 'BAC',
                        'LICENCE' => 'LICENCE',
                        'MASTER' => 'MASTER',
                        'DOCTORAT' => 'DOCTORAT',
                        'AUTRE' => 'AUTRE',
                    ]),

                Filter::make('created_at')
                    ->label("Periode")
                    ->form([
                        DatePicker::make('created_from')->label('Date debut'),
                        DatePicker::make('created_until')->label('Date fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Date debut ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Date fin ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })->columnSpan(2)->columns(2),

//                Tables\Filters\SelectFilter::make("Departement")
//                    ->preload()
//                    ->searchable()
            ],
//                layout: FiltersLayout::AboveContent
                layout:FiltersLayout:: AboveContentCollapsible
            )
                ->modifyQueryUsing(function (Builder $query) {

                    $user = Auth::user();
                    if($user->role->name=="Administrateur"){
                    $req=$query->where('status','=',"APPROUVER");
                    }elseif($user->role->name=="Réseau des Femmes"){
                        $req= $query->where('status','=',"APPROUVER")
                                    ->where('genre','=',"FEMININ");
                    }elseif ($user->role->name=="Réseau des Enseignants")
                    {
                        $req= $query->where('status','=',"APPROUVER")
                            ->where('categorie_socio','=',"Enseignants")
                             ;
                    }elseif ($user->role->name=="Réseau des Elèves et Etudiants"){
                        $req= $query->where('status','=',"APPROUVER")
                            ->where('categorie_socio','=',"Etudiants")
                            ->orWhere('categorie_socio','=',"Elèves");
                    }elseif ($user->role->name=="Réseau des Artisans"){
                        $req= $query->where('status','=',"APPROUVER")
                            ->where('categorie_socio','=',"Artisans") ;
                    } elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){

                        $roleCom=RoleCommune::where("role_id",'=',$user->role->id)->get();
                        $listCom=[];
                        foreach ($roleCom as $rc){array_push($listCom,$rc->commune_id);}

                        $req= $query->where('status','=',"APPROUVER")
                            ->whereIn('commune_id',$listCom );
                    }

//                    elseif (substr($user->role->name,0,3)=="CC-"){
//
//                    }
               return  $req;

                })
            ->actions([
//                Tables\Actions\Action::make('activate')
//                    ->action(fn (Adherant $record) => $record->activate())
//                    ->requiresConfirmation()
//                    ->color('success'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),


                    Tables\Actions\BulkAction::make('Export')
                        ->label('Exporter PDF')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {

//                                dd($records);
                                echo Pdf::loadHTML(  Blade::render('adherant_pdf', ['records' => $records])
                                )->setPaper('A4', 'landscape')
                                    ->stream();
                            }, 'users.pdf');
                        }),

//                    Tables\Actions\BulkAction::make('generatePdf')
//                        ->label('Generate PDF')
//                        ->action(function ($records) {
//                            $pdf = Pdf::loadView('pdf.selected-items', ['records' => $records]);
//                            return response()->streamDownload(function () use ($pdf) {
//                                echo $pdf->output();
//                            }, 'selected-items.pdf');
//                        })
//                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informations Personnelles')
                    ->schema([
                        TextEntry::make('nom'),
                        TextEntry::make( 'prenom'   ),
                        TextEntry::make(  'date_naissance'   ),
                        TextEntry::make('email'),
                        TextEntry::make('telephone'),
                    ])->columns(4),
                Section::make('Adresse')
                    ->schema([
                        TextEntry::make('departeemnt.libelle'),
                        TextEntry::make(  'commune.libelle'    ),
                        TextEntry::make(    'arrondissement.libelle'  ),
                        TextEntry::make(    'quartier.libelle'  ),
                    ])->columns(4),
                Section::make('Ambition politique')
                    ->schema([
                        TextEntry::make('niveau_instruction'),
                        TextEntry::make( 'activite_profession'   ),
                        TextEntry::make( 'ambition_politique'   )->columnSpan(2),
                    ])->columns(2)  ,
                Section::make("Pièce d'identité")
                    ->schema([
                        ImageEntry::make('photo_identite'),
                        ImageEntry::make('piece_photo_identite'),

                    ])->columns(2)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdherants::route('/'),
            'create' => Pages\CreateAdherant::route('/create'),
            'view' => Pages\ViewAdherant::route('/{record}'),
            'edit' => Pages\EditAdherant::route('/{record}/edit'),
        ];
    }
}
