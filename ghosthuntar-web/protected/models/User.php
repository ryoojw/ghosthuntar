<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $type
 * @property string $join_date
 * @property string $active
 * @property string $password
 * @property string $salt
 */
class User extends CActiveRecord
{
	public $confirm_password;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, name, password, confirm_password', 'required'),
			array('email, name, password', 'length', 'max'=>255),
			array('first_name, last_name, salt', 'length', 'max'=>125),
			array('type, active', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, name, first_name, last_name, type, join_date, active, password, salt', 'safe', 'on'=>'search'),
			array('email', 'email'),
			array('email', 'emailExists'),
			array('password', 'compare', 'compareAttribute' => 'confirm_password'),
		);
	}
	
	public function emailExists() {
		if (!$this->hasErrors())
			if ($this->emailExistValidator($this->email))
				$this->addError('email', 'Email has been taken.');
	}
	
	public function emailExistValidator($email) {
	
		$user = User::model()->find('LOWER(email)=?', array(strtolower($email)));
		
		if ($user === null)
			return false;
		
		return true;
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
			'email' => 'Email',
			'name' => 'Name',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'type' => 'Type',
			'join_date' => 'Join Date',
			'active' => 'Active',
			'password' => 'Password',
			'salt' => 'Salt',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('join_date',$this->join_date,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function validatePassword($password) { return $this->hashPassword($password, $this->salt) === $this->password; }
	
	public function hashPassword($password, $salt) { return md5($salt.$password); }
	
	public function generateSalt() { return uniqid('', true); }
	
	protected function beforeSave() {
	
		if (parent::beforeSave()) {
		
			if ($this->isNewRecord) {
			
				$this->active = 'yes';
				$this->salt = $this->generateSalt();
				$this->password = $this->hashPassword($this->password, $this->salt);
				$this->join_date = date("Y-m-d H:m:s");
				$this->type = 'player';
			}

			return true;
			
		} else
			return false;
	}
}