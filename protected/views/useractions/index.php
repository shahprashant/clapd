<?php
$this->breadcrumbs=array(
	'Useractions',
);

$this->menu=array(
	array('label'=>'Create Useractions', 'url'=>array('create')),
	array('label'=>'Manage Useractions', 'url'=>array('admin')),
);
?>

<h1>Useractions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
