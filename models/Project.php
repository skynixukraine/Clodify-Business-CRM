<?php

namespace app\models;

use app\components\DateUtil;
use Yii;
use app\models\ProjectCustomer;
use yii\filters\RateLimiter;

/**
 * This is the model class for table "projects".
 *
 * @property integer $id
 * @property string $name
 * @property string $jira_code
 * @property integer $total_logged_hours
 * @property integer $total_paid_hours
 * @property string $status
 * @property string $date_start
 * @property string $date_end
 * @property integer $is_delete
 *
 * @property ProjectCustomers[] $projectCustomers
 * @property Users[] $users
 * @property ProjectDevelopers[] $projectDevelopers
 * @property Users[] $users0
 * @property Reports[] $reports
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_NEW        = "NEW";
    const STATUS_ONHOLD     = "ONHOLD";
    const STATUS_INPROGRESS = "INPROGRESS";
    const STATUS_DONE       = "DONE";
    const STATUS_CANCELED   = "CANCELED";


    public $customers;
    public $developers;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status', 'jira_code'], 'required'],
            [['customers', 'developers'], 'required', 'on' => 'admin'],
            [['total_logged_hours', 'total_paid_hours', 'is_delete'], 'integer'],
            [['status'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['jira_code'], 'string', 'max' => 15],
            [['customers', 'developers'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'name'              => 'Name',
            'jira_code'         => 'Jira Code',
            'total_logged_hours'=> 'Total Logged Hours',
            'total_paid_hours'  => 'Total Paid Hours',
            'status'            => 'Status',
            'date_start'        => 'Date Start',
            'date_end'          => 'Date End',
            'is_delete'         => 'Is Delete'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCustomers()
    {
        return $this->hasMany(ProjectCustomer::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('project_customers', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectDevelopers()
    {
        return $this->hasMany(ProjectDeveloper::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevelopers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('project_developers', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::className(), ['project_id' => 'id']);
    }

    /** Projects where role: DEV, user: current projects.is_delete = 0  */
    public static function getDevOrAdminOrPmProjects($userId)
    {
        return self::findBySql('SELECT projects.id, projects.name, projects.jira_code, project_developers.status,'.
            ' projects.status
            FROM projects
            LEFT JOIN project_developers ON projects.id=project_developers.project_id
            LEFT JOIN users ON project_developers.user_id=users.id AND (users.role=:role OR users.role=:roleA OR users.role=:roleP )
            WHERE users.id=:userId AND projects.is_delete = 0;
            GROUP by projects.id', [
            ':role'     => User::ROLE_DEV,
            ':roleA'     => User::ROLE_ADMIN,
            ':roleP'     => User::ROLE_PM,
            ':userId'   => $userId
        ])->all();
    }

    /** Save the  field’s value in the database */
    public function beforeSave($insert)
    {

        $this->date_start = DateUtil::convertData($this->date_start);

        $this->date_end = DateUtil::convertData($this->date_end);

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {

        $connection = Yii::$app->db;

        if($this->customers) {

            /* Delete from ProjectCustomers*/
            $connection->createCommand()
                ->delete(ProjectCustomer::tableName(), [
                    'project_id' => $this->id,
                ])
                ->execute();

            /* Add to ProjectCustomers*/
            foreach ($this->customers as $customer) {

                $connection->createCommand()
                    ->insert(ProjectCustomer::tableName(), [
                        'project_id' => $this->id,
                        'user_id' => $customer,
                    ])->execute();
            }
        }

        if($this->developers) {

            /* Delete from ProjectCustomers*/
            $connection->createCommand()
                ->delete(ProjectDeveloper::tableName(), [
                    'project_id' => $this->id,
                ])
                ->execute();

            /* Add to ProjectCustomers*/
            foreach ($this->developers as $developer) {

                $connection->createCommand()
                    ->insert(ProjectDeveloper::tableName(), [
                        'project_id' => $this->id,
                        'user_id' => $developer,
                    ])->execute();
            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public static function ProjectsCurrentUser($curentUser)
    {
        return self::find()
            ->leftJoin(ProjectCustomer::tableName(), ProjectCustomer::tableName() . '.project_id=id')
            ->leftJoin(ProjectDeveloper::tableName(), ProjectDeveloper::tableName() . '.project_id=id')
            ->where(Project::tableName() . '.is_delete=0 AND ' .
                    ProjectDeveloper::tableName() . '.user_id=' . $curentUser . ' AND ' .
                    ProjectDeveloper::tableName() . '.status IN ("' . ProjectDeveloper::STATUS_ACTIVE . '", "' . ProjectDeveloper::STATUS_INACTIVE . '")')
            ->all();
    }
}
