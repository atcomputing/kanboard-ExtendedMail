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
            $this->send(
                $email,
                $email,
                $values['subject'],
                $html,
                $values['reply_to']
            );
        }
    }
    // TODO is better if could use send from /Mail/client.php
    /**
     * Send a HTML email
     *
     * @access public
     * @param  string  $recipientEmail
     * @param  string  $recipientName
     * @param  string  $subject
     * @param  string  $html
     * @param  string  $reply_to
     * @return Client
     */
    public function send($recipientEmail, $recipientName, $subject, $html,$replyto)
    {

        if (! empty($recipientEmail)) {
            $this->queueManager->push(EmailJob::getInstance($this->container)->withParams(
                $recipientEmail,
                $recipientName,
                $subject,
                $html,
                $this->getAuthorName(),
                $replyto
            ));
        }

        return $this;
    }

    // TODO is better if could use getAuthorName  from /Mail/client.php
    /**
     * Get author email
     *
     * @access public
     * @return string
     */
    public function getAuthorName()
    {
        $author = 'Kanboard';

        if ($this->userSession->isLogged()) {
            $author = e('%s via Kanboard', $this->helper->user->getFullname());
        }

        return $author;
    }
}
