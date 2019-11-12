<?php

use yii\web\View;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use backend\models\Plan;
use backend\models\Constancia;
use yii\helpers\Html;
use backend\models\ComisionMixtaCap;
use yii\widgets\ActiveForm;
use frontend\models\ContactForm;
use yii\captcha\Captcha;
use backend\models\Trabajador;
use backend\models\Catalogo;
use backend\models\PuestoEmpresa;

$this->title = 'Bienvenido establecimiento "' .strtoupper($model->NOMBRE_COMERCIAL).'"';

$this->registerJs("$('#help1').popover('hide');", View::POS_END, 'my-options1');
$this->registerJs("$('#help2').popover('hide');", View::POS_END, 'my-options2');

$this->params['subtitle'] = '';

$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-university fa-lg  fa-stack-1x"></i>
							   </span>';
$this->registerJs("$('#dataTable1').dataTable( {'language': {'url': '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json' }});", View::POS_END, 'my-options');


?>
				<!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>

                                        <?=  count(Trabajador::findBySql('select * from tbl_trabajador where id_empresa ='.$model->ID_EMPRESA.' AND activo = 1')->all()); ?>
                                    </h3>
                                    <p>

                                       Trabajadores en el establecimiento
                                    </p>
                                </div>
                                <div class="icon">
                   					 <i class="fa fa-users"></i>
                				</div>
                                <a class="small-box-footer" href="#anchor_comision">
                                  Más información <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>




                        <!-- ./col -->
                <!-- ./col -->
                    </div><!-- /.row -->


          <h3 class="page-header" id="anchor_comision">
           <i class="fa fa-users"></i>
        	<?= Yii::t('backend', 'Lista de trabajadores') ?> <small>dentro del establecimiento</small> </h3>

          </h3>

               <div class="row">

                     <div class="col-md-12 col-xs-12 col-sm-12">
                            <!-- AREA CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                   <i class="fa fa-eye"></i>
                                    <h3 class="box-title">Detalle de los trabajadores</h3>
                                     <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div>
                                </div>
                                <div class="box-body table-responsive">

                              		<table id="dataTable1" class="table table-condensed" cellspacing="0" >
							<thead>

								<tr>
									<th>Id</th>
									<th><?=Yii::t('backend', 'Nombre')?></th>
									<th><?=Yii::t('backend', 'RFC')?></th>
									<th><?=Yii::t('backend', 'CURP')?></th>
									<th><?=Yii::t('backend', 'NSS')?></th>

								<!-- 	<th>Obtención</th>
									<th>Tipo</th>

									<th>Promedio</th>
									<th>Aprobado</th> -->
									<th>Sexo</th>
									<th>Puesto</th>
									<th>Entidad federativa</th>
									<th>Delegación/Municipio</th>
									<th>Correo electronico</th>
									<th>Telefono</th>

									<th />

								</tr>
							</thead>
							<tbody>
							<?php $i = 0; foreach ($model->trabajadors as $worker) :?>


								<tr>
									<td ><?= $worker->ID_TRABAJADOR;?></td>
									<td><?= $worker->NOMBRE.' '. $worker->APP.' '.$worker->APM; ?></td>
									<td><?= $worker->RFC;?></td>
									<td><?= $worker->CURP;?></td>
									<td><?= $worker->NSS;?></td>
									<td><?= isset($worker->SEXO)? (($worker->SEXO == 1)?'MUJER':'HOMBRE' ):'no establecido';?></td>
									<td><?= isset($worker->pUESTO)?$worker->pUESTO->NOMBRE_PUESTO: ''?></td>
									<td><?php $entidad = Catalogo::findOne(['ID_ELEMENTO'=>$worker->ENTIDAD_FEDERATIVA,'CATEGORIA'=>1,'ACTIVO'=>1]);
												echo isset($entidad)?$entidad->NOMBRE:'no establecido';?></td>
									<td><?php $mpio =  Catalogo::findOne(['ID_ELEMENTO'=>$worker->MUNICIPIO_DELEGACION,'CATEGORIA'=>2,'ACTIVO'=>1]);
												echo isset($mpio)?$mpio->NOMBRE:'no establecido';?></td>
									<td><?= $worker->CORREO_ELECTRONICO;?></td>
									<td><?= $worker->TELEFONO;?></td>



								      <td>
												<?= Html::a('<i class="fa fa-eye"></i>', ['trabajador/view-by-sub', 'id'=>$worker->ID_TRABAJADOR],  [ 'class' => 'btn btn-info btn-xs' ] ) ?>


									</td>
								</tr>

							<?php  $i++; endforeach;?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="11"></td>
								</tr>
							</tfoot>
						</table>


                                </div><!-- /.box-body -->
                            </div><!-- /.box -->



                        </div>
                    </div>





    <h4 class="page-header">
    <i class="fa fa-question-circle"></i>

          Soporte y Ayuda
                        <small>Contenido de utilidad que le ayudara a operar el sistema</small>
    </h4>


                <div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title">Índice</h3>
                                     <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div id="accordion" class="box-group">
                                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                        <div class="panel box box-primary">
                                            <div class="box-header">
                                                <h4 class="box-title">
                                                    <a href="#collapseOne" data-parent="#accordion" data-toggle="collapse">

                                                     <h3>  <b> 1.1 Crear un nuevo  trabajador</b></h3>

                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse in" id="collapseOne">
                                                <div class="box-body">
	 												<h4>
	                                                	<i>Para crear un nuevo trabajador dentro de su establecimiento realice los siguientes pasos</i>
	                                                </h4>

	                                                <ol>
	                                                	<li>Clic en el menú <b>Trabajadores > Crear nuevo </b></li>
	                                                	<li>Ingrese los datos del trabajador en el formulario de captra</li>
	                                                	<li>Clic en el botón guardar para proceder</li>
	                                                </ol>

	                                                <ul><i>Notas: </i>
	                                                	<li>
	                                                		El proceso validara datos del trabajador como <b>RFC</b>
	                                                	</li>
	                                                	<li>
	                                                		No es posible guardar trabajadores con un mismo  <b>RFC</b>
	                                                	</li>
	                                                </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-primary">
                                            <div class="box-header">
                                                <h4 class="box-title">
                                                    <a href="#collapseTwo" data-parent="#accordion" data-toggle="collapse">
                                                       <h3>  <b>1.2 Eliminar un trabajador</b></h3>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseTwo">
                                                <div class="box-body">
                                              	 <h4>
	                                                	<i>Para eliminar un nuevo trabajador de su establecimiento realice los siguientes pasos</i>
	                                                </h4>

	                                                <ol>
	                                                	<li>Clic en el menú <b>Trabajadores > Administrar </b></li>
	                                                	<li>Ingrese los datos del trabajador en el formulario de captra</li>
	                                                	<li>Clic en el botón guardar para proceder</li>
	                                                </ol>

	                                                <ul><i>Notas: </i>
	                                                	<li>
	                                                		El proceso validara datos del trabajador como <b>RFC</b>
	                                                	</li>
	                                                	<li>
	                                                		No es posible guardar trabajadores con un mismo  <b>RFC</b>
	                                                	</li>
	                                                </ul>
                                                </div>
                                            </div>
                                        </div>

                                                 <div class="panel box box-primary">
                                            <div class="box-header">
                                                <h4 class="box-title">
                                                    <a href="#collapseThree" data-parent="#accordion" data-toggle="collapse">
                                                        <h3>  <b> 1.3 Actualizar un trabajador</b></h3>
                                                    </a>

                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseThree">
                                                <div class="box-body">
                                                <h4>

                                                <br>
                                                Artículo 16. Los Agentes Capacitadores Externos deberán solicitar su autorización y registro ante la Secretaría, así como el registro de los programas y cursos de capacitación que deseen impartir de conformidad con lo siguiente:
                                                </br>
                                                <br>I.	Cuando se trate de instituciones, escuelas u organismos especializados de capacitación deberán presentar el Formato DC-5 “Solicitud de Registro de Agente Capacitador Externo”, según modelo anexo y acompañar la siguiente documentación:</br>
                                                <br> II.	Cuando se trate de instructores independientes, deberán presentar el formato DC-5 “Solicitud de Registro de Agente Capacitador Externo”, según modelo anexo y la siguiente documentación:</br>
                                                <br>Artículo 17. Cuando se hayan presentado de forma personal los documentos correspondientes, la Secretaría entregará de forma inmediata el acuse de recibo correspondiente.
