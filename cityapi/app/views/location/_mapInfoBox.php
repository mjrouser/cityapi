<?php
/*
    _mapInfoBox.php

    Copyright Stefan Fisk 2012.
*/
?>
<div class="arrow"></div>
<div class="location">
    <div class="name"><?php echo CHtml::link(CHtml::encode($location->name), array('//location/view', 'id' => $location->id)) ?></div>
    <ol class="projects">
        <?php
        foreach($location->projects as $project) {
            ?>
            <li class="project">
                <div class="name"><?php echo CHtml::link(CHtml::encode($project->name), array('//location/view', 'id' => $location->id, '#' => $project->slug)) ?></div>
                <div class="description"><?php echo CHtml::encode($project->description); ?></div>
            </li>
            <?php
        }
        ?>
    </ol>
</div>