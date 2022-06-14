<?php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $http = "https://";
} else {
    $http = "http://";
}
?>
$(document).ready(function() {
	/*$("#frame_types").on("change", function() {
		var val = $(this).val();
		if (val != "from_catalogue") {
			$("#frame_dimensions").val("");
			$(".frame_dimensions_group").css("display", "none");
			$(".frame_covers_group, .frame_extras_group").css("display", "block");
		} else {
			$("#frame_covers, #frame_extras").val("");
			$(".frame_dimensions_group").css("display", "block");
			$(".frame_covers_group, .frame_extras_group").css("display", "none");
		}
	});*/
	$("#frame_covers").on("change", function() {
		var val = $(this).val();
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var coversJSON = data.categories.frames.sizes[0].covers[val];
			$("#frame_covers_price").val(coversJSON.price);
			$(".cover_label").html(val.replace("_", " ").toUpperCase());
		});
	})
	$("#frame_extras").on("change", function() {
		var val = $(this).val();
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var extrasJSON = data.categories.frames.sizes[0].extras[val];
			$("#frame_extras_price").val(extrasJSON[0].price);
			$("#frame_extras_max_dimensions_width").val(extrasJSON[0].max_dimensions[0]);
			$("#frame_extras_max_dimensions_height").val(extrasJSON[0].max_dimensions[1]);
			$(".extra_label").html(val.replace("_", " ").toUpperCase());
		});
	})
	$("#frame_dimensions").on("change", function() {
		var val = $(this).val();
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var dpricesJSON = data.categories.frames.sizes[1].price_list[val];
			$("#frame_dimensions_price").val(dpricesJSON);
			$(".dimension_label").html(data.categories.frames.sizes[1].dimensions[val][0] + "x" + data.categories.frames.sizes[1].dimensions[val][1]);
		});
	});
	$("#frame_discount").on("change", function() {
		var val = parseInt($(this).val());
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var discountJSON = data.categories.frames.sizes[1].discount_list[val]['discount'];
			$("#frame_dimensions_discount").val(discountJSON);
			$(".discount_label").html($("#frame_discount option:eq(" + (val + 1) + ")").text());
		});
	});

	/*Other Products*/
	$("#other_product_types").on("change", function() {
		var type = $(this).val();
		if (type == "capa") {
			$(".other_product_thickness_group, .other_product_dimensions_group, .other_product_type_price_group, .other_product_type_quantity_group").css("display", "block");
			$(".other_product_max_dimensions, .other_product_thickness_range", "none");
			$("#other_product_dimensions_range1, #other_product_dimensions_range2, #other_product_thickness_range1, #other_product_thickness_range2").val("");
			$(".other_product_thickness_group #other_product_thickness, #other_product_dimensions, #other_product_type_price, #other_product_type_quantity").val("");
		} else if (type == "passeparto_sheet") {
			$(".other_product_thickness_group, .other_product_with_glue, .other_product_type_quantity_group, .other_product_max_dimensions, .other_product_thickness_range").css("display", "none");
			$(".other_product_dimensions_group, .other_product_type_price_group").css("display", "block");
			$(".other_product_thickness_group #other_product_thickness, #other_product_dimensions, #other_product_type_price, #other_product_type_quantity, #other_product_dimensions_range1, #other_product_dimensions_range2, #other_product_thickness_range1, #other_product_thickness_range2").val("");
			$("#other_product_glue").attr("checked", false);
            alert('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json');
			$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
				var json = data.categories.other_products;
				$.each(json[type].sizes, function() {
					var options = "";
					$.each(this.dimensions, function() {
						options += "<option value='" + this[0] + "x" + this[1] + "'>" + this[0] + "x" + this[1] + "</option>";
					});
					$("#other_product_dimensions").html(options);
					triggerOtherProductsChange();
				})
			});
		} else if (type == "plexiglass") {
			$(".other_product_thickness_group, .other_product_with_glue, .other_product_dimensions_group, .other_product_type_quantity_group").css("display", "none");
			$("#other_product_glue").attr("checked", false);
			$(".other_product_type_price_group, .other_product_max_dimensions, .other_product_thickness_range").css("display", "block");
			$(".other_product_thickness_group #other_product_thickness, #other_product_dimensions, #other_product_type_price, #other_product_type_quantity").val("");
			triggerOtherProductsChange();
		}
	});

	$("#other_product_thickness").on("change", function() {
		var thickness = $(this).val();
		var type = $("#other_product_types").val();
		if (type == "capa") {
			$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
				var json = data.categories.other_products;
				$.each(json[type].sizes, function() {
					if (this.thickness_by_mm == thickness) {
						var options = "";
						$.each(this.dimensions, function() {
							options += "<option value='" + this[0] + "x" + this[1] + "'>" + this[0] + "x" + this[1] + "</option>";
						});
						$("#other_product_dimensions").html(options);
						triggerOtherProductsChange();
						return false;
					}
				})
			});
		}
	});

	$("#other_product_glue").on("click", function() {
		triggerOtherProductsChange();
	});

	/*Glass*/
	$("#glass_types").on("change", function() {
		var type = $(this).val();
		var find = "_";
		var re = new RegExp(find, 'g');
		var title = type.replace(re, " ");
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.glass;
			var thickness = "";
			$.each(json[type].sizes, function() {
				thickness += "<option value='" + this.thickness_by_mm + "'>" + this.thickness_by_mm + "mm</option>";
			});
			$(".glass_type_price_label").html(title);
			$("#glass_thickness").html(thickness);
			triggerGlassChange();
		});

	});

	/* Mirrors */
	$("#mirrors_types").on("change", function() {
		var type = $(this).val();
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.mirrors;
			var thickness = "";
			var hanging_types = "";
			$(".mirrors_finishing_price_group, .mirrors_finishing_frame_required_group, .mirrors_hanging_type_price_group").css("display", "none");
			$("#mirrors_finishing_price").val("");
			$("#mirrors_finishing option").remove();
			$("#mirrors_frame_required").attr("checked", false);
			$.each(json[type].sizes, function() {
				thickness += "<option value='" + this.thickness_by_mm + "'>" + this.thickness_by_mm + "mm</option>";
			});
			if (typeof json[type].hanging_type != "undefined") {
				$(".mirrors_hanging_types_group").css("display", "block");
				hanging_types += "<option value=''>Select hanging type</option>";
				$.each(json[type].hanging_type, function() {
					var find = " ";
					var re = new RegExp(find, 'g');
					var hanging_title = this.name.replace(re, "_");
					hanging_types += "<option value='" + hanging_title + "'>" + this.name + "</option>";
				});
			} else {
				$(".mirrors_hanging_types_group").css("display", "none");
				$("#mirrors_hanging_type_price").val("");
				$("#mirrors_hanging_types option").remove();
			}
			$("#mirrors_thickness").html(thickness);
			$("#mirrors_hanging_types").html(hanging_types);
			triggerMirrorChange();
		});

	});

	/* Prints */
	$("#print_types").on("change", function() {
		var type = $(this).val();
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.prints;
			var ranges = "";
			var finishes = "";
			var laminates = "";
			var extras = "";
			$.each(json[type].sizes.ranges, function(index) {
				ranges += "<option value='" + index + "'>" + ((this[1] == 0) ? ' > ' + this[0] : this[0] + ' - ' + this[1]) + "</option>";
			});
			if (typeof json[type].min_price != "undefined") {
				$("#print_min_price").css("display", "block");
				$("#print_min_price").val(json[type].min_price);
			} else {
				$("#print_min_price").css("display", "none");
			}
			//finishing
			if (typeof json[type].finishing != "undefined") {
				$(".print_finish_group, .print_finish_price_group").css("display", "block");
				finishes += "<option value=''>Select finishing</option>";
				$.each(json[type].finishing, function(index) {
					var find = " ";
					var re = new RegExp(find, 'g');
					var title = this.type.replace(re, "_");
					finishes += "<option value='" + title + "'>" + this.type + "</option>";
				});
				$("#print_finishes").html(finishes);
			} else {
				$(".print_finish_group, .print_finish_price_group").css("display", "none");
				$("#print_finishes option").remove();
			}
			//laminate
			if (typeof json[type].laminate != "undefined") {
				$(".print_laminate_group, .print_laminate_price_group").css("display", "block");
				laminates += "<option value=''>Select laminate</option>";
				$.each(json[type].laminate, function(index) {
					var find = " ";
					var re = new RegExp(find, 'g');
					var title = this.type.replace(re, "_");
					laminates += "<option value='" + title + "'>" + this.type + "</option>";
				});
				$("#print_laminate").html(laminates);
			} else {
				$(".print_laminate_group, .print_laminate_price_group").css("display", "none");
				$("#print_laminate option").remove();
			}

			//extras
			if (typeof json[type].extras != "undefined") {
				$(".print_extras_group, .print_extras_price_group").css("display", "block");
				extras += "<option value=''>Select extras</option>";
				$.each(json[type].extras, function(index) {
					var find = " ";
					var re = new RegExp(find, 'g');
					var title = this.type.replace(re, "_");
					extras += "<option value='" + title + "'>" + this.type + "</option>";
				});
				$("#print_extras").html(extras);
			} else {
				$(".print_extras_group, .print_extras_price_group").css("display", "none");
				$("#print_extras option").remove();
				$("#print_extras_price").val("");
			}
			$("#print_range").html(ranges);
			$(".print_label").html(type);
			triggerPrintChange();
		});
	});

	//upload image
	$("#upload_frame").on("change", function() {
		if ($(this).val() != "") {
            $(".image_upload_error").html("");
			var formData = new FormData();
            if($('#upload_frame')[0].files.length < 3)
            {
                $.each($('#upload_frame')[0].files, function(x, file){
                    formData.append('upload_frame[]', file);
                });
    			$.ajax({
    				url: 'upload.php',
    				data: formData,
    				type: 'POST',
    				enctype: 'multipart/form-data',
    				processData: false, // tell jQuery not to process the data
    				contentType: false, // tell jQuery not to set contentType
    				beforeSend: function() {
    					$(".img_loader img").css("display", "block");
    					$(".image_display").css("display", "none");
    				},
    				success: function(res) {
    					$("span.img_loader img").css("display", "none");
    					res = JSON.parse(res);
                        if (typeof res.error == "undefined") {
    						$(".image_display").css("display", "block");
    						$("#img_text").css("display", "block");
                            var html = "<ul>";
                            $.each(res, function(index){
                                html += "<li><p>"+this.width + "x" + this.height+"</p><img src='" + this.url + "' width='200' height='100' alt='" + this.filename + "'></li>";
                                $("#tmp_path").val(this.tmp_path);
                            });
                            html += "</ul>";

                            $("#image_content").html(html);
    					}
                   else{
                            $(".image_upload_error").html(res.error);
                        }
    				},
    				error: function(err) {
    					console.log(err);
    				}
    			})
            }
            else{
                $(".image_upload_error").html("More than 2 files can not be uploaded.");
            }
		}
	});

	//edit frame
	$(".edit-frame").on("click", function() {
		$("#myModal").modal("show");
		var splitted_id = $(this).attr("id").split("-");
		var id = splitted_id[splitted_id.length - 1];
		$("#myModal").on('shown.bs.modal', function() {
			$.ajax({
				url: "getFrameData.php",
				data: {
					id: id
				},
				type: 'POST',
				beforeSend: function() {
					$("div.img_loader").css("display", "block");
				},
				success: function(result) {
					var result = JSON.parse(result);
					$("#frame_id").val(result.id);
					$("#frame_name").val(result.name);
					$("#frame_color").val(result.color);
					$("#frame_type").val(result.type);
					$("#frame_price").val(result.price);
					$("#frame_description").html(result.description);
					$("#frame_status").val(result.active);
					$("#frame_status option").each(function(index) {
						if ($(this).attr("value") == result.active) {
							$(this).attr("selected", true);
							return false;
						}
					})
					$(".image_display").css("display", "block");
					$("#img_text").css("display", "block");
                    var html = "<ul>";
                    $.each(result.images, function(index){
                        html += "<li><p>"+this.width + "x" + this.height+"</p><img src='" + this.img_path + "' width='200' height='100' alt='" + this.filename + "'></li>";
                    });
                    html += "</ul>";
                    $("#image_content").html(html);
					$(".edit-form").css("display", "block");
					$("div.img_loader").css("display", "none");
				}
			});
		});
	});

    //edit frame
	$(".edit-catalog-frame").on("click", function() {
		$("#myModal").modal("show");
		var splitted_id = $(this).attr("id").split("-");
		var id = splitted_id[splitted_id.length - 1];
		$("#myModal").on('shown.bs.modal', function() {
			$.ajax({
				url: "getCatalogFrameData.php",
				data: {
					id: id
				},
				type: 'POST',
				beforeSend: function() {
					$("div.img_loader").css("display", "block");
				},
				success: function(result) {
					var result = JSON.parse(result);
					$("#frame_id").val(result.id);
					$("#frame_name").val(result.name);
                    $("#frame_price").val(result.price);
                    $("#frame_catalog_dimensions1").val(result.width);
                    $("#frame_catalog_dimensions2").val(result.height);
					$("#frame_status").val(result.active);
					$("#frame_status option").each(function(index) {
						if ($(this).attr("value") == result.active) {
							$(this).attr("selected", true);
							return false;
						}
					});
					$(".edit-catalog-form").css("display", "block");
					$("div.img_loader").css("display", "none");
				}
			});
		});
	});


	$("#add-translation").on("click", function() {
		$("#addTranslation").modal("show");
	})

	$(".edit-translation").on("click", function() {
		$("#editTranslation").modal("show");
		var splitted_id = $(this).attr("id").split("-");
		var id = splitted_id[splitted_id.length - 1];
		$("#editTranslation").on('shown.bs.modal', function() {
			$.ajax({
				url: "getTranslationData.php",
				data: {
					id: id
				},
				type: 'POST',
				beforeSend: function() {
					$("div.img_loader").css("display", "block");
				},
				success: function(result) {
					var result = JSON.parse(result);
					$("#edit_translation_id").val(result.id);
					$("#edit_translation_lang_en").val(result.lang_en);
					$("#edit_translation_lang_hb").val(result.lang_hb);
					$("#edit_translation_status").val(result.active);
					$("#edit_translation_status option").each(function(index) {
						if ($(this).attr("value") == result.active) {
							$(this).attr("selected", true);
							return false;
						}
					})
					$(".edit-form").css("display", "block");
					$("div.img_loader").css("display", "none");
				}
			});
		});
	});

	$("#sortable").sortable({
		update: function(event, ui) {
			var idsOrder = ($("#sortable").sortable("toArray", {
				attribute: 'data-id'
			}));
			$.ajax({
				url: 'sortFrames.php',
				data: {
					idsOrder: idsOrder
				},
				type: 'POST',
				success: function(result) {
					result = JSON.parse(result);
					if (result.success === true) {
						window.sessionStorage.setItem("orderMessage", result.message);
						window.location.href = window.location.href;
					}
				}
			})
		}
	});
	$("#sortable").disableSelection();

    $(".example").fancyTable({
      /*sortColumn:0, // column number for initial sorting
      sortOrder: 'descending', // 'desc', 'descending', 'asc', 'ascending', -1 (descending) and 1 (ascending)
      sortable: false,*/
      pagination: true, // default: false
      searchable: true,
      globalSearch: true,
      globalSearchExcludeColumns: [] // exclude column 2 & 5
    });

});

