jQuery.fn.horizontalTableAccordion = function() {
	// Get original font size
	var originalFontSize = jQuery('html').css('font-size');

	// Get active column
	var activeColumn = jQuery(".activeColumn");

	// Set max width using percentage %
	var maxWidth = 50;

	// Get width % ratio for min width by getting the children (count) in the
	// header
	var table_header_children = jQuery('tr:first-child').children().size();

	// Divide the column header count by 100 to get the average width
	var table_column_width_average = (100 / table_header_children);

	// Get the outer height of the header
	var table_header_height = jQuery('th').outerHeight();

	// Set min width for the columns
	var minWidth = table_column_width_average;

	// Animate table by clicking the table header ( <th> )
	jQuery("tr th").click(function() {
		if (jQuery(this).hasClass('correct_text_spacing')) {
			// Check for double click, do nothing
			var doubleClicked = true;
		} else {
			// Animates the last active column
			jQuery(activeColumn).animate( {
				width : minWidth + "%"
			}, {
				queue : false,
				duration : 600
			});

			// Animates the table header
			jQuery(this).animate( {
				width : maxWidth + "%"
			}, {
				queue : false,
				duration : 600
			});

			activeColumn = this;

			// Reset the table header CSS
			jQuery('tr:first-child').children().css( {
				'width' : minWidth,
				'overflow' : 'hidden',
				'white-space' : 'nowrap',
				'height' : table_header_height
			});

			// Remove class if found
			jQuery('table.horizontalTableAccordion tr').children().removeClass(
					'correct_text_spacing');

			// Reset the font size to zero
			var index = jQuery(this).parent().children().index(this);
			jQuery('table.horizontalTableAccordion tr').each(
					function() {
						jQuery(':nth-child(' + (index + 1) + ')', this).css(
								'font-size', '0px');
					});

			// Fix text spacing on selected column
			var index = jQuery(this).parent().children().index(this);
			jQuery('table.horizontalTableAccordion tr').each(
					function() {
						jQuery(':nth-child(' + (index + 1) + ')', this)
								.addClass('correct_text_spacing').animate( {
									fontSize : originalFontSize
								}, {
									queue : true,
									duration : 950
								});
					});
		}
	});

}
