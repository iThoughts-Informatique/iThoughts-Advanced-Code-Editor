=== iThoughts Advanced Code Editor ===
Contributors: Gerkin
Plugin URL: http://www.gerkindevelopment.net/en/portfolio/ithoughts-advanced-code-editor/
Tags: editor, editors, tool, tools, ace, ace editor, ide, help, programming, code, development, syntax, highlighting, syntax highlighting, check
Stable tag: 1.2.10
Requires at least: 3.3
Tested up to: 4.5
License: GPL3.0

Integrate the Code Editor Ace into your WordPress editors to help you write efficient code! Now provides code checking for your PHP files!

== Description ==

Writing code directly into your WordPress editors have never been so easy. [Ace](https://ace.c9.io/#nav=about), the famous Code Editor used by [Cloud9 Web-IDE](https://c9.io/), will perfectly fit your needs to be more effective and reduce coding errors. **It now comes with a code check process on PHP files, to never again crash your server down!**

= Features =
* Replace the Appearance and Plugin editor with ACE
* **Checks your code to avoid submission if syntax errors are found!**
* 34 themes to style your editor the way you'll love it
* Automatically adapt the syntax-highlight to the language of the file
* Automatically adjust editor size to fSnippetsit your screen and give you a bigger work-area
* Auto-completion for PHP, HTML, JavaScript and CSS
* Auto-close parenthesis, brackets, HTML tags, etc etc...
* Auto-indentation
* Syntax and code checking with markers in editor to easily find errors
* Possible to enable ace editor in client-side by a shortcode (usefull for forums with code tips)
* Works well with [iThoughts HTML Snippets](https://www.gerkindevelopment.net/en/portfolio/ithoughts-html-snippets/) to create re-usable pure HTML or hard-coded PHP snippets

Where WordPress says **Code Is Poetry**, iThoughts Advanced Code Editor reply **Code Is Calligraphy**

For more informations, please visit the [dedicated plugin page](http://www.gerkindevelopment.net/en/portfolio/ithoughts-advanced-code-editor/)

== Installation ==

Either install through admin panel (this is the eaiest way), or:

1. Upload `ithoughts-advanced-code-editor.zip` to the `/wp-content/plugins/` directory
2. Unzip the archive
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

This section is empty for now.

== Screenshots ==

1. Option page of iThoughts Advanced Code Editor with a test ACE editor
2. Edition of a plugin file
3. Information tooltip and completion suggestions in a theme CSS file 

== Changelog ==

= 1.2.8 =
* FIX Sometimes cleared the file content if query string empty

= 1.2.7 =
* FIX Add missing support of scrollto parameter in theme & plugin editors

= 1.2.5 =
* FIX CodeChecker handles correctly files ending with HTML template (last `?>` after last `<?php`)
* FIX Admin JS don't trigger errors anymore when there's no submit button.

= 1.2.4 =
* FIX clean forgotten var_dump

= 1.2.3 =
* FIX Deployer config file was mis-configured
* FIX Default options were not initialized, which can cause crashes

= 1.2.1 =
* UPD Changed deploy script

= 1.2.0 =
* NEW CodeChecker: Syntax validity checking
* UPD Client-Side shortcode
* UPD Scripts

= 1.0.2 =
* FIX Toxic update because of WordPress SVN silly

= 1.0.1 =
* FIX Explicit base resources path for ACE

= 1.0.0 =
* NEW Initial release


== Upgrade Notice ==

= 1.2.8 =
Correction of a critical fail

= 1.2.5 =
Improve behavior on PHP files ending with HTML template

= 1.2.4 =


= 1.2.3 =
IMPORTANT FIX! Please update, a mis-initialized var cause troubles!

= 1.2.0 =
Introducing CodeChecker: part 1. This module will check your PHP file to detect syntax errors
It will help you avoid crashing your site with typos or other syntax faults.

= 1.0.0 =
Initial release

