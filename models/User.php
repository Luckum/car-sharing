<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $role
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $firstname
 * @property string $midname
 * @property string $lastname
 * @property string $avatar
 * @property int $active
 * @property string $created_at
 *
 * @property BrigadeHasUser[] $brigadeHasUsers
 * @property UserProfile[] $userProfiles
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    const ROLE_ADMIN = 'administrator';
    const ROLE_MANAGER = 'manager';
    const ROLE_WORKER = 'worker';
    const ROLE_BRIGADIER = 'brigadier';
    
    protected $roleRu = [
        self::ROLE_ADMIN => 'Администратор',
        self::ROLE_MANAGER => 'Менеджер',
        self::ROLE_WORKER => 'Рабочий',
        self::ROLE_BRIGADIER => 'Бригадир'
    ];
    
    public $avatar_file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role'], 'string'],
            [['username', 'password', 'email', 'firstname', 'lastname'], 'required'],
            [['created_at'], 'safe'],
            [['username'], 'string', 'max' => 45],
            [['email', 'avatar'], 'string', 'max' => 50],
            [['firstname', 'midname', 'lastname'], 'string', 'max' => 100],
            [['active'], 'string', 'max' => 1],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password'], 'string', 'min' => 8, 'max' => 255],
            ['avatar_file', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'skipOnEmpty' => true, 'minSize' => 120*120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Роль',
            'username' => 'Имя пользователя (для входа в систему)',
            'password' => 'Пароль',
            'email' => 'Email',
            'firstname' => 'Имя',
            'midname' => 'Отчество',
            'lastname' => 'Фамилия',
            'avatar_file' => 'Аватар',
            'active' => 'Активен',
            'created_at' => 'Дата добавления',
            'fullNameColumnHtmlFormatted' => 'ФИО',
            'roleColumnHtmlFormatted' => 'Специализация',
            'brigadeColumnHtmlFormatted' => 'Бригада',
            'ticketsColumnHtmlFormatted' => 'Выполненных заявок за сутки / всего',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigadeHasUser()
    {
        return $this->hasOne(BrigadeHasUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }
    
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'active' => self::STATUS_ACTIVE]);
    }
    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'active' => self::STATUS_ACTIVE]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey()
    {
        return true;
    }
    
    public function validateAuthKey($authKey)
    {
        return true;
    }
    
    public function getRoleRu()
    {
        return $this->roleRu[$this->role];
    }
    
    public function getRoleRuByRole($role)
    {
        return $this->roleRu[$role];
    }
    
    public function getFullName()
    {
        return $this->lastname . ' ' . $this->firstname . ' ' . $this->midname;
    }
    
    public function getFullNameColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/user/snippets/fullname_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getRoleColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/user/snippets/role_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getBrigadeColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/user/snippets/brigade_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getTicketsColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/user/snippets/tickets_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getButtonsColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/user/snippets/buttons_col.php', [
            'model' => $this,
        ]);
    }
}
