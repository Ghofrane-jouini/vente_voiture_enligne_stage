<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ══════════════════════════════════════════
   FOOTER — MG AUTO
══════════════════════════════════════════ */
.mg-footer {
    position: relative;
    background: #0f0e1a;
    overflow: hidden;
    margin-top: 80px;
}

/* Fond décoratif */
.mg-footer::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 60% at 10% 80%, rgba(168,85,247,.10) 0%, transparent 55%),
        radial-gradient(ellipse 50% 50% at 90% 20%, rgba(236,72,153,.08) 0%, transparent 55%);
    pointer-events: none;
}

/* Ligne dégradée en haut */
.mg-footer::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--pink), var(--rose), var(--purple), var(--indigo), var(--cyan));
}

.mg-footer-inner {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 56px 24px 28px;
}

.mg-footer-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr;
    gap: 48px;
    margin-bottom: 44px;
}

/* ── Colonne brand ── */
.mg-footer-brand img {
    height: 48px;
    width: 48px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(168,85,247,.3);
    margin-bottom: 14px;
}

.mg-footer-brand h5 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -.02em;
    margin-bottom: 8px;
}

.mg-footer-brand h5 span {
    background: linear-gradient(135deg, var(--pink), var(--purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.mg-footer-brand p {
    font-family: 'Outfit', sans-serif;
    font-size: .88rem;
    color: rgba(255,255,255,.45);
    display: flex;
    align-items: center;
    gap: 8px;
}

.mg-footer-brand p i {
    color: var(--purple);
    font-size: .8rem;
}

/* ── Titres colonnes ── */
.mg-footer-col h6 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-bottom: 18px;
    position: relative;
    padding-bottom: 10px;
}

.mg-footer-col h6::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 28px; height: 2px;
    background: linear-gradient(90deg, var(--pink), var(--purple));
    border-radius: 2px;
}

/* ── Liens ── */
.mg-footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.mg-footer-links li a {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'Outfit', sans-serif;
    font-size: .88rem;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    transition: color .22s, gap .22s;
    position: relative;
}

.mg-footer-links li a i {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(168,85,247,.1);
    color: var(--purple);
    font-size: .75rem;
    flex-shrink: 0;
    transition: background .22s, color .22s;
}

.mg-footer-links li a:hover {
    color: #fff;
    gap: 13px;
}

.mg-footer-links li a:hover i {
    background: linear-gradient(135deg, var(--pink), var(--purple));
    color: #fff;
}

/* ── Carte ── */
.mg-footer-map a {
    display: block;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(0,0,0,.4);
    transition: transform .28s, box-shadow .28s;
    position: relative;
}

.mg-footer-map a::after {
    content: '📍 Voir sur Maps';
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(168,85,247,.55);
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .85rem;
    opacity: 0;
    transition: opacity .28s;
    backdrop-filter: blur(4px);
}

.mg-footer-map a:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(168,85,247,.3); }
.mg-footer-map a:hover::after { opacity: 1; }

.mg-footer-map img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    display: block;
}

/* ── Divider ── */
.mg-footer-divider {
    border: none;
    border-top: 1px solid rgba(255,255,255,.07);
    margin: 0 0 22px;
}

/* ── Bottom bar ── */
.mg-footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.mg-footer-bottom p {
    font-family: 'Outfit', sans-serif;
    font-size: .8rem;
    color: rgba(255,255,255,.3);
    margin: 0;
}

.mg-footer-bottom p span {
    color: var(--pink);
}

.mg-footer-socials {
    display: flex;
    gap: 8px;
}

.mg-social-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.06);
    color: rgba(255,255,255,.5);
    font-size: .8rem;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,.08);
    transition: all .22s;
}

.mg-social-btn:hover {
    background: linear-gradient(135deg, var(--pink), var(--purple));
    color: #fff;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(168,85,247,.4);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .mg-footer-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    .mg-footer-inner { padding: 40px 18px 24px; }
    .mg-footer-bottom { justify-content: center; text-align: center; }
}
</style>

<!-- ══ FOOTER HTML ══ -->
<footer class="mg-footer">
    <div class="mg-footer-inner">
        <div class="mg-footer-grid">

            <!-- Brand -->
            <div class="mg-footer-brand mg-footer-col">
                <img src="/stage/project/assets/uploads/logoMGAUTO.jpg" alt="MG AUTO">
                <h5>MG <span>AUTO</span></h5>
                <p><i class="fas fa-map-marker-alt"></i> Teboursouk, Tunisie</p>
                <p style="margin-top:6px"><i class="fas fa-phone"></i> 78 466 368</p>
            </div>

            <!-- Liens utiles -->
            <div class="mg-footer-col">
                <h6>Liens utiles</h6>
                <ul class="mg-footer-links">
                    <li>
                        <a href="/stage/project/index.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li>
                        <a href="/stage/project/auth/login.php">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/mgautoteboursouk" target="_blank">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Carte -->
            <div class="mg-footer-col">
                <h6>Notre emplacement</h6>
                <div class="mg-footer-map">
                    <a href="https://www.google.com/maps/place/MG+AUTO/data=!4m2!3m1!1s0x0:0xdec154d76875b213?sa=X&ved=1t:2428&hl=fr-TN&ictx=111" target="_blank">
                        <img src="/stage/project/assets/uploads/map-preview.png" alt="Emplacement MG AUTO">
                    </a>
                </div>
            </div>

        </div>

        <hr class="mg-footer-divider">

        <div class="mg-footer-bottom">
            <p>&copy; 2025 MG <span>AUTO</span> — Tous droits réservés</p>
            <div class="mg-footer-socials">
                <a href="https://www.facebook.com/mgautoteboursouk" target="_blank" class="mg-social-btn">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="tel:78466368" class="mg-social-btn">
                    <i class="fas fa-phone"></i>
                </a>
                <a href="https://www.google.com/maps/place/MG+AUTO/data=!4m2!3m1!1s0x0:0xdec154d76875b213" target="_blank" class="mg-social-btn">
                    <i class="fas fa-map-marker-alt"></i>
                </a>
            </div>
        </div>
    </div>
</footer>