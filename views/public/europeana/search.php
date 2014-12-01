<?php $pageTitle = __('Search Europeana ') . __('(%s total)', $totalResults); ?>
<?php echo head(array('title' => $pageTitle, 'bodyclass' => 'europeana search')); ?>

<h1><?php echo $pageTitle; ?></h1>

<?php echo $this->partial('europeana/search-form.php', array('query' => $query)); ?>

<?php if ($error): ?>
<p><strong><?php echo __("Error: {$error}"); ?></strong></p>

<?php elseif ($totalResults): ?>
<?php echo pagination_links(); ?>
<table id="search-results">
    <thead>
        <tr>
            <th><?php echo __('Record Type');?></th>
            <th><?php echo __('Title');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
        <tr>
            <td>
                <?php echo __(ucfirst(strtolower(metadata($record, 'type')))); ?>
            </td>
            <td>
                <a href="<?php echo metadata($record, 'edmIsShownAt'); ?>"><img src="<?php echo metadata($record, 'edmPreview'); ?>" class="image" alt="" /><?php echo metadata($record, 'title'); ?></a>
                <a href="<?php echo metadata($record, 'edmIsShownAt'); ?>"><?php echo metadata($record, 'title'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>
