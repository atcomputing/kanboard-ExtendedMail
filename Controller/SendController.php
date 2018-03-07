<?php
namespace Kanboard\Plugin\ExtendedMail\Controller;

use Kanboard\Job\EmailJob;
use Kanboard\Controller\BaseController;

/**
 * Class for sending comments by mail
 *
 * @package Kanboard\Plugin\ExtendedMail\Controller
 * @author   Rens Sikma <rens@atcomputing.nl>
 */
class SendController extends \Kanboard\Controller\CommentMailController
{
    public function save()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();
        $values['task_id'] = $task['id'];
        $values['user_id'] = $this->userSession->getId();

        list($valid, $errors) = $this->commentValidator->validateEmailCreation($values);

        if ($valid) {

            $this->sendByEmail($values);
            unset($values['reply_to']);
            $values = $this->prepareComment($values);

            if ($this->commentModel->create($values) !== false) {
                $this->flash->success(t('Comment sent by email successfully.'));
            } else {
                $this->flash->failure(t('Unable to create your comment.'));
            }

            $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id']), 'comments'), true);
        } else {
            $this->create($values, $errors);
        }
    }

    protected function sendByEmail(array $values)
    {
        $html = $this->template->render('comment_mail/email', array('email' => $values));
        $emails = explode_csv_field($values['emails']);

        foreach ($emails as $email) {
            $this->emailClient->send(
                $email,
                $email,
                $values['subject'],
                $html,
                null,
                $values['reply_to']
            );
        }
    }
}
