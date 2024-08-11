<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhérer à la NFN</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <a class="nav-link" target="_blank" href="https://nfn.bj/">Accueil  </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="https://nfn.bj/qui-sommes-nous/">Qui sommes-nous?</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_blank"  href="https://nfn.bj/nous-contacter/">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
{{--<div class="container my-5" style="background-image: url({{asset('images/logo.png')}}),wi">--}}
<div class="container my-5"  >

    <div class="container mt-5">
        <h1 class="text-center" id="top">Confirmer un paiement</h1>
        <p class="text-center">Confirmer votre paiement en remplissant le formulaire ci-dessous.</p>





        <div id="show-alert-rejeter" style=" display: none" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>STATUS DE VOTRE ADHESION !</strong>   <span id="message-rejeter"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div id="show-alert-en-attente" style=" display: none" class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>STATUS DE VOTRE ADHESION!</strong>   <span id="message-attente"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <div id="show-alert-bravo" style="display: none" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>BRAVO !</strong>   <span id="message-bravo"></span>
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


        <form id="paiementForm" class="" enctype="multipart/form-data" >

             <input  type="hidden" value="{{csrf_token()}}" name="_token">
            <div class="card">
                <div class="card-header" style="background-color: #321717; color: white;">
                    Confirmation
                </div>
                <div class="card-body">

                    <div class="row g-3">

                        <div class="col">
                            <label for="adress" class="form-label">Type de transaction *</label>
                            <select id="type_transaction" name="type_transaction" class="form-select" aria-label="Default select example" required>
                                <option value="">---</option>
                                <option value="Cotisation mensuelle">Cotisation mensuelle</option>
                                <option value="carte membre">Carte de membre</option>
                                <option value="Don">Don</option>

                            </select>
                        </div>
                        <div class="col">
                            <label for="identifiant" class="form-label">Numéro d'ahession *</label>
                            <input type="text" name="identifiant" value="" class="form-control " id="identifiant" placeholder="Ex: 02GOG00089 " aria-label="Identifiant" required>
                        </div>

                        <div class="col">
                            <label for="ref_transaction" class="form-label">Référence de la transaction *</label>
                            <input type="text" name="ref_transaction" value="" class="form-control " id="ref_transaction" placeholder=" " aria-label="ref_transaction" required>
                        </div>

                        <div class="col">
                            <label for="identifiant" class="form-label">Montant(FCFA) *</label>
                            <input type="number" name="montant" value="" class="form-control " id="montant" placeholder="  " aria-label="Identifiant" required>
                        </div>

                    </div>


                    <div class="col" style="text-align: center">
                        <br>
                        <br>


                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #f06f19;">Valider</button>
                    </div>
                </div>
            </div>


        </form>




    </div>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>



</div>


<!-- Footer -->
<footer class="text-muted py-5 bg-black text-white">
    <div class="container text-center">
        <p class="mb-1 text-white">&copy; 2024 FNF BENIN</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#" class="text-white">Accueil</a></li>
            <li class="list-inline-item"><a href="#about" class="text-white">À propos</a></li>
            <li class="list-inline-item"><a href="#contact" class="text-white">Contact</a></li>
        </ul>
    </div>
</footer>

{{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}

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

        $('#identifiant').on('change', function() {

            // alert($('#identifiant').val())
            let formData = new FormData();

            formData.append('identifiant',$('#identifiant').val());
            document.getElementById('overlay').style.display = 'flex';
            var csrfToken = '{{csrf_token()}}';
            console.log(csrfToken); // Log the token to ensure it's correct

            $.ajax({
                url: "{{url('check_num_id')}}",
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function(result) {
                    console.log("Success:", result);


                    if (result.data==='valide'){
                        document.getElementById('overlay').style.display='none';
                    }else {
                        alert(result.message);
                        document.getElementById('overlay').style.display='none';
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    // alert("An error occurred: " + xhr.responseText);

                    console.log("Success:", xhr.responseText);
                    alert("Desolé le numero d'adhesion n'est pas valide");
                    document.getElementById('overlay').style.display='none';
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



        $('#paiementForm').on('submit', function(event) {
            event.preventDefault();
            // Initialiser les variables de validation
            let estValide = true;
            let messageErreur = '';

            // Réinitialiser les messages d'erreur
            $('#message').empty();

            // // Valider les termes et conditions
            // if (!$('#termsConditions').is(':checked')) {
            //     estValide = false;
            //     messageErreur += '<p>Vous devez accepter les termes et conditions.</p>';
            //     // Scroll to the target div
            //     $('html, body').animate({
            //         scrollTop: $('#top').offset().top
            //     }, 500, function(){
            //         // Set focus to targetDiv after scrolling is complete
            //         $('#top').focus();
            //     });
            // }



            if (estValide) {
                $('#show-alert').hide();
                // Créer un FormData object pour gérer les fichiers
                let formData = new FormData(this);

                // alert(JSON.stringify(formData));

                document.getElementById('overlay').style.display = 'flex';

                $.ajax({
                    url: '/store_paiement',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.success==true) {

                            document.getElementById('overlay').style.display='none';

                            if (response.message==="Votre paiement a été enregistre avec success"){

                                $('#message-bravo').html("<p>Votre paiement a été enregistré avec succès.</p>");
                                $('#show-alert-bravo').show();
                                $('#paiementForm')[0].reset();

                            }



                            if (response.message==="Ce numero d'adhession n'est pas valide"){

                                $('#message-rejeter').html("<p>Status : En Ce numero d'adhession n'est pas valide.</p>");
                                $('#show-alert-rejeter').show();
                                $('#statusForm')[0].reset();

                            }

                          // console.log(response);






                            // Scroll to the target div
                            $('html, body').animate({
                                scrollTop: $('#top').offset().top
                            }, 500, function(){
                                // Set focus to targetDiv after scrolling is complete
                                $('#top').focus();
                            });

                            showSuccessAnimation();

                            document.getElementById('overlay').style.display='none';
                        } else {
                            document.getElementById('overlay').style.display = 'none';
                            $('#message-rejeter').html('<p>Erreur lors de la soumission du formulaire.</p>');

                            $('#show-alert-rejeter').show();
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
                        document.getElementById('overlay').style.display='none';
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





        function showSuccessAnimation() {
            $('#successAnimation').fadeIn(500, function() {
                $(this).delay(2000).fadeOut(500);
            });
        }







    });


</script>





    </body>
</html>