Si la solicitud se presentó por correo certificado o servicios de mensajería, el acuse de recibo será enviado al solicitante el día hábil siguiente a la fecha de recepción de la solicitud, devolviendo la documentación original que hubiese acompañado, de conformidad con lo establecido en el artículo 27, segundo párrafo del presente Acuerdo.
                                                </br>
                                                </h4>
                                              <br>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header">
                                                <h4 class="box-title">
                                                    <a href="#collapseFour" data-parent="#accordion" data-toggle="collapse">
                                                        <h3>  <b>1.4 Crear trabajadores desde archivo</b></h3>
                                                    </a>

                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseFour">
                                                <div class="box-body">
                                                <h4>

                                                <br>



<h4>Artículo 24. La constancia de competencias o de habilidades laborales deberá:<br>

<br><b>I.	Expedirse por:<br></b>

     <br>a.	La entidad instructora cuando se trate de agentes capacitadores externos;<br>

     <br>b.	Por la empresa, cuando se trate de instructores internos;<br>

     <br>c.	Las empresas de las que se haya adquirido un bien o servicio;<br>

     <br>d.	Extranjeros que impartan capacitación a trabajadores mexicanos en territorio nacional o cuando la capacitación se realice en el extranjero, y<br>

     <br>e.	Autoridades competentes de la Secretaría.<br>

