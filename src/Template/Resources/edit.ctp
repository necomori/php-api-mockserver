<?php
/** @var \App\Form\Resources\EditForm $form */
?>
<section class="content-header">
    <h1>
        <?php echo __('Resources'); ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <?= $this->Html->link('<i class="fa fa-reply"></i> ' . __('Back'), ['action' => 'index'], ['escape' => false]) ?>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-sign-in"></i> <?= __('New') ?></h3>
                </div>
                <?= $this->Form->create($form, ['role' => 'form', 'class' => 'form-horizontal']) ?>
                <div class="box-body">
                    <?= $this->Form->control('url', ['label' => __('URL')]) ?>
                    <?= $this->Form->control('method', ['label' => __('Request Method')]) ?>
                    <?= $this->Form->control('response', ['label' => __('Response')]) ?>
                </div>
                <div class="box-footer">
                    <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
