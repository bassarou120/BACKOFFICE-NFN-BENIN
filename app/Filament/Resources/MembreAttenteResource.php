<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AdherantExporter;
use App\Filament\Resources\MembreAttenteResource\Pages;
use App\Filament\Resources\MembreAttenteResource\RelationManagers;
use App\Mail\SampleMail2;
use App\Models\Adherant;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\MembreAttente;
use App\Models\RoleCommune;
use Carbon\Carbon;
use Filament\Forms;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\Exports\Enums\ExportFormat;



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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

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
                Tables\Columns\TextColumn::make('email'),

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
                    ->toggleable(isToggledHiddenByDefault: true)
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
            ])
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

                        }else{

                            $record->status="EN ATTENTE DE CONFIRMATION";
                            $record->save();

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
            ], position: ActionsPosition::BeforeColumns)
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

//                dd($record->status  );

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informations Personnelles')
                    ->schema([
                        TextEntry::make('nom'),
                        TextEntry::make( 'prenom'   ),
                        TextEntry::make(  'date_naissance'   ),

                        TextEntry::make('telephone'),
                        TextEntry::make('email'),
                    ])->columns(3),
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
            'index' => Pages\ListMembreAttentes::route('/'),
            'create' => Pages\CreateMembreAttente::route('/create'),
            'edit' => Pages\EditMembreAttente::route('/{record}/edit'),
        ];
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

}
