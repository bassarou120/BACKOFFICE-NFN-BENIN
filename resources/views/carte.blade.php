<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte de Membre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .card {
            width: 600px;
            height: 300px;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }
        .card-info {
            flex: 1;
        }
        .card-info h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .card-info p {
            margin: 5px 0;
            color: #666;
        }
        .card-info .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
        .footer-icons {
            margin-top: 10px;
        }
        .footer-icons img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="card">
    <img src="{{ $photo }}" alt="Photo de membre">
    <div class="card-info">
        <h2>{{ $nom }}</h2>
        <p class="highlight">{{ $titre }}</p>
        <p>Carte de membre : <span class="highlight">{{ $numero_carte }}</span></p>
        <p>Expire le : <span class="highlight">{{ $date_expiration }}</span></p>
        <p>Commune : {{ $commune }}</p>
        <p>Arrondissement : {{ $arrondissement }}</p>
        <p>Village/Quartier : {{ $quartier }}</p>
        <p>Profession : {{ $profession }}</p>
        <p>Téléphone : {{ $telephone }}</p>
        <div class="footer-icons">
            <!-- Add social media icons -->
            <img src="/path/to/facebook_icon.png" alt="Facebook">
            <img src="/path/to/instagram_icon.png" alt="Instagram">
            <img src="/path/to/twitter_icon.png" alt="Twitter">
            <img src="/path/to/youtube_icon.png" alt="YouTube">
        </div>
    </div>
</div>
</body>
</html>
