<?php

/**
 * This is the model class for table "{{ghost}}".
 *
 * The followings are the available columns in table '{{ghost}}':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $power
 * @property string $spawn_date
 * @property string $latitude
 * @property string $longitude
 * @property string $elevation
 * @property integer $owner_id
 *
 * The followings are the available model relations:
 * @property User $owner
 */
class Ghost extends CActiveRecord
{
	public $elevation;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ghost the static model class
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
		return '{{ghost}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, latitude, longitude', 'required'),
			array('power, owner_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('type', 'length', 'max'=>45),
			array('latitude, longitude, elevation', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, type, power, spawn_date, latitude, longitude, elevation, owner_id', 'safe', 'on'=>'search'),
			array('latitude, longitude', 'locationCheck'),
			array('latitude, longitude', 'uniqueLocationCheck'),
		);
	}

	public function locationCheck()
	{
		if (!$this->hasErrors())
		{
			if (($this->latitude == 0.000000) && ($this->longitude == 0.000000))
			{
				$this->addError('latitude', 'The Ghost is not placed in a valid location!');
			}
		}
	}
	
	public function uniqueLocationCheck()
	{
		if (!$this->hasErrors())
		{
			$model = Ghost::model()->find('latitude=:latitude AND longitude=:longitude AND elevation=:elevation', array(
				':latitude' 	=> $this->latitude,
				':longitude' 	=> $this->longitude,
				':elevation' 	=> $this->elevation,
			));
			
			if ($model)
				$this->addError('latitude', 'A Ghost already exists in that location!');
			//else
				//$this->addError('latitude', 'Something else '.round($this->latitude, 6).', '.round($this->longitude, 6));
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'owner' => array(self::BELONGS_TO, 'User', 'owner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'type' => 'Type',
			'power' => 'Power',
			'spawn_date' => 'Spawn Date',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'elevation' => 'Elevation',
			'owner_id' => 'Owner',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('power',$this->power);
		$criteria->compare('spawn_date',$this->spawn_date,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('elevation',$this->elevation,true);
		$criteria->compare('owner_id',$this->owner_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->owner_id   = Yii::app()->user->id;
				$this->spawn_date = date('Y-m-d H:m:s');
				
				if ($this->type == "blinky")
				{
					$this->name  = "Blinky";
					$this->power = 1;	
				}
				else if ($this->type == "inky")
				{
					$this->name  = "Inky";
					$this->power = 2;	
				}
				else if ($this->type == "pinky")
				{
					$this->name  = "Pinky";
					$this->power = 3;	
				}
				else if ($this->type == "clyde")
				{
					$this->name  = "Clyde";
					$this->power = 4;
				}
				
			} // if
			
			return true;
			
		} else
			return false;
	}
}