<br><b>II.	Autentificarse por la Comisión Mixta de Capacitación, Adiestramiento y Productividad en las empresas con más de 50 trabajadores o por el patrón o representante legal en las empresas hasta con 50 trabajadores; en este último caso se omitirá la firma del representante de los trabajadores</b><br>

  <br>	La Comisión Mixta de Capacitación, Adiestramiento y Productividad por unanimidad de sus integrantes, podrá acordar la forma en que autentificará las constancias de habilidades laborales.
	Se podrá hacer uso de firmas en imagen digitalizada en sustitución de firmas autógrafas. En caso de elegir esta última opción, se deberán conservar en los archivos de la empresa, a disposición de la Secretaría, los convenios respectivos de la Comisión respecto del uso de las firmas autógrafas autorizadas para ser digitalizadas, así como las especificaciones para comprobar su veracidad y para garantizar su adecuado uso.<br>

	<br><b>III.	Entregarse a los trabajadores que:</b><br>

<br> a.	Aprueben el curso de capacitación, mediante la evaluación  correspondiente, dentro de los veinte días hábiles siguientes al término del mismo, o
<br> b.	Aprueben el examen de suficiencia, aplicado por el agente capacitador, cuando se nieguen a recibir capacitación.
<br><b>IV.	Elaborarse utilizando cualquiera de las siguientes opciones:</b><br>
<br> a.	El formato DC-3 “Constancia de competencias o de habilidades laborales”, según modelo anexo.<br>
<br>b.	El formato disponible en el sistema informático ubicado en la página de Internet www.stps.gob.mx.<br>

