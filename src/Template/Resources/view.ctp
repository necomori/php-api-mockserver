<?php
/** @var \App\Model\Entity\Resource $resource */
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
                    <i class="fa fa-info"></i>
                    <h3 class="box-title"><?php echo __('Information'); ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->AppHtml->str($resource->id) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('URL') ?></th>
                            <td><?= $this->AppHtml->str($resource->url) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Request Method') ?></th>
                            <td><?= $this->AppHtml->str($resource->method) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Response') ?></th>
                            <td><?= $this->AppHtml->str($resource->response) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Created') ?></th>
                            <td><?= $this->AppHtml->datetime($resource->created) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?= $this->AppHtml->datetime($resource->modified) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
