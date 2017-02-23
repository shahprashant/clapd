<?php
$this->breadcrumbs=array(
	'Claps',
);

$this->menu=array(
	array('label'=>'Create Clap', 'url'=>array('create')),
	array('label'=>'Manage Clap', 'url'=>array('admin')),
);
?>

<h1>Claps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
