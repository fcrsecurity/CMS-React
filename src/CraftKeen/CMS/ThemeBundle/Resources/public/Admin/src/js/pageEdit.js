$(function () {
	// There's the widgets and the widgets_content_area
	var $widgets = $("#widgets"),
		$widgets_content_area = $("#widgets_content_area");

	// Let the widgets items be draggable
	$("li", $widgets).draggable({
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		revert: "invalid", // when not dropped, the item will revert back to its initial position
		containment: "document",
		helper: "clone",
		cursor: "move"
	});

	// Let the widgets_content_area be droppable, accepting the widgets items
	$widgets_content_area.droppable({
		accept: "#widgets > li",
		classes: {
			"ui-droppable-active": "ui-state-highlight"
		},
		drop: function (event, ui) {
			deleteImage(ui.draggable);
		}
	});

	// Let the widgets be droppable as well, accepting items from the widgets_content_area
	$widgets.droppable({
		accept: "#widgets_content_area li",
		classes: {
			"ui-droppable-active": "custom-state-active"
		},
		drop: function (event, ui) {
			recycleImage(ui.draggable);
		}
	});

	// Image deletion function
	var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a>";
	function deleteImage($item) {
		$item.fadeOut(function () {
			var $list = $("ul", $widgets_content_area).length ?
					$("ul", $widgets_content_area) :
					$("<ul class='widgets ui-helper-reset'/>").appendTo($widgets_content_area);

			$item.find("a.ui-icon-widgets_content_area").remove();
			$item.append(recycle_icon).appendTo($list).fadeIn(function () {
				$item
					.find("img");
			});
		});
	}

	// Image recycle function
	var widgets_content_area_icon = "<a href='link/to/widgets_content_area/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-widgets_content_area'>Delete image</a>";
	function recycleImage($item) {
		$item.fadeOut(function () {
			$item
					.find("a.ui-icon-refresh")
					.remove()
					.end()
					.append(widgets_content_area_icon)
					.find("img")
					.end()
					.appendTo($widgets)
					.fadeIn();
		});
	}

	// Image preview function, demonstrating the ui.dialog used as a modal window
	function viewLargerImage($link) {
		var src = $link.attr("href"),
				title = $link.siblings("img").attr("alt"),
				$modal = $("img[src$='" + src + "']");

		if ($modal.length) {
			$modal.dialog("open");
		} else {
			var img = $("<img alt='" + title + "' />")
					.attr("src", src).appendTo("body");
			setTimeout(function () {
				img.dialog({
					title: title,
					modal: true
				});
			}, 1);
		}
	}

	// Resolve the icons behavior with event delegation
	$("ul.widgets > li").on("click", function (event) {
		var $item = $(this),
				$target = $(event.target);

		if ($target.is("a.ui-icon-widgets_content_area")) {
			deleteImage($item);
		} else if ($target.is("a.ui-icon-zoomin")) {
			viewLargerImage($target);
		} else if ($target.is("a.ui-icon-refresh")) {
			recycleImage($item);
		}

		return false;
	});
});