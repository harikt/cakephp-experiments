<!-- File: templates/Articles/edit.php -->

<h1>Edit Article</h1>
<?php
echo $this->Form->create($article);
echo $this->Form->control('user_id', ['type' => 'hidden']);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '3']);
?>
<? /*
<fieldset>
    <legend>English</legend>
    <?= $this->Form->control('_translations.en_GB.title'); ?>
<?= $this->Form->control('_translations.en_GB.body', ['rows' => '3']); ?>
</fieldset>
*/ ?>
<fieldset>
    <legend>Spanish</legend>
    <?= $this->Form->control('_translations.es_AR.title'); ?>
    <?= $this->Form->control('_translations.es_AR.body'); ?>
</fieldset>
<?php
// echo $this->Form->control('body', ['rows' => '3']);
echo $this->Form->control('tags._ids', ['options' => $tags]);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>