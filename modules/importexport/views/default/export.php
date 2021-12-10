<?php

use app\modules\importexport\ImportexportModule;

$this->title = 'Adatok exportálása';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="importexport-default-index">
    <h1><?=$this->title?></h1>
    
    <p>
        
        <form method="get" action="/importexport/default/export-data">
            
            <div class="form-group">
              <label for="pwd">Mit szeretne exportálni?</label>
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

            </div>
            
            <div class="form-group">
              <label for="pwd">Milyen formátumban szeretne exportálni?</label>
              <select class="form-control" name="csv">
                 <option value="csv_semicolon" selected>CSV (pontosvessző)</option>
                 <option value="csv">CSV (vessző)</option>
                 <option value="" >JSON</option>
              </select>
            </div>
            
            <p>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Exportálás</button>
                </div>
                            
            </p>
            
        </form>
        
    </p>
</div>

