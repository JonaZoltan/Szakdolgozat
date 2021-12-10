<?php

use app\modules\importexport\ImportexportModule;

$this->title = 'Adatok importálása';

$this->params['breadcrumbs'][] = $this->title;

$error = Yii::$app->session->getFlash('error');
$success = intval(Yii::$app->session->getFlash('success', '0'));

?>

<div class="importexport-default-index">
    <h1><?=$this->title?></h1>
    
    <p>
    
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <?=$error?>
        </div>
            <?php if ($success): ?>
                <div class="alert alert-success">
                  <i class="fas fa-check-circle"></i> Sikeresen importálva <code><strong><?=$success?></strong></code> elem.
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (!$error && $success): ?>
            <div class="alert alert-success">
              <i class="fas fa-check-circle"></i>  Sikeresen importálva <code><strong><?=$success?></strong></code> elem.
            </div>
        <?php endif; ?>
        
        <form method="post" action="/importexport/default/import-data" enctype="multipart/form-data">
            <div class="form-group">
              <label>Válassza ki az importálandó fájlt!</label>
              <input type="file" name="file" class="form-control" required />
            </div>
            
            <div class="form-group">
              <label>Mit tartalmaz a fájl?</label>
              <select class="form-control" name="table_index">
                <optgroup label="Alap modul">
                <?php foreach (ImportexportModule::$tables as $index => $table): ?>
                    <?php if ($table['module'] === 'base'): ?>
                    <option value="<?=$index?>"><?=$table['name']?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                </optgroup>
                
                <optgroup label="CRM modul">
                <?php foreach (ImportexportModule::$tables as $index => $table): ?>
                    <?php if ($table['module'] === 'crm'): ?>
                    <option value="<?=$index?>"><?=$table['name']?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                </optgroup>
                
                <optgroup label="Gyártás modul">
                <?php foreach (ImportexportModule::$tables as $index => $table): ?>
                    <?php if ($table['module'] === 'production'): ?>
                    <option value="<?=$index?>"><?=$table['name']?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                </optgroup>
              </select>
              <div class="sample-data">
                  <a href="/importexport/default/export-data?table_index=0&csv=true"><i class="fas fa-download"></i> Minta letöltése</a>
              </div>
            </div>
            
            <div class="form-group">
              <label>Milyen formátumú a fájl?</label>
              <select class="form-control" name="csv">
                 <option value="csv_semicolon" selected>CSV (pontosvessző)</option>
                 <option value="csv">CSV (vessző)</option>
                 <option value="" >JSON</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Milyen műveletet szeretne végrehajtani?</label>
              <select class="form-control" name="operation">
                 <option value="create" selected>Új adatokat rögzíteni</option>
                 <option value="update">Meglévő adatokat módosítani</option>
              </select>
            </div>
            
            <p>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Importálás</button>
                </div>
                
            </p>
            
        </form>
        
    </p>
</div>

<script>
    
    setInterval(function () {
        $(".sample-data a").attr("href", "/importexport/default/export-data?table_index=" + $("[name='table_index']").val() + "&csv=" + $("[name='csv']").val());
    }, 300);
    
</script>