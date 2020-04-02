=== Serious Duplicated Terms ===
Contributors: softmodeling, seriouswp
Tags: terms, taxonomy, duplicates, category, tag, merge, optimization, cleaning
Requires at least: 4.3
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Merge duplicated tags and categories to simplify and better organize the content and navigation of your site

== Description ==

Once a site starts growing, it always ends up with repeated tags and categories. Sometimes, you create a new category when you were before using a tag. Or you just forget about old tags and en up writing new ones with a very similar name.

This damages the organization your content and the quality of the user navigation on your site. 

This plugin helps you to quickly identify (too) similar tags and categories and gives you the option to merge them to simplify your site taxonomies. 

In the *Configuration* page you can define how close term names should be to be considered duplicates. 

In the *Analysis* page you can review the duplications and decide which ones to merge. 


== Installation ==

Install and Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Should I create a backup of my site before using the plugin? =

Yes. Always create a backup of your site before taking any action that modifies your site data

= Does the plugin work with custom taxonomies? =

No. Only the standard tag and categories taxonomies are taken into account

= Does the plugin reassign posts automatically? =

Yes. All posts tagged or assigned to categories that are merged are automatically reassigned to the merged term.

= Does the plugin automatically create redirections for the merged tags and categories? =

No. If you do index individual tag and category pages (not always recommended) you will need to create the redirections manually. 

= I see too many similar tags/categories in the analysis page =

Try checking the *Equal names only* in the *Configuration* page to avoid considering as similars tags/categories that are substring of other ones (which could trigger many results if you use very short term names).

= I have merged a term but it shows up again in the list of duplicates =

Some hosting provideres are very aggressive with their caching strategy and store the results of some queries to avoid recalculating them. This could produce this effect. Just ignore the duplicate, since it has been already fixed, and it will disappear on its own later on once the provider flushes the cache.

== Screenshots ==

1. Configuration page for the plugin
2. Analysis page showing the duplicated categories and tags

== Changelog ==

= 1.1.2 =
* Tested with WordPress 5.4

= 1.1.1 =
* Cleaned error messages

= 1.1 =
* Added option to signal as duplicates only terms with the same exact name

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.1.2 =
* Tested with WordPress 5.4

= 1.1 =
* Now you can reduce the number of false positives in large sites by enforcing equal names in the detection of duplicates