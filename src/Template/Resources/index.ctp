<?php
/** @var \App\Model\Entity\Resource[] $resources */
?>
<section class="content-header">
    <h1>
        <?php echo __('Resources'); ?>
        <div class="pull-right"><?= $this->Html->link(__('New'), ['action' => 'add'], ['class' => 'btn btn-success btn-xs']) ?></div>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-table"></i> <?= __('List') ?></h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('ID')) ?></th>
                            <th><?= $this->Paginator->sort('url', __('URL')) ?></th>
                            <th><?= $this->Paginator->sort('method', __('Request Method')) ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resources as $resource): ?>
                            <tr>
                                <td><?= $this->Number->format($resource->id) ?></td>
                                <td><?= $this->AppHtml->str($resource->url) ?></td>
                                <td><?= $this->AppHtml->str($resource->method) ?></td>
                                <td class="actions" style="white-space:nowrap">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $resource->id], ['class' => 'btn btn-info btn-xs']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $resource->id], ['class' => 'btn btn-warning btn-xs']) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $resource->id],
                                        ['confirm' => __('Are you sure you want to delete {0}?', $this->Number->format($resource->id)), 'class' => 'btn btn-danger btn-xs']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php echo $this->Paginator->numbers(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
