<?php

use app\modules\users\models\User;
use app\modules\importexport\ImportexportModule;
use app\modules\settings\models\Settings;

$this->title = 'Biztonsági mentés';

$this->params['breadcrumbs'][] = $this->title;

$user = User::current();

?>

<div class="importexport-default-index">
    <h1><?=$this->title?></h1>
    
    <p data-text>
    <i class="fas fa-info-circle"></i>
    <?=Settings::get(1)?>
    <br><br>
    <?php if ($user->is_admin): ?>
    <a href="javascript:void(0)" onclick="$('[data-edit-text]').show();$('[data-text]').hide();" style="color: #888!important"><i class="fas fa-pen"></i> Szöveg szerkesztése</a>
    <?php endif; ?>
    </p>
    
    <form method="post" action="/importexport/default/change-backup-text" data-edit-text style="display: none">
        <div class="form-group">
            <textarea name="text" class="form-control" rows="4"><?=Settings::get(1)?></textarea>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Mentés</button>
        </div>
    </form>
    
    <hr style="border-bottom: 1px solid #ddd; width: 100%" />
    
    <p>
        <form method="get" action="/importexport/default/backup-data">
            
            <!--
            <div class="form-group">
              <label for="pwd">Milyen formátumban szeretné elkészíteni a biztonsági mentést?</label>
              <select class="form-control" name="format">
                 <option value="json" selected>JSON</option>
              </select>
            </div>
            -->
            
            <p>

                <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Teljes biztonsági mentés készítése</button>
                </div>
                            
            </p>
            
        </form>
    
    </p>
    
</div>