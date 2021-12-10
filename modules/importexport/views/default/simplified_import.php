<?php

use app\modules\importexport\ImportexportModule;

$this->title = 'Egyszerűsített adatimportálás';

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
        
        <form method="post" action="/importexport/default/simplified-import-data" enctype="multipart/form-data">
            <div class="form-group">
              <label>Válassza ki az importálandó fájlt!</label>
              <input type="file" name="file" class="form-control" required />
            </div>
            
            <div class="form-group">
              <label>Mit tartalmaz a fájl?</label>
              <select class="form-control" name="type">
                  <option value="clients" selected>Ügyfelek</option>
                  <option value="products">Cikkek</option>
              </select>
              <div class="sample-data">
                  <a href="/modules/alapmodul/importexportportexport/sample/szitar_ugyfeltabla.xlsx"><i class="fas fa-download"></i> Minta letöltése (Excel)</a>
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
        var t = $("select[name='type']").val();
        $(".sample-data a").attr("href", t === 'products' ? '/modules/importexport/sample/szitar_cikktorzs.xlsx' : '/modules/importexport/sample/szitar_ugyfeltabla.xlsx');
    }, 300);
    
</script>