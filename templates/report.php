<div class="wrap">
	<div id="ithoughts-advanced-code-editor-options" class="meta-box meta-box-50 metabox-holder">
		<div class="meta-box-inside admin-help">
			<div class="icon32" id="icon-options-general">
				<br>
			</div>
			<h2><?php

/**
 * @file Form to make a false positiive/negative report
 *
 * @author Gerkin
 * @copyright 2016 GerkinDevelopment
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.8
 */

 _e('Report', 'ithoughts-advanced-code-editor' ); ?></h2>
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets">
					<div id="normal-sortables" class=""><!--Old removed classes: "meta-box-sortables ui-sortable"-->
						<form action="<?php echo $ajax; ?>" method="post" class="simpleajaxform" data-target="update-response" post_text="<?php _e('Sending your report. Please wait...', 'ithoughts-advanced-code-editor' ); ?>">
							<div class="postbox">
								<div class="handlediv" title="Cliquer pour inverser."><br></div><h3 class="hndle"><span><?php _e('Report', 'ithoughts-advanced-code-editor' ); ?></span></h3>
								<div class="inside">
									<p><?php _e("Please fill the following form to report an error in the code check. If you want to hide pre-filled data, don't hesitate. No sensible informations, such as password, should be sent through this file. Please do appropriate checks.", "ithoughts-advanced-code-editor"); ?></p>
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label for="name"><?php _e('Your name (better if I have to contact you about this problem)', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["name"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="email"><?php _e('Your email', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["email"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="type"><?php _e('Type of report', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["type"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="file"><?php _e('Filename', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["file"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="code"><?php _e('Edited code', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["code"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="included"><?php _e('Previously included files', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["included"]; ?>
												</td>
											</tr>
											<tr>
												<th>
													<label for="comment"><?php _e('Please describe the problem', 'ithoughts-advanced-code-editor' ); ?>:</label>
												</th>
												<td>
													<?php echo $opts["comment"]; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<p>
								<input autocomplete="off" type="hidden" name="action" value="ithoughts_ace_report_send"/>
								<button name="submit" class="alignleft button-primary"><?php _e('Send report', 'ithoughts-advanced-code-editor' ); ?></button>
							</p>

						</form>
						<div id="update-response"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>