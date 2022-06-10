
function do_remove(el)
{
    el.parentElement.parentElement.parentElement.remove();
}
jQuery(document).ready(function() {
    var gmb_frame;
    jQuery('#btnGmbAddItem').click(function(){
        if ( gmb_frame ) {
            gmb_frame.open();
            return;
        }
        gmb_frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        gmb_frame.on('select', function() {
            let attachment = gmb_frame.state().get('selection').first().toJSON();
            let last_index = jQuery('#gmbContainer li:last-child').index();
            last_index += 2;
            let el = '<li class="gmb_item">';
            el += '<table style="background-color:rgb(241, 241, 241); border-radius:5px;">';
            el += '<tr>';
            el += '<td style="width:250px; max-width:250px; padding:0 1em 1em 1em; text-align:center;"><img src="'+attachment.url+'" style="width:100%;" /><br/><span style="cursor:pointer; color:red;" onclick="do_remove(this)" >remove</span></td>';
            el += '<td style="vertical-align:top; padding-left:1em; padding-right:1em;">';
            el += '<p><input type="text" style="width:400px;" placeholder="Title" name="pg_item_title_' + last_index + '"></p>';
            el += '<p><textarea rows="5" style="width:400px;" placeholder="Description" name="pg_item_sub_' + last_index + '"></textarea></p>';
            el += '<p><input type="text" style="width:400px;" placeholder="Button Text" name="pg_item_btn_' + last_index + '"></p>';
            el += '<p><input type="text" style="width:400px;" placeholder="Button Url" name="pg_item_url_' + last_index + '"></p>';
            el += '<p><input type="number" min="1" step="1" style="width:100px;" placeholder="Order" name="pg_item_seq_' + last_index + '"></p>';
            el += '<input type="hidden" name="pg_item_id_' + last_index + '" value="' + attachment.id + '"></input>';
            el += '</td>';
            el += '</tr>';
            el += '</table>';
            el += '</li>';
            jQuery('#gmbContainer').append(el);
        });
        gmb_frame.open();
    });
    jQuery('#btnGmbAddItemSingle').click(function(){
        if ( gmb_frame ) {
            gmb_frame.open();
            return;
        }
        gmb_frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        gmb_frame.on('select', function() {
            let attachment = gmb_frame.state().get('selection').first().toJSON();
            let last_index = jQuery('#gmbContainerSingle li:last-child').index();
            last_index += 2;
            let el = '<li class="gmb_item">';
            el += '<table style="background-color:rgb(241, 241, 241); border-radius:5px;">';
            el += '<tr>';
            el += '<td style="width:250px; max-width:250px; padding:0 1em 1em 1em; text-align:center;"><img src="'+attachment.url+'" style="width:100%;" /><br/><span style="cursor:pointer; color:red;" onclick="do_remove(this)" >remove</span></td>';
            el += '<td style="vertical-align:top; padding-left:1em; padding-right:1em;">';
            el += '<p><input type="number" min="1" step="1" style="width:100px;" placeholder="Order" name="pg_item_seq_' + last_index + '"></p>';
            el += '<input type="hidden" name="pg_item_id_' + last_index + '" value="' + attachment.id + '"></input>';
            el += '</td>';
            el += '</tr>';
            el += '</table>';
            el += '</li>';
            jQuery('#gmbContainerSingle').append(el);
        });
        gmb_frame.open();
    });
});
