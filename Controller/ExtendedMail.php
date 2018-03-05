<?php

namespace Kanboard\Plugin\ExtendedMail\Controller;

use Kanboard\Controller\BaseController;

/**
 * ExtendedMail
 *
 * @package  Kanboard\Plugin\ExtendedMail\Controller
 * @author   Rens Sikma <rens@atcomputing.nl>
 */
class ExtendedMail extends BaseController
{
    /**
     * Show ExtendedMail settings
     *
     * @access public
     */
    public function show(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $this->response->html($this->helper->layout->project('ExtendedMail:config/show', array(
            'values'  => array (
                'mailTemplate_to'=>$this->helper->MailTemplate->Tto($project['id']),
                'mailTemplate_subject'=> $this->helper->MailTemplate->Tsubject($project['id']),
                'mailTemplate_reply_to'=> $this->helper->MailTemplate->Treply_to($project['id']),
                'mailTemplate_body'=> $this->helper->MailTemplate->Tbody($project['id'])
                ),

            'errors'  => $errors,
            'project' => $project,
            'members' => $this->projectPermissionModel->getMembersWithEmail($project['id']),
            'title'   => t('Project tags management'))));

    }
    /**
     * Validate and update a projectis ExtendedMail settings
     *
     * @access public
     */
    public function update()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        // TODO check permsions/verify
        $this->projectMetadataModel->save($project['id'], $values);
        $this->flash->success(t('Project updated successfully.'));

        return $this->show($values, array());

    }
}
