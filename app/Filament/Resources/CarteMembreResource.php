<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarteMembreResource\Pages;
use App\Filament\Resources\CarteMembreResource\RelationManagers;
use App\Mail\SampleMail2;
use App\Models\Adherant;
use App\Models\CarteMembre;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


use Filament\Tables\Enums\ActionsPosition;


//use Intervention\Image\Facades\Image;
use http\Url;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;


class CarteMembreResource extends Resource
{
    protected static ?string $model = Adherant::class;
    protected static ?string $navigationGroup = 'Gestion Adhérent / Membre';
    protected static ?string $navigationLabel="Carte Membre";
    protected static ?int $navigationSort=2;

    protected static ?string $label="Gestion Carte des Membres";
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected $listeners = ['refreshRelations' => '$refresh'];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn ::make('carte_membre')
                    ->label("Carte")
                    ->defaultImageUrl(url('/images/logo.png'))
                    ->width(300)
                    ->height(150)
                ,
                Tables\Columns\TextColumn::make('nom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('prenom')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('telephone')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (\Illuminate\Contracts\Database\Eloquent\Builder $query) {
                return $query->where('status','=',"APPROUVER");
            })
            ->actions([

//                ActionGroup::make([
//                    // ...
//                ])->icon('heroicon-m-ellipsis-horizontal')
//                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('Activate')

                    ->action(function ($record) {


                        // Load the base image
                        $img = Image::make(storage_path('app/public/carte_template/carte_template2.jpeg'));


                        // Add titre
                        $img->text($record->categorie, 360, 480, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(55);
                            $font->color('#000000');
                            $font->align('center');
                            $font->valign('top');

                        });

                        // Add prenom
                        $img->text($record->prenom, 360, 570, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(55);
                            $font->color('#000000');
                            $font->align('center');
                            $font->valign('top');

                        });

                        // Add nom
                        $img->text($record->nom, 360, 645, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-Semibold.ttf'));
                            $font->size(55);
                            $font->color('#000000');
                            $font->align('center');
                            $font->valign('top');

                        });


                      $photo =  Image::make(storage_path('app/public/' . $record->photo_identite));
                        $photo->resize(420, 355);
                         $img->insert($photo, 'top-left', 167, 97);



                        $img->text("Carte de membre N° : 00".$record->id, 850, 200, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });

                        $img->text("Expire le  : ". Carbon::now()->addYears(5)->format('d-M-Y'), 850, 280, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });


                        $img->text("Commune : ".$record->commune->libelle , 850, 360, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });

                        $img->text("Arrondissement : ".$record->arrondissement->libelle , 850, 440, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });

                        $img->text("Quartier / village : ".$record->quartier->libelle , 850, 520, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });

                        $img->text("Profession : ".$record->activite_profession  , 850, 600, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });

                        $img->text("Téléphone : +229".$record->telephone  , 850, 680, function($font) {
                            $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                            $font->size(50);
                            $font->color('#000000');
                            $font->align('left');
                            $font->valign('top');

                        });






                        // Add ima
//                        $img->text('A', 372, 272, function($font) {
////                            $font->file(public_path('fonts/YourFont.ttf'));
//                            $font->size(18);
//                            $font->color('#000000');
//                            $font->align('left');
//                            $font->valign('top');
//                        });
//                        $img->text('N° 01', 400, 200, function($font) {
////                            $font->file(public_path('fonts/YourFont.ttf'));
//                            $font->size(18);
//                            $font->color('#000000');
//                            $font->align('left');
//                            $font->valign('top');
//                        });

                        // Save the image
                        $img->save(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));

                        $record->carte_membre= '/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg';
                        $record->save();

             return response()->download(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));



                        redirect(static::getUrl('index'));

//                        return response()->download(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));

//                        dd($record,$record->nom , $record->commune-

//                        $image = ImageManager::imagick()->read(storage_path('app/public/carte_template/cate_tampate.jpeg'));
//
//                        $manager = new ImageManager(new Driver());
//                        $img = $manager->read(storage_path('app/public/carte_template/carte_template2.jpeg'));
//                        $img =  $manager->read(storage_path('app/public/carte_template/carte_template2.jpeg'));
//                        $img =ImageManager::imagick()->read(storage_path('app/public/carte_template/carte_template2.jpeg'));
//
//                        $img->text('The quick brown fox', 120, 100, function (FontFactory $font) {
//
//                            $font->size(70);
//                            $font->color('fff');
//                            $font->stroke('ff5500', 2);
//                            $font->align('center');
//                            $font->valign('middle');
//                            $font->lineHeight(1.6);
//                            $font->angle(10);
//                            $font->wrap(250);
//                        });
//
//                        $photo =  $manager->read(storage_path('app/public/' . $record->photo_identite))->resize(100, 100);
//                        $photo->resize(220, 210);
//
//
//                        $img->place($photo, 'top-left', 110, 160);
//
//                        $img->save(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));
//                        $record->carte_membre= '/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg';
//                        $record->save();



                        /*

                        $templatePath = storage_path('app/public/carte_template/MODEL_CARTE.docx');
                        // Charger le document Word existant
                        $templateProcessor = new TemplateProcessor($templatePath);
                        $templateProcessor->setValue("NUM", "00".$record->id );
                        $templateProcessor->setValue("PRENOM",$record->prenom );
                        $templateProcessor->setValue("NOM", $record->nom );
                        $templateProcessor->setValue("TITRE", $record->categorie );
                        $templateProcessor->setValue("COMMUNE", $record->commune->libelle );
                        $templateProcessor->setValue("ARRONDISSEMENT", $record->arrondissement->libelle );
//
                        $templateProcessor->setValue("QUARTIER", $record->quartier->libelle );
                        $templateProcessor->setValue("PROFESSION", $record->activite_profession );
                        $templateProcessor->setValue("TELEPHONE", $record->telephone );

                        // Sauvegarder le document mis à jour
                        $outputPath = storage_path('app/public/carte_membre/updated_document.docx');
                        $templateProcessor->saveAs($outputPath);
                */





