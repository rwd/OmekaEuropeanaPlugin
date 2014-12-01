<?php 
$formAttributes = array(
    'action' => url('europeana'),
    'method' => 'GET',
    'class'  => 'europeana-search',
); 
?>
<?php echo $this->form('europeana-search-form', $formAttributes); ?>
    <?php echo $this->formText('q', $query, array('title' => __('Search keywords'), 'size' => 30)); ?>
    <?php echo $this->formButton('', __('Search'), array('type' => 'submit')); ?>
</form>
