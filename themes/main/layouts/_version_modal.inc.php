
<div class="modal" id="version-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
	            <h5 class="modal-title"><?= Yii::t('app', 'szitar_system_version') ?>: <b>v<?=$version['number']?> </b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= Yii::t('app', 'close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
	            <?= str_replace("\n", "<br>", $version['description']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'close') ?></button>
            </div>
        </div>
    </div>
</div>