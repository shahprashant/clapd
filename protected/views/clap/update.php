<?php
$this->breadcrumbs=array(
	'Claps'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Clap', 'url'=>array('index')),
	array('label'=>'Create Clap', 'url'=>array('create')),
	array('label'=>'View Clap', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Clap', 'url'=>array('admin')),
);
?>

<h1>Update Clap <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('clap/_form', array('model'=>$model)); ?>
