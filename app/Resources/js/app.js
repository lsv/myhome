var tmpl = $.templates('#template');

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function loadTabs() {
    jQuery('.tab-pane.active .feed').each(function() {
        var $this = jQuery(this),
            url = $this.data('feed'),
            maxitems = $this.data('maxitems'),
            id = $this.attr('id')
            ;

        jQuery.post('/feed', {feeds: [{
            id: id,
            url: url,
            maxitems: maxitems
        }]}, function(response) {
            var maxage = moment().subtract(15, 'days');
            jQuery.each(response, function(objIndex, obj) {
                jQuery('#' + obj.id + ' > div').html($('<ul class="results">'));
                var results = jQuery('#' + obj.id + ' > div > ul');
                if (obj.hasOwnProperty('error')) {
                    // write error
                } else if (obj.hasOwnProperty('items')) {
                    // loop items
                    jQuery.each(obj.items, function(itemIndex, item) {
                        // check max items
                        if (obj.maxitems > itemIndex) {
                            // check date
                            if (moment(item.date).isAfter(maxage)) {
                                results.append(tmpl.render({
                                    date: moment(item.date).format('LLLL'),
                                    url: item.url,
                                    title: item.title,
                                    content: nl2br(item.content)
                                }));
                            }
                        }
                    });
                }
            });
        }, 'json');
    });
}

jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function () {
    loadTabs();
});

loadTabs();

$('.nav-tabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show')
});