function deleteTranslation(id) {
    $.ajax({
        url: 'deleteTranslation.php',
        data: {
            id: id
        },
        type: 'POST',
        beforeSend: function() {

        },
        success: function(res) {
            res = JSON.parse(res);
            if (res.success === true) {
                window.sessionStorage.setItem("deleteMessage", res.message);
                window.location.href = window.location.href;
            }

        },
        error: function(err) {
            console.log(err);
        }
    })
}

function deleteFrame(id) {
	$.ajax({
		url: 'deleteFrame.php',
		data: {
			id: id
		},
		type: 'POST',
		beforeSend: function() {

		},
		success: function(res) {
			res = JSON.parse(res);
			if (res.success === true) {
				window.sessionStorage.setItem("deleteMessage", res.message);
				window.location.href = window.location.href;
			}

		},
		error: function(err) {
			console.log(err);
		}
	})
}
//Prints
function triggerPrintChange() {
	var type = $("#print_types").val();
	var range = $("#print_range").val();
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.prints;
		$.each(json[type].sizes.ranges, function(index) {
			if (index == range) {
				$("#print_price").val(json[type].prices[index]);
				return false;
			}
		});
	});
}

function triggerPrintFinishingChange() {
	var type = $("#print_types").val();
	var finish = $("#print_finishes").val().replace("_", " ");
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.prints;
		$.each(json[type].finishing, function(index) {
			if (this.type == finish) {
				$("#print_finish_price").val(this.price);
				$(".print_finish_label").html(finish);
				return false;
			}
		});
	});
}

