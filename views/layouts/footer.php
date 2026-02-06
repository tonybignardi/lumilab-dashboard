<?php
use yii\bootstrap5\Html;
?>
<!-- FOOTER -->
<footer class="footer mt-auto py-3">
  <div class="container">
    <div class="row d-flex justify-content-between align-items-center">
        <div class="col-3">
            <ul>
                <li><a href="equipe.php">Equipe</a></li>
                <li><a href="termos.php">Termos de Uso</a></li>
                <li><a href="#">Declaração de Privacidade</a></li>
                <li><a href="ajuda.php">Ajuda | FAQ</a></li>
            </ul>
        </div>
        <div class="col-3">
            <ul>
                <li><a target="_blank" href="https://www.instagram.com/seadufrgs">@seadufrgs</a></li>
                <li><a target="_blank" href="https://www.instagram.com/napeadufrgs">@napeadufrgs</a></li>
                <li><a href="mailto:lumina@sead.ufrgs.br">lumina@sead.ufrgs.br</a></li>
            </ul>
        </div>
        <div id="footer-logos" class="col-6 d-flex justify-content-end align-items-center">
            <a href="#">
                <?php echo Html::img('@web/assets/img/logos/logo_ufrgs.svg');?>
            </a>
            <a href="#">
            <?php echo Html::img('@web/assets/img/logos/logo_sead.svg');?>
            </a>
            <a href="#">
            <?php echo Html::img('@web/assets/img/logos/logo_napead.svg');?>
            </a>
            <a href="#">
                <?php echo Html::img('@web/assets/img/logos/logo_cnpq.svg');?>
            </a>
        </div>
    </div>
  </div>
</footer>


