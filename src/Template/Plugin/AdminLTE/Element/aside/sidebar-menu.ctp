<ul class="sidebar-menu">
    <li class="header">NAVIGATION</li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-sitemap"></i>
            <span>Resources</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/resources'); ?>"><i class="fa fa-table"></i><?php echo __('List'); ?></a></li>
            <li><a href="<?php echo $this->Url->build('/resources/add'); ?>"><i class="fa fa-sign-in"></i><?php echo('New'); ?></a></li>
        </ul>
    </li>
</ul>