function triggerPrintLaminateChange() {
	var type = $("#print_types").val();
	var laminate = $("#print_laminate").val().replace("_", " ");
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.prints;
		$.each(json[type].laminate, function(index) {
			if (this.type == laminate) {
				$("#print_laminate_price").val(this.price);
				$(".print_laminate_label").html(laminate);
				return false;
			}
		});
	});
}

function triggerPrintExtrasChange() {
	var type = $("#print_types").val();
	var extras = $("#print_extras").val().replace("_", " ");
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.prints;
		$.each(json[type].extras, function(index) {
			if (this.type == extras) {
				if (typeof this.max_dimensions != "undefined") {
					$(".print_extras_max_dimensions_group").css("display", "block");
					$("#print_extras_max_dimensions1").val(this.max_dimensions[0]);
					$("#print_extras_max_dimensions2").val(this.max_dimensions[1]);
				} else {
					$("#print_extras_max_dimensions1, #print_extras_max_dimensions2").val("");
					$(".print_extras_max_dimensions_group").css("display", "none");
				}
				$("#print_extras_price").val(this.price);
				$(".print_extras_label").html(extras);
				return false;
			}
		});
	});
}
//Mirror
function triggerMirrorChange() {
	var thickness = $("#mirrors_thickness").val();
	var type = $("#mirrors_types").val();
	var find = "_";
	var re = new RegExp(find, 'g');
	var title = type.replace(re, " ");
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.mirrors;
		var finishing = "";
		$.each(json[type].sizes, function() {
			if (this.thickness_by_mm == thickness) {
				finishing += "<option value=''>Select finishing</option>";
				$.each(this.finishing, function() {
					var find = " ";
					var re = new RegExp(find, 'g');
					var finishing_title = this.type.replace(re, "_");
					finishing += "<option value='" + finishing_title + "'>" + this.type + "</option>";
				});
				thickness += "<option value='" + this.thickness_by_mm + "'>" + this.thickness_by_mm + "mm</option>";
				$("#mirrors_type_price").val(this.price);

				$("#mirrors_type_min_price").val(this.min_price);
				$(".mirrors_type_price_label").html(title);
				$("#mirrors_finishing").html(finishing);
				$("#mirrors_dimensions1").val(this.dimensions[0]);
				$("#mirrors_dimensions2").val(this.dimensions[1]);
				return false;
			}
		});
	});
}