//                        dd($record->nom , $record->commune->libelle);

                    } )
                    ->label("Régénérer")
                    ->icon('heroicon-o-identification')
                    ->requiresConfirmation()
                    ->color('success'),


                Tables\Actions\Action::make('Envoyer')

                    ->action(function ($record) {



                        // Save the image
//                        $img->save(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));

//                        $record->carte_membre= '/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg';


//                        return response()->download(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));


//                            $url=Url(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));

                        $url=Url( 'storage/'.$record->carte_membre);
                         $content = [
                             'subject' => 'Votre Carte de Membre NFN est Disponible !',
                             'body' =>"Cher(e)". $record->prenom." ".  $record->nom." ,<br>
                             <p>Nous sommes ravis de vous annoncer que votre carte de membre du parti NFN est désormais disponible.</p>

                             <p> En tant que membre officiel de notre parti, vous jouez un rôle crucial dans notre mission de promouvoir nos valeurs et nos objectifs communs. Votre engagement et votre soutien sont essentiels pour notre réussite collective.</p>

                                  <p>   Veuillez cliquer sur le lien ci-dessous pour télécharger votre carte de membre NFN : </br> ".$url." </p>
                                  <br>
                                  <img src='".$url." ' alt=''>

                        <p>Nous vous encourageons à conserver cette carte précieusement et à l'utiliser lors de nos événements et réunions.</p>


                             Cordialement,

                  <br>
                 "
                         ];
                         Mail::to([$record->email ])->send(new SampleMail2($content));




                    } )
                    ->label("Envoyer")
                    ->icon('heroicon-o-share')
                    ->requiresConfirmation()
                    ->color('primary'),









//                Tables\Actions\Action::make('Activate')
//
//                    ->action(function ($record) {
//
//                        /*
//                         $record->status="APPROUVER";
//                         $record->save();
//
//                         $content = [
//                             'subject' => 'Félicitations pour votre adhésion au parti FNF !',
//                             'body' =>"Cher(e)". $record->prenom." ".  $record->nom." ,<br>
//                             <p>Nous sommes ravis de vous informer que votre soumission en ligne pour devenir membre du parti FNF a été examinée et validée avec succès. À partir d'aujourd'hui, vous faites officiellement partie de notre parti.</p>
//
//                             <p> Nous tenons à vous féliciter pour votre engagement et à vous remercier pour la confiance que vous nous accordez. Votre adhésion témoigne de votre soutien à nos valeurs et à nos objectifs communs.</p>
//
//                             <p> En tant que membre du parti FNF, vous aurez l'opportunité de contribuer activement à nos initiatives et de participer à nos événements et activités. Nous sommes convaincus que votre engagement et vos idées apporteront une grande valeur à notre communauté.</p>
//
//                             <p> Pour toute question ou pour plus d'informations sur vos prochains pas en tant que membre, n'hésitez pas à nous contacter à l'adresse suivante : [Adresse email de contact].</p>
//
//                           <p>   Encore une fois, félicitations et bienvenue parmi nous !</p>
//
//                             Cordialement,
//
//                  <br>
//                 "
//                         ];
//                         Mail::to([$record->email ])->send(new SampleMail2($content));
// */
//
////                        $this->generateMemberCard($record);
//                        $member=$record;
//

//                        $manager = new ImageManager(new Driver());
            //    $img = Image::read(storage_path('app/public/carte_template/cate_tampate.jpeg'));
