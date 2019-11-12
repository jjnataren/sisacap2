<?php
use backend\models\EmpresaUsuario; 

?>
  
  <h3>
	  A QUÍEN CORRESPONDA
  </h3>
  
  
  <h4>
  	Buen día,  enviamos reporte DC4 correspondiente al periodo <i><?= $model->FECHA_INFORME; ?></i>
  </h4>
  <?php $companyModel = EmpresaUsuario::getMyCompany()->iDEMPRESA;?>
  
		<div class="col-md-6 col-xs-12">
            <div class="box box-primary">
                    <div class="box-body">
                    
                    <h2>Datos de la empresa</h2>
                    
                    <dl style='border-top-color:#3C8DBC; border-right-color:#3C8DBC; border-top-style: solid; background-color: #F4F4F4;  border-right-style: solid; font-family: "Times New Roman", Serif;'>
                        <dt style="color: Black; "><?= Yii::t('backend', 'Empresa') ?></dt>
                        <dd><b><?= $companyModel->NOMBRE_RAZON_SOCIAL ?></b></dd>

                        <dt><?= Yii::t('backend', 'RFC') ?></dt>
                        <dd><?= $companyModel->RFC; ?></dd>
                        
                        <dt><?= Yii::t('backend', 'Telefono contacto') ?></dt>
                        <dd><?= $companyModel->TELEFONO; ?>
					
						</dd>
                        
                        <dt><?= Yii::t('backend', 'Correo electronico') ?></dt>
						<dd><?= $companyModel->CORREO_ELECTRONICO; ?>
                                    
                    </dl>
                    
                    
                    
                </div><!-- /.box-body -->
         
            </div>
        </div>
        
        
        <b>Saludos cordiales</b>
        <br />
        <a href="#">SISACAP</a>    
        
        

  
