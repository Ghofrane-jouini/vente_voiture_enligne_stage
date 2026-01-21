<!-- ===== Footer CSS ===== --> 
<style>
.footer {
    background-color: #0b0b0b;
    color: #eee;
    font-family: 'Poppins', sans-serif;
    padding: 50px 20px 20px 20px;
}

.footer h5, .footer h6 {
    color: #d4af37;
    margin-bottom: 12px;
    font-weight: bold;
}

.footer p, .footer li a {
    color: #aaa;
    font-size: 14px;
}

.footer a {
    text-decoration: none;
    transition: color 0.3s, transform 0.3s;
}

.footer a:hover {
    color: #d4af37;
    transform: translateY(-2px);
}

.footer ul {
    padding-left: 0;
    margin: 0;
}

.footer ul li {
    list-style: none;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.footer ul li i {
    margin-right: 8px;
    color: #d4af37;
}

.footer .links-section h6 {
    font-size: 16px;
    margin-bottom: 15px;
}

.footer .links-section ul li a {
    font-size: 14px;
    display: inline-block;
    position: relative;
}

.footer .links-section ul li a::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: #d4af37;
    transition: width 0.3s;
    position: absolute;
    bottom: -3px;
    left: 0;
}

.footer .links-section ul li a:hover::after {
    width: 100%;
}

.footer img.logo {
    display: block;
    margin-bottom: 10px;
    max-width: 120px;
    height: auto;
}

.footer .map-img {
    width: 100%;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    transition: transform 0.3s, box-shadow 0.3s;
}

.footer .map-img:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
}

.footer hr {
    margin: 25px 0;
    border-top: 1px solid #444;
}

.footer p.text-center {
    font-size: 13px;
    color: #aaa;
    margin-top: 10px;
}

.footer .row > div {
    display: flex;
    flex-direction: column;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer .row > div {
        text-align: center;
        margin-bottom: 20px;
    }
    .footer img.logo {
        margin: 0 auto 10px;
    }
}
</style>

<!-- ===== Footer HTML ===== -->
<footer class="footer bg-dark text-light">
    <div class="container">
        <div class="row">

            <!-- Logo + Nom -->
            <div class="col-md-4 mb-3">
                <img src="/stage/project/assets/uploads/logoMGAUTO.jpg" class="logo" alt="MG AUTO">
                <h5 class="mt-2">MG AUTO - TEBOURSOUK</h5>
                <p>Téléphone: 78 466 368</p>
            </div>

            <!-- Liens / Infos -->
            <div class="col-md-4 mb-3 links-section">
                <h6>Liens utiles</h6>
                <ul class="list-unstyled">
                    <li><i class="fa fa-home"></i><a href="/stage/project/index.php">Accueil</a></li>
                    <li><i class="fa fa-sign-in-alt"></i><a href="/stage/project/auth/login.php">Connexion</a></li>
                    <li><i class="fab fa-facebook-f"></i><a href="https://www.facebook.com/mgautoteboursouk" target="_blank">Facebook</a></li>
                </ul>
            </div>

            <!-- Carte clickable -->
            <div class="col-md-4 mb-3">
                <h6>Notre emplacement</h6>
                <a href="https://www.google.com/maps/place/MG+AUTO/data=!4m2!3m1!1s0x0:0xdec154d76875b213?sa=X&ved=1t:2428&hl=fr-TN&ictx=111" target="_blank">
                    <img src="/stage/project/assets/uploads/map-preview.png" alt="Emplacement MG AUTO" class="map-img">
                </a>
            </div>

        </div>

        <hr>

        <p class="text-center mb-0">&copy; 2025 MG AUTO - Tous droits réservés</p>
    </div>
</footer>

<!-- ===== Font Awesome ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
