<?php
$this->breadcrumbs=array(
	'Useractions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Useractions', 'url'=>array('index')),
	array('label'=>'Create Useractions', 'url'=>array('create')),
	array('label'=>'Update Useractions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Useractions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Useractions', 'url'=>array('admin')),
);
?>

<h1>View Useractions #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userId',
		'clapsId',
		'type',
	),
)); ?>
