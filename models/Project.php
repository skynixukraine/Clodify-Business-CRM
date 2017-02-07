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
 * @property integer $cost
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
    public $invoice_received;
    public $is_pm;
    public $is_sales;
    public $alias =[];
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
            [['name', 'status'], 'required'],
            [['customers', 'developers','invoice_received', 'is_pm', 'is_sales'], 'required', 'on' => 'admin'],
            [['invoice_received', 'is_pm', 'is_delete', 'is_sales'], 'integer'],
            [['total_logged_hours', 'total_paid_hours'], 'number'],
            [['status'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['jira_code'], 'string', 'max' => 15],
            [['customers', 'developers', 'alias'], 'safe'],
            ['is_sales', function() {
                if ($user = User::findOne($this->is_sales)) {
                    if ($user->role == 'DEV') {
                        $this->addError('error', Yii::t('yii', 'Developer can not be sales'));
                    }
                }
            }]
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
            'is_delete'         => 'Is Delete',
            'alias'             => 'Alias',
            'cost'              => 'Cost'
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
    public static function getDevOrAdminOrPmOrSalesProjects($userId)
    {
        return self::findBySql('SELECT projects.id, projects.name, projects.jira_code, project_developers.status,'.
            ' projects.status
            FROM projects
            LEFT JOIN project_developers ON projects.id=project_developers.project_id
            LEFT JOIN users ON project_developers.user_id=users.id AND (users.role=:role OR users.role=:roleA OR users.role=:roleP OR users.role=:roleS )
            WHERE users.id=:userId AND projects.is_delete = 0 AND projects.status IN ("' . Project::STATUS_INPROGRESS. '", "' . Project::STATUS_NEW . '")
            AND project_developers.status IN ("' . ProjectDeveloper::STATUS_ACTIVE . '")
            GROUP by projects.id', [
            ':role'      => User::ROLE_DEV,
            ':roleA'     => User::ROLE_ADMIN,
            ':roleP'     => User::ROLE_PM,
            ':roleS'     => User::ROLE_SALES,
            ':userId'    => $userId
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

            foreach (User::allCustomers() as $customer) {
                if($this->invoice_received == $customer->id || in_array($customer->id, $this->customers)){
                    $connection->createCommand()
                        ->insert(ProjectCustomer::tableName(), [
                            'project_id' => $this->id,
                            'user_id' => $customer->id,
                            /*'receive_invoices' => 1,*///when add project to some user receive_invoices from project_customers = 1
                            'receive_invoices' => ($this->invoice_received==$customer->id),
                        ])->execute();
                }

            }
        }

        if ($this->developers) {

            /* Delete from ProjectCustomers*/
            $connection->createCommand()
                ->delete(ProjectDeveloper::tableName(), [
                    'project_id' => $this->id,
                ])
                ->execute();

            /* Add to ProjectDevelopers*/
            foreach (User::allDevelopers() as $developer) {
                if ($this->is_pm == $developer->id || (($this->is_sales == $developer->id) ) || in_array($developer->id, $this->developers)) {
                    $connection->createCommand()
                        ->insert(ProjectDeveloper::tableName(), [
                            'project_id' => $this->id,
                            'user_id' => $developer->id,
                            'is_sales' => ($this->is_sales == $developer->id),
                            'is_pm' => ($this->is_pm == $developer->id),
                            'alias_user_id' => isset($this->alias[$developer->id]) ? $this->alias[$developer->id] : null
                        ])->execute();
                }
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
                    ProjectDeveloper::tableName() . '.status IN ("' . ProjectDeveloper::STATUS_ACTIVE . '", "' . ProjectDeveloper::STATUS_INACTIVE . '") AND ' .
                    Project::tableName() . '.status IN ("' . Project::STATUS_INPROGRESS . '", "' . Project::STATUS_NEW . '")')
            ->all();
    }
    // Returns projects with all available statuses
    public static function ProjectsCurrentUserAllStatuses($curentUser)
    {
        return self::find()
            ->leftJoin(ProjectCustomer::tableName(), ProjectCustomer::tableName() . '.project_id=id')
            ->leftJoin(ProjectDeveloper::tableName(), ProjectDeveloper::tableName() . '.project_id=id')
            ->where(Project::tableName() . '.is_delete=0 AND ' .
                ProjectDeveloper::tableName() . '.user_id=' . $curentUser . ' AND ' .
                ProjectDeveloper::tableName() . '.status IN ("' . ProjectDeveloper::STATUS_ACTIVE . '", "' . ProjectDeveloper::STATUS_INACTIVE . '")')
            ->all();
    }
    public static function ProjectsCurrentClient($curentClient)
    {
        return self::find()
            ->leftJoin(ProjectCustomer::tableName(), ProjectCustomer::tableName() . '.project_id=id')
            ->leftJoin(ProjectDeveloper::tableName(), ProjectDeveloper::tableName() . '.project_id=id')
            ->where (Project::tableName() . '.is_delete=0 AND ' .
                ProjectCustomer::tableName() . '.user_id=' . $curentClient . ' AND ' .
                ProjectDeveloper::tableName() . '.status IN ("' . ProjectDeveloper::STATUS_ACTIVE . '", "' . ProjectDeveloper::STATUS_INACTIVE . '")')
            ->all();
    }

    public static function getClientProjects($clientId)
    {
        return self::find()
            ->leftJoin(  ProjectCustomer::tableName(), ProjectCustomer::tableName() . ".project_id=" . Project::tableName() . ".id")
            ->leftJoin(User::tableName(), User::tableName() . ".id=" . ProjectCustomer::tableName() . ".user_id")
            ->where(ProjectCustomer::tableName() . ".user_id=" . $clientId)
            ->andWhere(Project::tableName() . '.is_delete=0')
            ->groupBy('id')
            ->all();
    }
    public static function projectsName($userId)
    {
        return self::find()
            ->leftJoin(ProjectDeveloper::tableName(), ProjectDeveloper::tableName() . '.project_id=' . Project::tableName() . '.id')
            ->where(ProjectDeveloper::tableName() . '.user_id=:ID', [':ID' => $userId])
            ->all();
    }
    public function isInCustomers($user_id){
        if(!is_array($this->customers)){
            return false;
        }

        return in_array($user_id, $this->customers);
    }
    public function isInvoiced($user_id){
        if(!is_array($this->projectCustomers)){
            return false;
        }
        foreach ($this->projectCustomers as $projectCustomer){
            if($projectCustomer->receive_invoices && $projectCustomer->user_id == $user_id){
                return true;
            }
        }
        return false;
    }

    public function isInDevelopers($user_id){
        if(!is_array($this->developers)){
            return false;
        }

        return in_array($user_id, $this->developers);
    }
    public function isPm($user_id){
        if(!is_array($this->projectDevelopers)){
            return false;
        }
        foreach ($this->projectDevelopers as $projectDeveloper){
            if($projectDeveloper->is_pm && $projectDeveloper->user_id == $user_id){
                return true;
            }
        }
        return false;
    }
    public function isSales($user_id){
        if(!is_array($this->projectDevelopers)){
            return false;
        }
        foreach ($this->projectDevelopers as $projectDeveloper){
            if($projectDeveloper->is_sales && $projectDeveloper->user_id == $user_id){
                return true;
            }
        }
        return false;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

}
