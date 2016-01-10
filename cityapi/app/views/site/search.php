<?php
/*
    index.php

    Copyright Stefan Fisk 2012
*/

/* @var $errorMessage array */
/* @var $results array */

if (!isset($errorMessage)) $errorMessage = false;

$count = isset($results) ? count($results) : 0;

$this->layout = '//layouts/main';

// Error Message

if ($errorMessage) {
    $this->pageTitle = Yii::t('app', 'Search failed');

    $this->beginWidget('application.widgets.OuterBoxWidget', array(
        'class' => 'results',
        'title' => Yii::t('app', 'Search failed'),
    ));
    ?>
        <div class="error-message">
            <?php echo CHtml::encode($errorMessage); ?>
        </div>
    <?php
    $this->endWidget();

    return;
}

// No results

if (0 === $count) {
    $this->pageTitle = Yii::t(
        'app',
        'No results found for: {query}',
        array(
            '{query}' => $query,
        )
    );

    $this->beginWidget('application.widgets.OuterBoxWidget', array(
        'class' => 'results',
        'title' => Yii::t(
            'app',
            'No results found for <span class="query">"{query}"</span>',
            array(
                '{query}' => $query,
            )
        ),
    ));
    ?>
        <div class="no-results-message">
            <?php
            echo Yii::t(
                'app',
                'No results found for <span class="query">"{query}"</span>',
                array(
                    '{query}' => $query,
                )
            );
            ?>
        </div>
    <?php
    $this->endWidget();

    return;
}

// Results

$this->pageTitle = Yii::t(
    'app',
    'Found {count} results for "{query}"', array(
        '{query}' => $query,
        '{count}' => $count,
    )
);

$this->beginWidget('application.widgets.OuterBoxWidget', array(
    'class' => 'results',
    'title' => Yii::t('app', 'Found {count} results for <span class="query">"{query}"</span>', array(
        '{query}' => $query,
        '{count}' => $count,
    )),
));
    ?>
    <ol>
        <?php
        foreach ($results as $result) {
            ?>
            <li>
                <div class="result">
                    <h2><?php echo CHtml::link($result['name'], $result['url']); ?></h2>
                    <div class="description"><?php echo CHtml::encode($result['description']); ?></div>
                    <div class="url"><?php echo CHtml::encode($this->createAbsoluteUrl($result['url'])); ?></div>
                </div>
            </li>
            <?php
        }
        ?>
    </ol>
<?php
$this->endWidget();
