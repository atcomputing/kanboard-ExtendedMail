<?php if ($this->user->hasProjectAccess('ExtendedMail', 'show', $project['id'])): ?>
    <li <?= $this->app->checkMenuSelection('Mail defaults') ?>>
        <?= $this->url->link(t('Extended Mail'), 'ExtendedMail', 'show',array ('plugin' => 'ExtendedMail','project_id' => $project['id'])) ?>
    </li>
<?php endif ?>
