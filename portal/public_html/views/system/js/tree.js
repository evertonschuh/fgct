jQuery(document).ready(function(){
	
	jQuery.fn.extend({
		treeview:	function() {
			return this.each(function() {
				// Initialize the top levels;
				var tree = jQuery(this);
				
				tree.addClass('treeview-tree');
				tree.find('li').each(function() {
					var stick = jQuery(this);
				});
				tree.find('li').has("ul").each(function () {
					var branch = jQuery(this); //li with children ul
					
					branch.prepend("<i class='tree-indicator glyphicon glyphicon-chevron-right'></i>");
					branch.addClass('tree-branch');
					branch.on('click', function (e) {
						if (this == e.target) {
							var icon = jQuery(this).children('i:first');
							
							icon.toggleClass("glyphicon-chevron-down glyphicon-chevron-right");
							jQuery(this).children().children().toggle();
						}
					})
					branch.children().children().toggle();
					
					/**
					 *	The following snippet of code enables the treeview to
					 *	function when a button, indicator or anchor is clicked.
					 *
					 *	It also prevents the default function of an anchor and
					 *	a button from firing.
					 */
					branch.children('.tree-indicator, button, a').click(function(e) {
						branch.click();
						
						e.preventDefault();
					});
				});
			});
		}
	});
	
	/**
	 *	The following snippet of code automatically converst
	 *	any '.treeview' DOM elements into a treeview component.
	 */
	jQuery(window).on('load', function () {
		jQuery('.treeview').each(function () {
			var tree = jQuery(this);
			tree.treeview();
		})
	})
});