function triggerMirrorFinishingChange() {
	var thickness = $("#mirrors_thickness").val();
	var type = $("#mirrors_types").val();
	var finishing = $("#mirrors_finishing").val();
	if (finishing == '') {
		$(".mirrors_finishing_price_group, .mirrors_finishing_frame_required_group").css("display", "none");
		$("#mirrors_finishing_price").val("");
		$("#mirrors_frame_required").attr("checked", false);
	}
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.mirrors;
		$.each(json[type].sizes, function() {
			if (this.thickness_by_mm == thickness) {
				$.each(this.finishing, function() {
					var find = "_";
					var re = new RegExp(find, 'g');
					var finishing_title = finishing.replace(re, " ");
					if (this.type == finishing_title) {
						$(".mirrors_finishing_price_label").html(this.type);
						$(".mirrors_finishing_price_group, .mirrors_finishing_frame_required_group").css("display", "block");
						$("#mirrors_finishing_price").val(this.price_by_meter);
						$("#mirrors_frame_required").attr("checked", this.chose_frame);
						return false;
					}
				});
				return false;
			}
		});

	});
}

function triggerMirrorHangingTypesChange() {
	var hanging_type = $("#mirrors_hanging_types").val();
	var type = $("#mirrors_types").val();
	if (hanging_type == '') {
		$(".mirrors_hanging_type_price_group").css("display", "none");
		$("#mirrors_hanging_type_price").val("");
	}
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.mirrors;
		$.each(json[type].hanging_type, function() {
			var find = "_";
			var re = new RegExp(find, 'g');
			var hanging_title = hanging_type.replace(re, " ");
			if (this.name == hanging_title) {
				$(".mirrors_hanging_type_price_group").css("display", "block");
				$(".mirrors_hanging_type_price_label").html(this.name);
				$("#mirrors_hanging_type_price").val(this.price);
				return false;
			}
		});

	});
}

