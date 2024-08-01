<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhérer à la NFN</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

{{--    <link href="css/styles.css" rel="stylesheet">--}}

    <style>
        body {
            font-family: Arial, sans-serif;

        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .jumbotron {
            {{--background: url('{{asset("images/logo.png")}}') repeat  ;--}}
            {{--background-size:200px 200px;--}}
            /*background-size: cover;*/
            color: white;
            padding: 4rem 2rem;
        }

        .footer {
            background: #403934;
            color: white;
            padding: 2rem 0;
        }

        .footer a {
            color: white;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>

    <style>
        /* Style pour le message de succès */
        #successAnimation {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        .paper-piece {
            width: 200px;
            height: 100px;
            background: #4CAF50;
            border: 1px solid #fff;
            position: absolute;
            transform: scale(0);
            animation: paperEffect 1s forwards;
        }

        .paper-piece:nth-child(1) { top: 0; left: 0; }
        .paper-piece:nth-child(2) { top: 0; right: 0; }
        .paper-piece:nth-child(3) { bottom: 0; left: 0; }
        .paper-piece:nth-child(4) { bottom: 0; right: 0; }

        @keyframes paperEffect {
            to {
                transform: scale(1) rotate(360deg);
            }
        }


        .overlay {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Black background with opacity */
            z-index: 1000; /* Sit on top */
            justify-content: center; /* Center the spinner */
            align-items: center; /* Center the spinner */
        }

        .spinner {
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>


</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark_ bg-dark_" >
    <div class="container">
        <img src="{{asset('images/logo.png')}}" width="75" alt="">
        <a class="navbar-brand" href="#">NFN BENIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Accueil  </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header Section -->
<header class="jumbotron text-center  text-white s-section" style="background-color: #f06f19">
    <div class="container">
        <h1 class="display-4">Fiche d'adhésion au parti Nouvelle Force Nationale</h1>
{{--        <p class="lead">Ceci est une simple page d'accueil créée avec HTML, CSS et Bootstrap.</p>--}}
{{--        <a href="#about" class="btn btn-secondary btn-lg">En savoir plus</a>--}}
    </div>
</header>

<!-- Main Content -->
{{--<div class="container my-5" style="background-image: url({{asset('images/logo.png')}}),wi">--}}
<div class="container my-5"  >

    <div class="container mt-5">
        <h1 class="text-center" id="top">Adhérer à la NFN</h1>
        <p class="text-center">Rejoignez-nous en remplissant le formulaire ci-dessous.</p>





        <div id="show-alert" style=" display: none" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Attention!</strong>   <span id="message"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div id="show-alert2" style="display: none" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Bien!</strong>   <span id="message2"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <div class="overlay" id="overlay">
            <div class="spinner"></div>
        </div>

{{--        <div class="text-center">--}}
{{--            <div class="spinner-border" role="status">--}}
{{--                <span class="sr-only">Loading...</span>--}}
{{--            </div>--}}
{{--        </div>--}}

        <form id="adheranForm" class="  " enctype="multipart/form-data" >



             <input  type="hidden" value="{{csrf_token()}}" name="_token">
            <div class="card">
                <div class="card-header" style="background-color: #321717; color: white;">
                    Informations Personnelles
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Prénom" aria-label="Prénom" required>
                        </div>
                        <div class="col">
                            <label for="nom" class="form-label">Nom de famille</label>
                            <input type="text" name="nom" class="form-control" id="nom" placeholder="Nom de famille" aria-label="Nom de famille" required>
                        </div>
                        <div class="col">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control" id="date_naissance" placeholder="Date de naissance" aria-label="Date de naissance" required>
                        </div>
                        <div class="col">
                            <label for="adress" class="form-label">Genre</label>
                            <select id="niveau_instruction" name="genre" class="form-select" aria-label="Default select example" required>
                                <option value="NEANT">---</option>
                                <option value="MASCULIN">Masculin</option>
                                <option value="FEMININ">Feminin</option>

                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control " id="email" placeholder="Ex: example@gmail.com" aria-label="Email" required>
                        </div>
                        <div class="col">
                            <label for="telephone" class="form-label">Numéro de téléphone *</label>
                            <input type="tel" name="telephone" class="form-control" id="telephone" placeholder="Ex: 97602657" aria-label="Numéro de téléphone" required>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header" style="background-color: #321717; color: white;" required>
                    Adresse
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label for="departement-liste" class="form-label">Departement</label>
                            <select id="departement-liste" name="departement_id" class="form-select" aria-label="Default select example" required>
                                <option selected>Choisir departement</option>
                                @foreach($listeDepartemnt as $dep)
                                    <option value="{{$dep->id}}">{{$dep->libelle}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="commune-liste" class="form-label">Commune</label>
                            <select id="commune-liste" name="commune_id" class="form-select" aria-label="Default select example" required></select>
                        </div>
                        <div class="col">
                            <label for="arrondissement-liste" class="form-label">Arrondissement</label>
                            <select id="arrondissement-liste" name="arrondissement_id" required class="form-select" aria-label="Default select example"></select>
                        </div>
                        <div class="col">
                            <label for="quartier-liste" class="form-label">Quartier</label>
                            <select id="quartier-liste" name="quartier_id" class="form-select" required aria-label="Default select example"></select>
                        </div>
                    </div>

                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="adress" class="form-label">Votre adress</label>
                            <input type="text" name="adresse" class="form-control" id="adresse" placeholder="" aria-label="" required>
                        </div>
                        <div class="col">   </div>
                        <div class="col"></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header" style="background-color: #321717; color: white;">
                    Ambition politique *
                </div>
                <div class="card-body">

                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="adress" class="form-label">Niveau d'instruction</label>
                            <select id="niveau_instruction" name="niveau_instruction" class="form-select" aria-label="Default select example" required>
                                <option value="NEANT">---</option>
                                <option value="CEP">CEP</option>
                                <option value="BEPC">BEPC</option>
                                <option value="BAC">BAC</option>
                                <option value="LICENCE">LICENCE</option>
                                <option value="MASTER">MASTER</option>
                                <option value="DOCTORAT">DOCTORAT</option>
                                <option value="AUTRE">AUTRE</option>

                            </select>
                         </div>

                        <div class="col">
                            <label for="categorie_socio" class="form-label">Catégorie socio professionelle</label>
                            <select id="categorie_socio" name="categorie_socio" class="form-select" aria-label="Default select example" required>
                                <option value="NEANT">---</option>
                                <option value="Artisans">Artisans</option>
                                <option value="Employés">Employés</option>
                                <option value="Ouviriers">Ouviriers</option>
                                <option value="Agriculteurs exploitants">Agriculteurs exploitants</option>
                                <option value="Elèves">Elèves </option>
                                <option value="Enseignants">Enseignants </option>
                                <option value="Etudiants">Etudiants</option>
                                <option value="AUTRE">AUTRE</option>

                            </select>
                        </div>


                        <div class="col">
                            <label for="activite_profession" class="form-label">Activité/profession *</label>
                            <input type="text" name="activite_profession" class="form-control" id="activite_profession" placeholder=" " aria-label="Numéro de téléphone" required>

                        </div>
{{--                        <div class="col"></div>--}}
                    </div>

                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="ambition_politique" class="form-label">Ambition politique</label>
                            <textarea name="ambition_politique" required class="form-control" id="ambition_politique" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header" style="background-color: #321717; color: white;">
                    Pièce d'identité
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label for="photo_perso" class="form-label">Photo Personnelle (limit Mo) *</label>
                            <img id="image_photo_perso" style="display: none; width: 200px; height: 180px;border: solid; margin-bottom: 20px;"  >

                            <input name="photo_perso" class="form-control" type="file" id="photo_perso"  accept="image/png, image/jpeg" required >
                        </div>
                        <div class="col">
                            <label for="photo_id" class="form-label">Pièce d'identité  (limit Mo) *</label>
                            <img id="image_photo_id" style="display: none; width: 200px; height: 180px;border: solid;margin-bottom: 20px;"  >

                            <input name="photo_id" class="form-control" type="file" id="photo_id"  accept="image/png, image/jpeg" required>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-section">
                <h3>Conditions Générales</h3>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="termsConditions">
                    <label class="custom-control-label" for="termsConditions">J'accepte les termes et conditions</label>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-block" style="background-color: #f06f19;">Soumettre</button>
        </form>




    </div>








</div>

<!-- Footer -->
<footer class="text-muted py-5 bg-dark text-white">
    <div class="container text-center">
        <p class="mb-1">&copy; 2024 FNF BENIN</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#" class="text-white">Accueil</a></li>
            <li class="list-inline-item"><a href="#about" class="text-white">À propos</a></li>
            <li class="list-inline-item"><a href="#contact" class="text-white">Contact</a></li>
        </ul>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

{{--<script src="js/scripts.js"></script>--}}

<script>


    $(document).ready(function () {

        $('#show-alert').hide();
        $('#show-alert2').hide();

        $('#adheranForm').on('submit', function(event) {
            event.preventDefault();


            // Initialiser les variables de validation
            let estValide = true;
            let messageErreur = '';

            // Réinitialiser les messages d'erreur
            $('#message').empty();

            // Valider les termes et conditions
            if (!$('#termsConditions').is(':checked')) {
                estValide = false;
                messageErreur += '<p>Vous devez accepter les termes et conditions.</p>';
                // Scroll to the target div
                $('html, body').animate({
                    scrollTop: $('#top').offset().top
                }, 500, function(){
                    // Set focus to targetDiv after scrolling is complete
                    $('#top').focus();
                });
            }



            if (estValide) {
                $('#show-alert').hide();
                // Créer un FormData object pour gérer les fichiers
                let formData = new FormData(this);


                // alert(JSON.stringify(formData));

                document.getElementById('overlay').style.display = 'flex';

                $.ajax({
                    url: '/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.success==true) {


                            $('#message2').html("<p>Formulaire soumis avec succès!<br>Nous avons bien reçu votre demande d'adhésion au parti FNF via notre formulaire en ligne.</p>");
                            $('#show-alert2').show();
                            $('#adheranForm')[0].reset();



                            // Scroll to the target div
                            $('html, body').animate({
                                scrollTop: $('#top').offset().top
                            }, 500, function(){
                                // Set focus to targetDiv after scrolling is complete
                                $('#top').focus();
                            });

                            showSuccessAnimation();
                        } else {
                            document.getElementById('overlay').style.display = 'none';
                            $('#message').html('<p>Erreur lors de la soumission du formulaire.</p>');

                            $('#show-alert1').show();
                            // Scroll to the target div
                            $('html, body').animate({
                                scrollTop: $('#top').offset().top
                            }, 500, function(){
                                // Set focus to targetDiv after scrolling is complete
                                $('#top').focus();
                            });

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', errorThrown);
                        $('#show-alert').show();
                        $('#message').html('<p>Erreur lors de la soumission du formulaire.</p>');
                        $('#message').focus(); // Set focus to input2
                    }
                });
            } else {
                $('#show-alert').show();
                $('#message').html(messageErreur);

                // Scroll to the target div
                $('html, body').animate({
                    scrollTop: $('#top').offset().top
                }, 500, function(){
                    // Set focus to targetDiv after scrolling is complete
                    $('#top').focus();
                });
            }
        });


        $('#photo_perso').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_photo_perso').attr('src', e.target.result);
                    $('#image_photo_perso').css('display', 'block');
                }
                reader.readAsDataURL(file);
            } else {
                $('#image_photo_perso').css('display', 'none');
            }
        });


        $('#email').on('change', function() {

           // alert($('#email').val())

            // document.getElementById('overlay').style.display = 'flex';


            $.ajax({
                url: "{{url('check_email')}}",
                type: "POST",
                data: {
                    email:$('#email').val(),
                    _token: '{{csrf_token()}}'

                },
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function (result) {


                    alert("dd");
                }


            }) ;

               //  document.getElementById('overlay').style.display = 'none';
               // alert("test");
               //
               //  // if (result=="non"){
               //  //     $('#email').addClass('is-valid');
               //  // }else {
               //  //     $('#email').addClass('is-invalid');
               //  // }







        });


        $('#photo_id').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_photo_id').attr('src', e.target.result);
                    $('#image_photo_id').css('display', 'block');
                }
                reader.readAsDataURL(file);
            } else {
                $('#image_photo_id').css('display', 'none');
            }
        });


        function showSuccessAnimation() {
            $('#successAnimation').fadeIn(500, function() {
                $(this).delay(2000).fadeOut(500);
            });
        }


        //
    //     $("#adheranForm").submit(function(e){
    //         e.preventDefault(); //empêcher une action par défaut
    //         // var form_url = $(this).attr("action"); //récupérer l'URL du formulaire
    //         // var form_method = $(this).attr("method"); //récupérer la méthode GET/POST du formulaire
    //         var form_data = $(this).serialize(); //Encoder les éléments du formulaire pour la soumission
    //
    // alert( JSON.stringify(form_data));
    //       console.log(form_data);
    //
    //         $.ajax({
    //             url : "store",
    //             type: 'POST',
    //             data : form_data
    //         }).done(function(response){
    //             $("#res").html(response);
    //         });
    //     });
    //





        /*------------------------------------------
        --------------------------------------------
     Departement Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#departement-liste').on('change', function () {

            var id = this.value;

            $("#commune-liste").html('');
            $.ajax({
                url: "{{url('getCommuneByDepartId')}}",
                type: "POST",
                data: {
                    departement_id: id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#commune-liste').html('<option value="">-- choisir une commune --</option>');

                    // for(var a in result ){
                    //     alert(JSON.stringify(a))
                    // }

                    $.each(result.communes, function (key, value) {

                        $("#commune-liste").append('<option value="' + value.id + '">' + value.libelle + '</option>');
                    });
                    $('#arrondissement-liste').html('<option value="">-- Choisir un Arrondissement --</option>');
                }
            });
        });


        /*------------------------------------------
        --------------------------------------------
     Commune Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#commune-liste').on('change', function () {

            var id = this.value;

            $("#arrondissement-liste").html('');
            $.ajax({
                url: "{{url('getArrondissementByCommId')}}",
                type: "POST",
                data: {
                    commune_id: id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#arrondissement-liste').html('<option value="">-- Choisir un Arrondissement--</option>');

                    // for(var a in result ){
                    //     alert(JSON.stringify(a))
                    // }

                    $.each(result.arrondissements, function (key, value) {

                        $("#arrondissement-liste").append('<option value="' + value.id + '">' + value.libelle + '</option>');
                    });
                    $('#quartier-liste').html('<option value="">-- Choisir un quartier --</option>');
                }
            });
        });



        /*------------------------------------------
        --------------------------------------------
     Arrondissement Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#arrondissement-liste').on('change', function () {

            var id = this.value;

            $("#quartier-liste").html('');
            $.ajax({
                url: "{{url('getQuartierByArrondId')}}",
                type: "POST",
                data: {
                    arrondissement_id: id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#quartier-liste').html('<option value="">-- Choisir un quartier--</option>');

                    // for(var a in result ){
                    //     alert(JSON.stringify(a))
                    // }

                    $.each(result.quartiers, function (key, value) {

                        $("#quartier-liste").append('<option value="' + value.id + '">' + value.libelle + '</option>');
                    });
                    // $('#quartier-liste').html('<option value="">-- Choisir un quartier --</option>');
                }
            });
        });







    });


</script>

 >



    </body>
</html>



