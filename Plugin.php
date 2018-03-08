<?php

namespace Kanboard\Plugin\ExtendedMail;

use Kanboard\Core\Security\Role;
use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {

        $this->projectAccessMap->add('ExtendedMail', '*', Role::PROJECT_MANAGER);

        $this->route->addRoute('projects/:project_id/ExtendedMail', 'ExtendedMail', 'show', 'ExtendedMail');

        $this->template->hook->attach('template:project:sidebar', 'ExtendedMail:project/sidebar');
        $this->template->setTemplateOverride('comment_mail/create', 'ExtendedMail:comment_mail/create');
        $this->helper->register('MailTemplate', '\Kanboard\Plugin\ExtendedMail\Helper\MailTemplate');

    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'ExtendedMail';
    }

    public function getPluginDescription()
    {
        return t('adds functionalit to send comment by mail: custom reply to, templates ... ');
    }

    public function getPluginAuthor()
    {
        return 'Rens Sikma';
    }

    public function getPluginVersion()
    {
        return '0.8.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-ExtendedMail';
    }
}