function triggerGlassChange() {
	var thickness_by_mm = $("#glass_thickness").val();
	var type = $("#glass_types").val();
	$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
		var json = data.categories.glass;
		$.each(json[type].sizes, function() {
			if (this.thickness_by_mm == thickness_by_mm) {
				$("#glass_dimensions_min_range1").val(this.min_dimensions[0]);
				$("#glass_dimensions_min_range2").val(this.min_dimensions[1]);
				$("#glass_dimensions_max_range1").val(this.max_dimensions[0]);
				$("#glass_dimensions_max_range2").val(this.max_dimensions[1]);
				$("#glass_type_price").val(this.price);
				$("#glass_type_min_price").val(this.min_price);
				return false;
			}
		});
	});
}

function triggerOtherProductsChange() {
	var dimension = $("#other_product_dimensions").val();
	var thickness = $("#other_product_thickness").val();
	var type = $("#other_product_types").val();
	var find = "_";
	var re = new RegExp(find, 'g');
	var title = type.replace(re, " ");
	if (type == "capa") {
		dimension = dimension.split("x");
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.other_products;
			$.each(json[type].sizes, function() {
				var other_p_type = this;
				if (other_p_type.thickness_by_mm == thickness) {
					$.each(other_p_type.dimensions, function(d_index) {
						if (this[0] == dimension[0] && this[1] == dimension[1]) {
							$(".other_product_type_price_label, .other_product_type_quantity_label").html(title + " with " + dimension[0] + "x" + dimension[1]);
							$(".other_product_type_price_group, .other_product_type_quantity_group, .other_product_with_glue, .other_product_type_min_price_group").css("display", "block");
							if ($("#other_product_glue").is(":checked")) {
								$.each(other_p_type.prices, function() {
									if (this.with_glue == 1) {
										$("#other_product_type_price").val(this.price_list[d_index]);
										$("#other_product_type_quantity").val(this.quantity_threshold[d_index]);
									}
								});
							} else {
								$.each(other_p_type.prices, function() {
									if (this.with_glue == 0) {
										$("#other_product_type_price").val(this.price_list[d_index]);
										$("#other_product_type_quantity").val(this.quantity_threshold[d_index]);
									}
								});
							}
							return false;
						}
					});
					$(".other_product_type_min_price_label").html(title);
					$("#other_product_type_min_price").val(other_p_type.min_price);
					return false;
				}
			})
		});
	} else if (type == "passeparto_sheet") {
		dimension = dimension.split("x");
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.other_products;
			$.each(json[type].sizes, function() {
				var other_p_type = this;
				$.each(other_p_type.dimensions, function(d_index) {
					if (this[0] == dimension[0] && this[1] == dimension[1]) {
						$(".other_product_type_price_label").html(title + " with " + dimension[0] + "x" + dimension[1]);
						$(".other_product_type_price_group, .other_product_type_min_price_group").css("display", "block");
						$("#other_product_type_price").val(other_p_type.prices[d_index]);
						$(".other_product_type_min_price_label").html(title);
						$("#other_product_type_min_price").val(other_p_type.min_price);
						return false;
					}
				});
			})
		});
	} else if (type == "plexiglass") {
		$.getJSON('<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_PATH; ?>/types.json', function(data) {
			var json = data.categories.other_products;
			$(".other_product_type_price_label").html(title);
			$(".other_product_type_price_group").css("display", "block");
			$("#other_product_type_price").val(json[type].sizes[0]["prices"][0]);
			$("#other_product_thickness_range1").val(json[type].sizes[0]["thickness_range_by_mm"][0]);
			$("#other_product_thickness_range2").val(json[type].sizes[0]["thickness_range_by_mm"][1]);
			$("#other_product_dimensions_range1").val(json[type]["manual_dimensions"]["max_dimensions"][0]);
			$("#other_product_dimensions_range2").val(json[type]["manual_dimensions"]["max_dimensions"][1]);
			$(".other_product_type_min_price_group").css("display", "none");
			$("#other_product_type_min_price").val("");
		});
	}
}

function getText(elem){
    var list = JSON.parse($("#code_list").val());
    var id = parseInt($(elem).val());
    console.log(list[id]['text']);
    console.log($("#translation_lang_en"));
    $("#translation_lang_en").val(list[id]['text']);
}

function checkTranslation(edit)
{
    var find = " ";
    var re = new RegExp(find, 'g');
    if(edit)
    {
        var id = $("#edit_translation_id").val();
        var code = $("#edit_translation_lang_en").val().replace(re, "_").trim();
        var data = {id: id, code: code};
    }
    else {
        var code = $("#translation_lang_en").val().replace(re, "_").trim();
        var data = {code: code};
    }
    var response = $.ajax({
        async:false,
        url:"checkTranslationCode.php",
        data: data,
        type: 'POST'
    });
    var response = JSON.parse(response.responseText);
    if(response.status)
    {
        (edit) ? $(".edit_lang_en_error").html(response.message) : $(".lang_en_error").html(response.message);
        return false;
    }
    else {
        (edit) ? $(".edit_lang_en_error").html("") : $(".lang_en_error").html("");
        return true;
    }
}
