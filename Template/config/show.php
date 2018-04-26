<div class="page-header">
    <h2><?= t('Template Mail') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('ExtendedMail', 'update', array('plugin' => 'ExtendedMail','project_id' => $project['id'], 'redirect' => 'edit')) ?>"   autocomplete="off" class="js-mail-form">
    <?= $this->form->csrf() ?>

    <fieldset>
    <?= $this->form->label(t('Email'), 'emails') ?>
    <?= $this->form->text('mailTemplate_to', $values, $errors, array('autofocus', 'tabindex="1"')) ?>

    <?php if (! empty($members)): ?>
        <div class="dropdown">
            <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-address-card-o"></i><i class="fa fa-caret-down"></i></a>
            <ul>
                <?php foreach ($members as $member): ?>
                    <li data-email="<?= $this->text->e($member['email']) ?>" class="js-autocomplete-email">
                        <?= $this->text->e($this->user->getFullname($member)) ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?= $this->form->label(t('Subject'), 'subject') ?>
    <?= $this->form->text('mailTemplate_subject',$values,$errors, array('tabindex="2"')) ?>

    <?php if (! empty($project['predefined_email_subjects'])): ?>
        <div class="dropdown">
            <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-archive"></i><i class="fa fa-caret-down"></i></a>
            <ul>
                <?php foreach (explode("\r\n", trim($project['predefined_email_subjects'])) as $subject): ?>
                    <?php $subject = trim($subject); ?>

                    <?php if (! empty($subject)): ?>
                        <li data-subject="<?= $this->text->e($subject) ?>" class="js-autocomplete-subject">
                            <?= $this->text->e($subject) ?>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?= $this->form->label(t('Reply to'), 'Reply to') ?>
    <?= $this->form->text('mailTemplate_reply_to', $values, $errors, array('tabindex=3')) ?>

    <?= $this->form->label(t('Comment'), 'description') ?>
    <?= $this->form->textEditor('mailTemplate_body', $values, $errors, array('tabindex' => 4)) ?>
    </fieldset>
    <?= $this->modal->submitButtons(array('tabindex' => 5)) ?>
</form>

<?= $this->form->label(t("available variables:"),"available variables:") ?>
<ul style="margin-left: 10px">

<?php foreach ($this->MailTemplate->getPattern ()as $p):?>
    <li style="display: inline;">
        <?= "$p, " ?>
    </li style="display: inline;">
<?php endforeach ?>
<ul>