<br> De seleccionar esta opción, las empresas tendrán la posibilidad de emitir las constancias de competencias o de habilidades laborales de sus trabajadores a través del sistema informático, así como elaborar la lista de constancias de competencias o de habilidades laborales, incluyendo únicamente los datos faltantes.<br>
<br> c.	Un documento elaborado por la empresa al que se denominará “Constancia de Competencias o de Habilidades Laborales”, y que deberá contener, al menos, la información siguiente:<br>
<br>1.	Del trabajador: apellido paterno, materno y nombre(s); Clave Única de Registro de Población y ocupación específica en la empresa (según Catálogo);<br>
<br>2.	De la empresa: nombre o razón social (en caso de ser persona física anotar apellido paterno, materno y nombre(s) y Registro Federal de Contribuyentes con homoclave;<br>
<br>3.	Del programa de capacitación, adiestramiento y productividad: nombre del curso; duración en horas; periodo de ejecución; área temática del curso (según Catálogo);<br>
<br>4.	Nombre del Agente Capacitador Externo, cuando se trate de una institución, escuela u organismo; o nombre de la empresa cuando se trate de un instructor interno de la misma;<br>
<br>5.	Nombre y firma del instructor, en el caso de cursos a distancia, será suficiente anotar el nombre del tutor en línea, y<br>
<br>6.	Nombre y firma de los representantes de los trabajadores y de la empresa, integrantes de la Comisión Mixta de Capacitación, Adiestramiento y Productividad o en su caso del patrón o representante legal.<br>
<br>La información de los catálogos relativos a la ocupación específica del trabajador en la empresa y a las áreas temáticas del curso, para las empresas que no expidan las constancias a través del sistema informático, se encuentran disponibles en el propio sistema ubicado en la página de Internet www.stps.gob.mx, en caso contrario dichos catálogos se encuentran en el reverso del formato DC-3 “Constancia de Competencias o de Habilidades Laborales”, según modelo anexo .<br>
<br>Artículo 25. En todos los casos, se podrán incluir en las constancias de competencias o de habilidades laborales solamente los logotipos, imágenes o membretes que identifiquen a la empresa y, en su caso, al agente capacitador. A excepción de las constancias emitidas por la Secretaría, no se deberán utilizar imágenes, ni textos que identifiquen o hagan referencia a que la Secretaría avala el desarrollo, contenido o calidad de los cursos y/o que cuenta con el reconocimiento o validez por parte de la misma.<br>
<br>Artículo 26. Las empresas deberán hacer del conocimiento de la Secretaría, para su registro y control, las listas de las constancias de competencias o de habilidades laborales, que contendrán la información de la capacitación o adiestramiento otorgado a los trabajadores como resultado de las acciones realizadas conforme al plan y programas de capacitación, adiestramiento y productividad, tomando en consideración  lo siguiente:<br>
<br><b>I.	Dentro de los sesenta días hábiles posteriores al término de cada año de los planes y programas de capacitación, adiestramiento y productividad y al finalizar el mismo, aun cuando no haya cumplido un año completo, las empresas deberán presentar la información correspondiente a los siguientes rubros :</b><br>
<br>a.	Los datos generales de la empresa;<br>
<br>b.	La vigencia del plan y programas de capacitación, adiestramiento y productividad;<br>
<br>c.	Los datos generales del trabajador;<br>
<br>d.	La información de los cursos de capacitación recibidos por los trabajadores;<br>
<br>e.	Las certificaciones en Normas Técnicas de Competencia Laboral o su equivalente que, en su caso, comprueben tener los trabajadores, opcionalmente, y<br>
<br>f.	El grado máximo de estudios terminados con reconocimiento de validez oficial que los trabajadores proporcionen al patrón.<br>
	<br>Las empresas que tengan hasta 50 trabajadores podrán presentar su lista de constancias de competencias o de habilidades laborales por medios impresos o de forma electrónica.<br>
	Las empresas con más de 50 trabajadores deberán presentar su lista de constancias de competencias o de habilidades laborales, de forma electrónica.<br>
	Las empresas que no hayan registrado algún plan y programas de capacitación y adiestramiento de sus trabajadores ante la Secretaría, deberán observar lo establecido en el Artículo Primero Transitorio del presente Acuerdo.
	Cuando las empresas opten por realizar el trámite a través de medios electrónicos, deberán ingresar a la página de Internet de la Secretaría en la dirección www.stps.gob.mx, y seguir las instrucciones que se indiquen en la liga referente a la presentación de las listas de constancias de competencias o de habilidades laborales. En caso de realizarlo de manera personal, deberán presentar el formato DC-4 “Lista de constancias de competencias o de habilidades laborales”, según modelo anexo. De utilizar la primera opción, la información se incorporará a la base de datos de la Secretaría con el propósito de que en lo sucesivo sólo se hagan las actualizaciones correspondientes.<br>
<br><b>II.	De proceder la solicitud en tiempo y forma, la Secretaría emitirá un acuse de recibo el mismo día en que se realice la presentación de las listas de constancias, ya sea que ésta se efectúe en ventanilla o bien por medios electrónicos, en cuyo caso se proporcionará el acuse por esta misma vía;<br>
<br><b>III.	Las empresas deberán tener a disposición de la Secretaría, como parte de sus registros internos, copia de las constancias de competencias o de habilidades laborales expedidas a sus trabajadores durante el último año, ya  sea en papel o en archivos electrónicos que conserven la imagen de la constancia entregada, y<br>
<br><b>IV.	La Secretaría incluirá y administrará en la base de datos del Padrón de Trabajadores Capacitados, la información de los trabajadores presentada por las em
<br>presas en las listas de constancias de competencias o de habilidades laborales.</b><br>
<br>

</h4>
                                                </h4>


                                                </div>
                                            </div>
                                        </div>





                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>

                    </div>

                                 <div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-solid">
                                <div class="box-header">
                                	<i class="fa fa-envelope"></i>
                                    <h1 class="box-title">Contacto y soporte técnico</h1>
                                     <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div>
                                </div><!-- /.box-header -->
                                <div class="box-body">



                                	<address>
									  <strong> <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.nombre', '') ?></strong><br>
									  <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.direccion', '') ?><br>
									  <abbr title="Telefono de contacto">Tel:</abbr> <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.telefono', '') ?>
									</address>

									<address>
									  <strong>Correo electronico</strong><br>
									  <a href="mailto:#">  <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.correo', '') ?></a>
									  <br />


									</address>
									<h4>
									  <i class="fa fa-facebook-official"></i>
									  <a href="<?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.fb', '') ?>" target="blank"><?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.nombre', '') ?></a>
                                	&nbsp;
                                	&nbsp;

                                	<i class="fa fa-twitter"></i>
									  <a href="<?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.tw', '') ?>" target="blank"><?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.nombre', '') ?></a>
                                	</h4>
                                </div>
                                </div>
                                </div>
                                </div>