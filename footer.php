</div>
    </div>

    <?php if ($totalPage > 1): ?>
        <nav aria-label="Navigation des pages">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1)? 'disabled':'' ?> "><a class="page-link" href="<?= ($page <= 1)? '#' : '?page='.($page - 1) ?>">Précédent</a></li>
                <?php for($i = 1; $i <= $totalPage; $i++): ?>
                    <li class="page-item <?= ($page == $i)? 'disabled':'' ?>"><a class="page-link" href="<?= ($page == $i)? '#' : '?page='.($i) ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPage)? 'disabled':'' ?>"><a class="page-link" href="<?= ($page >= $totalPage)? '#' : '?page='.($page + 1) ?>">Suivant</a></li>
            </ul>
        </nav>
    <?php endif; ?>


    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.fr.min.js"></script>
    <script src="../main.js"></script>
</body>
</html>