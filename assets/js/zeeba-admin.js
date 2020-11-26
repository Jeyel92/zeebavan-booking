// $(document).ready(function() {
    jQuery("#step1 div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("#step1 div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("#step1 div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    jQuery("#step2 div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("#step2 div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("#step2 div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    jQuery("#step3 div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("#step3 div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("#step3 div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    jQuery("#policy-add-btn").click(function() {
        text = '<div class="more-policy-content row"><div class="col-md-10 col-xs-10 form-group">\
        		<input type="text" class="form-control" name="step3_policy_list[]" value="" />\
                </div><a class="button button-danger remove-btn" title="Remove row">\
                <i class="glyphicon glyphicon-remove"></i></a></div>';
        jQuery('#more-policy-container').append(text);
    });

    jQuery("#bundle-add-btn").click(function() {
        text = '<div class="more-bundle-content"><div class="form-group"><label for="name">Name</label>\
        		<input type="text" class="form-control" id="name" name="bundle_name[]" value="" />\
                </div><div class="form-group row"><div class="col-md-6 col-xs-12">\
                <label for="code">Codes</label><small>SD option codes separated with space</small>\
                <input type="text"  class="form-control" id="code" name="bundle_code[]" value="" /></div>\
                <div class="col-md-6 col-xs-12"> <label for="price">Price</label><small>(per day)\
                </small><input type="text"  class="form-control" id="price" name="bundle_price[]" value="" />\
                </div></div><div class="form-group"><label for="tooltip">Tooltips</label>\
                <textarea class="form-control" id="tooltips" name="bundle_tooltip[]" rows="5"></textarea></div>\
                <a class="button button-danger remove-btn">Remove Row</a></div>';
        jQuery('#more-bundle-container').append(text);
    });

    jQuery("#rename-add-btn").click(function() {
        text = '<div class="more-rename-content"><div class="form-group row"><div class="col-xs-6">\
                <input type="text" class="form-control" name="renaming_options_code[]" value="" />\
                </div><div class="col-xs-6"><input type="text" class="form-control" name="renaming_options_text[]" value="" />\
                </div></div><a class="button button-danger remove-btn">Remove Row</a></div>';
        jQuery('#more-rename-container').append(text);
    });

    jQuery("#step4 div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("#step4 div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("#step4 div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    jQuery("#step4-policy-add-btn").click(function() {
        text = '<div class="step4-more-policy-content row"><div class="col-md-10 col-xs-10 form-group">\
                <input type="text" class="form-control" name="step4_policy_list[]" value="" />\
                </div><a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a></div>';
        jQuery('#step4-more-policy-container').append(text);
    });

    jQuery("#step5 div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("#step5 div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("#step5 div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    jQuery("#step5-content-add-btn").click(function() {
        text = '<tr><td><input type="text" class="form-control" name="step5_rd_content_name[]" value="" /></td>\
                <td><input type="text" class="form-control" name="step5_rd_content_value[]" value="" /></td>\
                <td><input type="checkbox" class="form-control highlight_checkbox" name="step5_rd_content_highlight_checkbox[]" value="1" />\
                <input type="hidden" class="form-control" name="step5_rd_content_highlight[]" value=""/></td>\
                <td><a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a></td>\
              </tr>';
        jQuery('#step5-more-content-container tbody').append(text);
    });

    jQuery("#step5-policy-add-btn").click(function() {
        text = '<div class="step5-more-policy-content row"><div class="col-md-10 col-xs-10 form-group">\
                <input type="text" class="form-control" name="step5_policy_list[]" value="" />\
                </div><a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a></div>';
        jQuery('#step5-more-policy-container').append(text);
    });

    jQuery(".highlight_checkbox").on('change', function(){
    	if(jQuery(this).is(':checked') == true){
    		jQuery(this).next().val(1);
    	}else{
    		jQuery(this).next().val(0);
    	}
    });


    jQuery(document).on("click", ".remove-btn", function() {
        jQuery(this).parents('tr').remove();
        jQuery(this).parent().remove();
    });

// });