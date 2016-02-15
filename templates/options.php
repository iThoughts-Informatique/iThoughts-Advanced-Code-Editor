<div class="wrap">
	<div id="ithoughts-advanced-code-editor-options" class="meta-box meta-box-50 metabox-holder">
		<div class="meta-box-inside admin-help">
			<div class="icon32" id="icon-options-general">
				<br>
			</div>
			<h2><?php _e('Options', 'ithoughts-advanced-code-editor' ); ?></h2>
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets">
					<div id="normal-sortables" class=""><!--Old removed classes: "meta-box-sortables ui-sortable"-->
						<form action="<?php echo $ajax; ?>" method="post" class="simpleajaxform" data-target="update-response">

							<p><strong><?php _e("Note", 'ithoughts-advanced-code-editor' ); ?>:</strong>&nbsp;<?php _e("Labels in <span class=\"nonoverridable\">red</span> indicate global options, not overridable by tips.", 'ithoughts-advanced-code-editor' ); ?></p>

							<div class="postbox">
								<div class="handlediv" title="Cliquer pour inverser."><br></div><h3 class="hndle"><span><?php _e('Options', 'ithoughts-advanced-code-editor' ); ?></span></h3>
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label for="enable_shortcode"><?php _e('Enable client-side shortcode', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $optionsInputs["enable_shortcode"]; ?><label for="enable_shortcode"><?php _e('Use it with <em>[ace_editor lang="yourlang"]$your->code()[/ace_editor]</em>', 'ithoughts-advanced-code-editor' ); ?></label>
												</td>
												<td rowspan="4" id="ace_opts_preview"><textarea id="test_ace_editor">&lt;?php
	$text = (isset($_GET) &amp;&amp; isset($_GET["text"])) ? $_GET["text"] : "Hello World";
?&gt;
&lt;div&gt;&lt;?php echo $text; ?&gt;&lt;/div&gt;
&lt;h1&gt;
	&lt;em&gt;
		iThoughts Advanced Code Editor
	&lt;/em&gt;
&lt;/h1&gt;
&lt;style&gt;
div{
	text-align:center;
}
&lt;/style&gt;</textarea>
											</tr>
											<tr>
												<th>
													<label for="theme"><?php _e('Theme', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $optionsInputs["theme"]; ?>
												</td>
											</tr>
											<tr>
												<th rowspan="2">
													<label for="autocompletion"><?php _e('autocompletion', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $optionsInputs["autocompletion"]["autocompletion_ondemand"]; ?><label for="autocompletion_ondemand"><?php _e('Autocomplete on demande <em>(Press ctrl+space)</em>', 'ithoughts-advanced-code-editor' ); ?></label>
												</td>
											</tr>
											<tr>
												<td>
													<?php echo $optionsInputs["autocompletion"]["autocompletion_live"]; ?><label for="autocompletion_live"><?php _e('Live autocomplete <em>(suggest as you type)</em>', 'ithoughts-advanced-code-editor' ); ?></label>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<p>
								<input autocomplete="off" type="hidden" name="action" value="ithoughts_ace_update_options"/>
								<button name="submit" class="alignleft button-primary"><?php _e('Update Options', 'ithoughts-advanced-code-editor' ); ?></button>
							</p>

						</form>
						<div id="update-response" class="clear confweb-update"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>