<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("js_source",  __('Javascript source', 'ajax-search-lite'), array(
			'selects'   => wd_asl()->o['asl_compatibility_def']['js_source_def'],
			'value'     => $com_options['js_source']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">
		<strong>Legacy</strong> scripts use <strong>jQuery</strong> and will be removed on the first 2022 release.
		<?php echo sprintf( __('<a target="_blank" href="%s">Read More</a>'),
			'https://documentation.ajaxsearchlite.com/compatibility-settings/javascript-compatibility' ); ?>
	</p>
</div>
<div class="item" wd-enable-on="js_source:jqueryless-nomin,jqueryless-min">
	<?php
	$o = new wpdreamsCustomSelect("script_loading_method", __('Script loading method', 'ajax-search-pro'), array(
			'selects'=>array(
				array('option'=>'Classic', 'value'=>'classic'),
				array('option'=>'Optimized (recommended)', 'value'=>'optimized'),
				array('option'=>'Optimized asynchronous', 'value'=>'optimized_async')
			),
			'value'=>$com_options['script_loading_method']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">
	<ul style="float:right;text-align:left;width:70%;">
		<li><?php echo __('<b>Classic</b> - All scripts are loaded as blocking at the same time', 'ajax-search-pro'); ?></li>
		<li><?php echo __('<b>Optimized</b> - Scripts are loaded separately, but only the required ones', 'ajax-search-pro'); ?></li>
		<li><?php echo __('<b>Optimized asnynchronous</b> - Same as the Optimized, but the scripts load in the background', 'ajax-search-pro'); ?></li>
	</ul>
	<div class="clear"></div>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("detect_ajax", __('Try to re-initialize if the page was loaded via ajax?', 'ajax-search-lite'),
		$com_options['detect_ajax']
	); ?>
	<p class='descMsg'>
		<?php echo __('Will try to re-initialize the plugin in case an AJAX page loader is used, like Polylang language switcher etc..', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<p class='infoMsg'>
		<?php echo __('You can turn some of these off, if you are not using them.', 'ajax-search-lite'); ?>
	</p>
	<?php $o = new wpdreamsYesNo("js_retain_popstate", __('Remember search phrase and options when using the Browser Back button?', 'ajax-search-lite'),
		$com_options['js_retain_popstate']
	); ?>
	<p class='descMsg'>
		<?php echo __('Whenever the user clicks on a live search result, and decides to navigate back, the search will re-trigger and reset the previous options.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("js_fix_duplicates", __('Try fixing DOM duplicates of the search bar if they exist?', 'ajax-search-lite'),
		$com_options['js_fix_duplicates']
	); ?>
	<p class='descMsg'>
		<?php echo __('Some menu or widgets scripts tend to <strong>clone</strong> the search bar completely for Mobile viewports, causing a malfunctioning search bar with no event handlers. When this is active, the plugin script will try to fix that, if possible.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("load_google_fonts", __('Load the <strong>google fonts</strong> used in the search options?', 'ajax-search-lite'),
		$com_options['load_google_fonts']
	); ?>
	<p class='descMsg'>
		<?php echo __('When <strong>turned off</strong>, the google fonts <strong>will not be loaded</strong> via this plugin at all.<br>Useful if you already have them loaded, to avoid mutliple loading times.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("load_scroll_js", "Load the scrollbar script?", array(
			'selects'=>array(
				array('option'=>'Yes', 'value'=>'yes'),
				array('option'=>'No', 'value'=>'no')
			),
			'value'=>$com_options['load_scroll_js']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class='descMsg'>
	<ul>
		<li>When set to <strong>No</strong>, the custom scrollbar will <strong>not be used at all</strong>.</li>
	</ul>
	</p>
</div>