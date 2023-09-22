<!-- File: templates/Articles/add.php -->

<h1>Add Article : Languages using $this->Form->control('_translations.en_GB.title').</h1>
<?php
echo $this->Form->create($article);
// Hard code the user for now.
echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => 1]);
?>
<fieldset>
    <legend>English</legend>
    <?= $this->Form->control('_translations.en_GB.title'); ?>
    <?= $this->Form->control('_translations.en_GB.body', ['rows' => '3']); ?>
</fieldset>
<fieldset>
    <legend>Spanish</legend>
    <?= $this->Form->control('_translations.es_AR.title'); ?>
    <?= $this->Form->control('_translations.es_AR.body'); ?>
</fieldset>
<?php
echo $this->Form->control('tag_string', ['type' => 'text']);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>