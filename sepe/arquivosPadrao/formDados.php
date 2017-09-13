<form class="form-horizontal" action="fisica.php?acao=resultado" method="post">
    <fieldset>
        <!-- Form Name -->
        <legend>Form Name</legend>
        <!-- Prepended text-->
        <?php foreach($_POST["variaveis"] as $variavel):?>
        <div class="form-group">
            <label class="col-md-4 control-label" for="prependedtext"><?php echo $variavel; ?></label>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $variavel; ?></span>
                    <input id="<?php echo $variavel; ?>" name="<?php echo $variavel; ?>" class="form-control" placeholder="<?php echo $variavel; ?>" type="number">
                </div>
            </div>

        </div>
        <?php endforeach; ?>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Enviar Dados</label>
            <div class="col-md-4">
                <button id="singlebutton" class="btn btn-primary">Enviar</button>
            </div>
        </div>

    </fieldset>
</form>