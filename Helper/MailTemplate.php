<?php


namespace Kanboard\Plugin\ExtendedMail\Helper;

use Kanboard\Core\Base;

/**
 * Class helper for mail temlates
 *
 * @package Kanboard\Plugin\ExtendedMail\Helper
 * @author  Rens Sikma <rens@atcomputing.nl>
 */
class MailTemplate extends Base
{

    # if you update you should also update variableExpansion
    /* public */
    const PATTERN = array ('%task_id', '%task_title', '%task_description',
                                    '%creator_id', '%creator_name', '%creator_email',
                                    '%assignee', '%assignee_name', '%assignee_email',
                                     '%project_id', '%project_name', '%project_email', '%user_name', '%user_email');
    public function getPattern(){
        return self::PATTERN;
    }


     /**
     * @access public
     * @param  array $project
     * @param  array $task
     * @param  string $msg
     * @return string
     */
    public function variableExpansion($project,$task,$msg) {
       $user = $this->userSession->getAll();

       $creator = $this->userModel->getById($task['creator_id']);
       // $owner = $this->userModel->getById($task['owner_id']);
       $assignee = $this->userModel->getById ($this->userModel->getIdByUsername ($task['assignee_username']));
       // TODO remove repetion ?/use transposer array
       $variables = array ($task['id'], $task['title'] , $task['description'],
                           $creator['id'], self::guessName($creator), $creator['email'],
                           $assignee['id'], self::guessName($assignee), $assignee['email'],
                           $project['id'], $project['name'], $project['email'],
                           $user['username'], $user['email'] );

        return str_replace(self::PATTERN ,$variables ,$msg);
    }

    public function guessName ($user){
        return empty($user['name']) ? $user['username'] : $user['name'];
    }

     /**
     * @param  integer $project_id
     * @return string
     */
    public function Treply_to($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_reply_to', '%user_email');
    }

     /**
     * @param  integer $project_id
     * @return string
     */
    public function Tsubject($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_subject', '');
    }

     /**
     * @param  integer $project_id
     * @return string
     */
    public function Tto($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_to', '');
    }

     /**
     * @param  integer $project_id
     * @return string
     */
    public function Tbody($project_id)
    {
        return $this->projectMetadataModel->get($project_id, 'mailTemplate_body', '');
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function subject($project,$task)
    {
        $subject_template = self::Tsubject ($project['id']);
        return self::variableExpansion($project, $task, $subject_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function reply_to($project,$task)
    {
        $reply_to_template = self::Treply_to($project['id']);
        return self::variableExpansion($project, $task, $reply_to_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function to($project,$task)
    {
        $to_template= self::Tto($project['id']);
        return self::variableExpansion($project, $task, $to_template);
    }

     /**
     * @param  array $project
     * @param  array $task
     * @return string
     */
    public function body($project,$task)
    {
        $body_template = self::Tbody($project['id']);
        return self::variableExpansion($project, $task, $body_template);
    }
}
