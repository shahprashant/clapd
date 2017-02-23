<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $category
 * @property integer $parent
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category, parent', 'required'),
			array('parent', 'numerical', 'integerOnly'=>true),
			array('category', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category, parent', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category' => 'Category',
			'parent' => 'Parent',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('parent',$this->parent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCategoryBrowseTree() 
	{
		$categories = array();
		
		$categoryRootReader = Yii::app()->db->createCommand()
		->select('*')
		->from('category c')
		->where('parent = 0')
		->order('category asc')
		->query();

		while(($categoryRootRow=$categoryRootReader->read())!==false) {
			$catid = $categoryRootRow['id'];
			$catText = $categoryRootRow['category'];
			$categories[$catid]['catText'] = $catText;
		}
		
		$categoryLeafReader = Yii::app()->db->createCommand()
		->select('*')
		->from('category c')
		->where('parent != 0')
		->order('category asc')
		->query();

		while(($categoryLeafRow=$categoryLeafReader->read())!==false) {
			$catid = $categoryLeafRow['id'];
			$catText = $categoryLeafRow['category'];
			$parentid = $categoryLeafRow['parent'];
			$categories[$parentid]['children'][] = array('catid' => $catid, 'catText' => $catText);
		}
		
		return ($categories);
		
	}
	
	public function getCategoryText($catid) 
	{
		$categoryReader = Yii::app()->db->createCommand()
						->select('*')
						->from('category c')
						->where('id = ' . $catid)
						->limit('1')
						->queryRow();
		
		$catText = $categoryReader['category'];
		return ($catText);
		
	}
		
}