//
//
//                        // read image from file system
////                        $image = $manager->read('images/example.jpg');
////                        $image = ImageManager::imagick()->read(storage_path('app/public/carte_template/cate_tampate.jpeg'));
//                        $img =  $manager->read(storage_path('app/public/carte_template/carte_template2.jpeg'));
////                        $img =ImageManager::imagick()->read(storage_path('app/public/carte_template/cate_tampate.jpeg'));
////                        $img = Image::read(storage_path('app/public/carte_template/cate_tampate.jpeg'));
//
//                        $img->text($member->nom." ".$member->prenom, 100, 100, function(FontFactory $font) {
//                            $font->size(90);
////                            $font->color('#000');
//                            $font->align('center');
//                        });
//
////                        $img->text('Profession: ' . $member->nom, 150, 450, function($font) {
////                            $font->size(24);
////                            $font->color('#000');
////                            $font->align('center');
////                        });
//
////                        $img->text('Téléphone: ' . $member->telephone, 150, 500, function($font) {
////                            $font->size(24);
////                            $font->color('#000');
////                            $font->align('center');
////                        });
//
//                        // Ajoutez les autres informations de la même manière
//
//                        $photo =  $manager->read(storage_path('app/public/' . $member->photo_identite))->resize(100, 100);
//                        $photo->resize(220, 210);
//
//
//                        $img->place($photo, 'top-left', 110, 160);
//
//                        $img->save(storage_path('app/public/carte_membre/'.$member->id.'-'.$member->nom.'-carte.jpg'));
//                        $record->carte_membre= '/carte_membre/'.$member->id.'-'.$member->nom.'-carte.jpg';
//                        $record->save();
//
////                        return response()->download(storage_path('app/public/carte_membre/'.$member->id.'-'.$member->nom.'-carte.jpg'));
//
//
//
//
//                    } )
//                    ->label("Régénérer")
//                    ->icon('heroicon-o-identification')
//                    ->requiresConfirmation()
//                    ->color('success'),


            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([

                Tables\Actions\BulkAction::make('Regernerer')
                    ->label('Regerenere toute les cartes')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {

                        foreach ($records as $record){
                            // Load the base image
                            $img = Image::make(storage_path('app/public/carte_template/carte_template2.jpeg'));


                            // Add titre
                            $img->text($record->categorie, 360, 480, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(55);
                                $font->color('#000000');
                                $font->align('center');
                                $font->valign('top');

                            });

                            // Add prenom
                            $img->text($record->prenom, 360, 570, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(55);
                                $font->color('#000000');
                                $font->align('center');
                                $font->valign('top');

                            });

                            // Add nom
                            $img->text($record->nom, 360, 645, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-Semibold.ttf'));
                                $font->size(55);
                                $font->color('#000000');
                                $font->align('center');
                                $font->valign('top');

                            });


                            $photo =  Image::make(storage_path('app/public/' . $record->photo_identite));
                            $photo->resize(420, 355);
                            $img->insert($photo, 'top-left', 167, 97);



                            $img->text("Carte de membre N° : 00".$record->id, 850, 200, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            $img->text("Expire le  : ". Carbon::now()->addYears(5)->format('d-M-Y'), 850, 280, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });


                            $img->text("Commune : ".$record->commune->libelle , 850, 360, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            $img->text("Arrondissement : ".$record->arrondissement->libelle , 850, 440, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            $img->text("Quartier / village : ".$record->quartier->libelle , 850, 520, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            $img->text("Profession : ".$record->activite_profession  , 850, 600, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            $img->text("Téléphone : +229".$record->telephone  , 850, 680, function($font) {
                                $font->file(storage_path('app/public/carte_template/Montserrat-MediumItalic.ttf'));
                                $font->size(50);
                                $font->color('#000000');
                                $font->align('left');
                                $font->valign('top');

                            });

                            // Save the image
                            $img->save(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));

                            $record->carte_membre= '/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg';
                            $record->save();




                        }
                        redirect(static::getUrl('index'));

                    }),

                Tables\Actions\BulkAction::make('Envoyer')
                    ->label('Envoyer toute les cartes')
                    ->icon('heroicon-m-share')
                    ->openUrlInNewTab()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {

                        foreach ($records as $record){

//                            $url=Url(storage_path('app/public/carte_membre/'.$record->id.'-'.$record->nom.'-carte.jpg'));
                            $url=Url( 'storage/'.$record->carte_membre);


                            $content = [
                                'subject' => 'Votre Carte de Membre NFN est Disponible !',
                                'body' =>"Cher(e)". $record->prenom." ".  $record->nom." ,<br>
                             <p>Nous sommes ravis de vous annoncer que votre carte de membre du parti NFN est désormais disponible.</p>

                             <p> En tant que membre officiel de notre parti, vous jouez un rôle crucial dans notre mission de promouvoir nos valeurs et nos objectifs communs. Votre engagement et votre soutien sont essentiels pour notre réussite collective.</p>

                                  <p>   Veuillez cliquer sur le lien ci-dessous pour télécharger votre carte de membre NFN : </br> ".$url." </p>

                        <p>Nous vous encourageons à conserver cette carte précieusement et à l'utiliser lors de nos événements et réunions.</p>


                             Cordialement,

                  <br>
                 "
                            ];
                            Mail::to([$record->email ])->send(new SampleMail2($content));


                        }
//                        redirect(static::getUrl('index'));

                    }),

                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make("Carte membre")
                    ->schema([
                        ImageEntry::make('carte_membre')
                        ,
                        ImageEntry::make('piece_photo_identite'),

                    ])->columnSpan(2)
            ]);
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
            'index' => Pages\ListCarteMembres::route('/'),
            'create' => Pages\CreateCarteMembre::route('/create'),
//            'edit' => Pages\EditCarteMembre::route('/{record}/edit'),
        ];
    }
}
