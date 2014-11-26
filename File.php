<?php

namespace omnilight\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "yz_files".
 *
 * @property integer $id
 * @property string $original_name
 * @property integer $file_size
 * @property string $created_at
 * @property string $updated_at
 */
class File extends ActiveRecord
{
    const SCENARIO_FILE_UPLOAD = 'fileUpload';

    /**
     * @var UploadedFile
     */
    public $fileUpload;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'fileRule' => [['fileUpload'], 'file', 'on' => self::SCENARIO_FILE_UPLOAD, ],
            [['file_size'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['original_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('omnilight/models', 'ID'),
            'original_name' => Yii::t('omnilight/models', 'Original Name'),
            'file_size' => Yii::t('omnilight/models', 'File Size'),
            'created_at' => Yii::t('omnilight/models', 'Created At'),
            'updated_at' => Yii::t('omnilight/models', 'Updated At'),
            'fileUpload' => Yii::t('omnilight/models', 'File'),
        ];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            if ($this->scenario == self::SCENARIO_FILE_UPLOAD) {
                if (!($this->fileUpload instanceof UploadedFile))
                    $this->fileUpload = UploadedFile::getInstance($this, 'fileUpload');
            }
            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->scenario == self::SCENARIO_FILE_UPLOAD) {
                $this->original_name = $this->fileUpload->name;
                $this->file_size = filesize($this->fileUpload->tempName);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->scenario == self::SCENARIO_FILE_UPLOAD) {
            $fileName = $this->getFileName();
            FileHelper::createDirectory(dirname($fileName));
            $this->fileUpload->saveAs($fileName);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return bool|string
     */
    public function getFileName()
    {
        return Yii::getAlias('@webroot/uploads/'.$this->id.'.'.$this->getFileExtension());
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }
}
