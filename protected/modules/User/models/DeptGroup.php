<?php

/**
 * This is the model class for table "{{dept_group}}".
 *
 * The followings are the available columns in table '{{dept_group}}':
 * @property string $id
 * @property integer $dept_id
 * @property integer $group_id
 */
class DeptGroup extends CActiveRecord
{
        public $group_name;
        public $group_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{dept_group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dept_id, group_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dept_id, group_id', 'safe', 'on'=>'search'),
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
			'id' => '主键',
			'dept_id' => '部门id',
			'group_id' => '组别id',
			'group_name' => '组名',
			'dept_name' => '部门',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('dept_id',$this->dept_id);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getByDeptId($dept_id)
        {
            $deptid = intval($dept_id);
            $cir = new CDbCriteria();
            $cir->select = 'group_id';
            $cir->compare('dept_id', $dept_id);
            $groupobj = $this->findAll($cir);
            $res=null;
            if($groupobj)
            {
                $groupArr = array();
                foreach ($groupobj as $obj)
                {
                    $groupArr[]=$obj->group_id;
                }
                if($groupArr)
                {
                    $cir2 = new CDbCriteria;
                    $cir2->addInCondition('id', $groupArr);
                    $list = GroupInfo::model()->findAll($cir2);
                    $empty = new GroupInfo();
                    $empty->id=0;
                    $empty->name="请选择组别";
                    $list = array_merge(array($empty),$list);
                    $res = CHtml::listData($list, 'id', 'name');
                }
            }
           return $dept_id&&$res?$res:array(0=>'请选择组别');
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DeptGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
