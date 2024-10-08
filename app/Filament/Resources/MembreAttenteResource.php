<?php

namespace App\Filament\Resources;


use App\Filament\Exports\AdherantExporter;
use App\Filament\Resources\AdherantResource\Pages\ViewAdherant;
use App\Filament\Resources\MembreAttenteResource\Pages;
use App\Filament\Resources\MembreAttenteResource\RelationManagers;
use App\Mail\SampleMail2;
use App\Models\Adherant;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\MembreAttente;
use App\Models\RoleCommune;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\Exports\Enums\ExportFormat;

use Filament\Tables\Enums\FiltersLayout;



use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManagerStatic as Image;


class MembreAttenteResource extends Resource
{
    protected static ?string $model = Adherant::class;

    protected static ?string $navigationGroup = 'Gestion Adhérent / Membre';
    protected static ?string $navigationLabel="Membre en Attente";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort=3;

    protected static ?string $recordTitleAttribute = 'nom';
    protected $listeners = ['refreshRelations' => '$refresh'];

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
    /*
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

                Forms\Components\TextInput::make('photo_identite')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('piece_photo_identite')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('niveau_instruction')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('activite_profession')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('ambition_politique')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'APPROVER' => 'APPROVER',
                        'REJETER' => 'REJETER',

                    ])
                    ->native(false)
                    ->default('EN ATTENTE DE VALIDATION'),
            ])
            ->columns(4);
    }
    */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('identifiant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')

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
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('categorie_socio')
                    ->label('Catégorie socio professionelle')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('date_naissance')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lieu_residence')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('quartier.libelle')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->sortable(),


//                Tables\Columns\TextColumn::make('photo_identite')
//                    ->searchable(),
                Tables\Columns\TextColumn::make('piece_photo_identite')
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('niveau_instruction')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('activite_profession')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->query(function (\Illuminate\Contracts\Database\Eloquent\Builder $query, array $data): Builder {
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
                            $indicators['created_from'] = 'Date debut ' . \Illuminate\Support\Carbon::parse($data['created_from'])->toFormattedDateString();
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
//            ->filters([
//                //
//
//            ])
            ->modifyQueryUsing(function ( Builder $query) {

                $user = Auth::user();

                if($user->role->name=="Administrateur"){


                $req=$query->where('status','=',"EN ATTENTE DE VALIDATION")
                ->orWhere('status','=','EN ATTENTE DE CONFIRMATION');

                }elseif($user->role->name=="Réseau des Femmes"){
                    $req= $query->where('status','=',"EN ATTENTE DE VALIDATION")
                        ->where('genre','=',"FEMININ");
                }elseif ($user->role->name=="Réseau des Enseignants")
                {
                    $req= $query->where('status','=',"EN ATTENTE DE VALIDATION")
                        ->where('categorie_socio','=',"Etudiants")
                        ->orWhere('categorie_socio','=',"Elèves");
                }elseif ($user->role->name=="Réseau des Elèves et Etudiants"){
                    $req= $query->where('status','=',"EN ATTENTE DE VALIDATION")
                        ->where('categorie_socio','=',"Etudiants")
                        ->orWhere('categorie_socio','=',"Elèves");
                }elseif ($user->role->name=="Réseau des Artisans"){
                    $req= $query->where('status','=',"EN ATTENTE DE VALIDATION")
                        ->where('categorie_socio','=',"Artisans") ;

                }
                elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){

                    $roleCom=RoleCommune::where("role_id",'=',$user->role->id)->get();
                    $listCom=[];
                    foreach ($roleCom as $rc){array_push($listCom,$rc->commune_id);}

                    $req= $query->where('status','=',"EN ATTENTE DE VALIDATION")
                        ->whereIn('commune_id',$listCom );
                }

//


                return  $req;
//                return $query->where('status','!=',"EN ATTENTE");
//                return $query->where('status','!=',"APPROUVER");
            })
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('activate')
                    ->action(function ($record) {

                        $user = Auth::user();

                        if($user->role->name=="Administrateur"){

                            $record->status="APPROUVER";
                            $record->save();

                            $content = [
                                'subject' => 'Félicitations pour votre adhésion au parti FNF !',
                                'body' =>"Cher(e)". $record->prenom." ".  $record->nom." ,<br>
                            <p> Votre adhésion à la NFN est définitivement validée.
                                Pour obtenir votre carte de membre , cliquez sur https://nfn.bj/fait-un-don/ (coût 200f CFA)
                                Pour plus d’informations, contactez le +22990430506 (appel et WhatsApp)
                                </p>

                            Cordialement,

                             <br>
                            "
                            ];
                            Mail::to([$record->email ])->send(new SampleMail2($content));

                        }else{

                            $record->status="EN ATTENTE DE CONFIRMATION";
                            $record->save();

                            $contentAdminAdherant = [
                                'subject' => 'Confirmation de votre adhésion au parti FNF',
                                'body' =>" Demande d’adhésion N°". $record->identifiant." pré-validée par le CCE . <br>
                                        Votre validation est requise dans les 72h."

                            ];

                            $users = User::with('role')->get();
                            $listAdminAdherant =[];



                            foreach ($users as  $user){

                                if ($user->role !=null  && $user->role->name=="Administrateur"){
                                    array_push($listAdminAdherant,$user->email);
                                }

                            }

                            Mail::to($listAdminAdherant)->send(new SampleMail2($contentAdminAdherant));

                        }


                    } )
                    ->label(function ($record){
                        $user = Auth::user();

                        if($user->role->name=="Administrateur"){
                            return "Confirmer";
                        }else{

                            return "Valider";
                        }
                    })
                    ->requiresConfirmation()
                    ->color('success'),
//                Tables\Actions\EditAction::make(),
            ],
                position: ActionsPosition::BeforeColumns)
            ->headerActions([
                ExportAction::make()
                    ->exporter(AdherantExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('Activer_all')
                    ->label('Appruvé tous les membres eb attente')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {

                        foreach ($records as $record){


                            $record->status="APPROUVER";
                            $record->save();

                            $content = [
                                'subject' => 'Félicitations pour votre adhésion au parti FNF !',
                                'body' =>"Cher(e)". $record->prenom." ".  $record->nom." ,<br>
                            <p>Nous sommes ravis de vous informer que votre soumission en ligne pour devenir membre du parti FNF a été examinée et validée avec succès. À partir d'aujourd'hui, vous faites officiellement partie de notre parti.</p>

                            <p> Nous tenons à vous féliciter pour votre engagement et à vous remercier pour la confiance que vous nous accordez. Votre adhésion témoigne de votre soutien à nos valeurs et à nos objectifs communs.</p>

                            <p> En tant que membre du parti FNF, vous aurez l'opportunité de contribuer activement à nos initiatives et de participer à nos événements et activités. Nous sommes convaincus que votre engagement et vos idées apporteront une grande valeur à notre communauté.</p>

                            <p> Pour toute question ou pour plus d'informations sur vos prochains pas en tant que membre, n'hésitez pas à nous contacter à l'adresse suivante : [Adresse email de contact].</p>

                          <p>   Encore une fois, félicitations et bienvenue parmi nous !</p>

                            Cordialement,

                             <br>
                            "
                            ];
                            Mail::to([$record->email ])->send(new SampleMail2($content));

                        }
//                            redirect(static::getUrl('index'));

                    }),
                Tables\Actions\BulkActionGroup::make([

                ]),

            ])
            ->recordClasses(function (Adherant $record) {

                $class = match ($record->status) {

                    'EN ATTENTE DE VALIDATION' => 'bg-attent',
                    'EN ATTENTE DE CONFIRMATION' => 'bg-confirmation',
                    default => null,
                };

                error_log("Status: {$record->status}, Class: {$class}");

                return $class;
            });

    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembreAttentes::route('/'),
            'create' => Pages\CreateMembreAttente::route('/create'),
            'edit' => Pages\EditMembreAttente::route('/{record}/edit'),
            'view' =>  ViewAdherant::route('/{record}'),
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
    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if($user->role->name=="Administrateur" || substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){
            return true;
        }else{

            return false;
        }

    }
/*
    public function generateMemberCard(Adherant $member)
    {
        $img = Image::make(storage_path('app/public/carte_template/cate_tampate.jpeg'));

        $img->text($member->name, 150, 150, function($font) {
            $font->size(24);
            $font->color('#000');
            $font->align('center');
        });

        // Ajoutez les autres informations de la même manière

        $photo = Image::make(storage_path('app/public/' . $member->photo_identite))->resize(100, 100);
        $img->insert($photo, 'top-left', 50, 50);

        $img->save(storage_path('app/public/carte_membre/'.$member->id.'generated_card.jpg'));

        return response()->download(storage_path('app/public/carte_membre/'.$member->id.'generated_card.jpg'));
    }

*/

}
