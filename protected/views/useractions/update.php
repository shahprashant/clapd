<?php
$this->breadcrumbs=array(
	'Useractions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Useractions', 'url'=>array('index')),
	array('label'=>'Create Useractions', 'url'=>array('create')),
	array('label'=>'View Useractions', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Useractions', 'url'=>array('admin')),
);
?>

<h1>Update Useractions <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>