<div class="card">
    <div class="card-header">
        <strong>
            <?php
            $biblia->getNomeLivro($get['livro']);
            echo " " . $get['cap'];
            ?>
        </strong>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <?php $biblia->versiculo($get); ?>
        </table>
    </div>
</div>