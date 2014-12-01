<?php $view = get_view(); ?>
<fieldset>
    <div class="field">
        <div class="two columns alpha">
            <label for="europeana_api_key" class="required"><?php echo __('API key'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <p class="explanation"><?php echo __("API key for the Europeana REST API. Obtain a key at: %s", '<a href="http://labs.europeana.eu/api/registration/">http://labs.europeana.eu/api/registration/</a>'); ?></p>
            <?php echo $view->formText('europeana_api_key', get_option('europeana_api_key')); ?>
        </div>
    </div>

    <div class="field">
        <div class="two columns alpha">
            <label for="europeana_query"><?php echo __('Query'); ?></label>
        </div>
        <div class="inputs five columns omega">
            <p class="explanation"><?php echo __("A query to send to the Europeana API, in addition to any user-entered terms. Use this to limit the results returned, e.g. to those from a particular provider.");?></p>
            <?php echo $view->formText('europeana_query', get_option('europeana_query')); ?>
        </div>
    </div>
</fieldset>
