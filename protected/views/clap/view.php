<?php
$this->breadcrumbs=array(
	'Claps'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Clap', 'url'=>array('index')),
	array('label'=>'Create Clap', 'url'=>array('create')),
	array('label'=>'Update Clap', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Clap', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Clap', 'url'=>array('admin')),
);
?>

<h1>View Clap #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'clap',
		'image',
		'userId',
		'categoryId',
		'rating',
		'createtime',
		'updatetime',
		'refreshtime',
	),
)); ?>
