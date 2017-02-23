<?php
$this->breadcrumbs=array(
	'Useractions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Useractions', 'url'=>array('index')),
	array('label'=>'Manage Useractions', 'url'=>array('admin')),
);
?>

<h1>Create Useractions</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>