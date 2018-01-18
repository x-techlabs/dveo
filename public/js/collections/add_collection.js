ace = ace || {};

extend(ace, {
    /**
     * Send the collection title and description to server, if
     * it successfully saved, send the request to insert
     * playlist_id, and collection_id to the table playlists_in_collections
     *
     * return null
     */
    addToCollection: function () {

        var title = $('#title').val();
        var description = $('#description').val();

        $.ajax({
            url: ace.path('add_to_collection'),
            type: "POST",
            data: {
                'title': title
            },
            dataType: "json",
            success: function (data) {
                if (data.status) {
                    $('.edited').html('<span class="fui-check"></span> Collection successfully added!');
                    setInterval(function() {
                        window.location.replace("collections");
                    }, 1500);
                }
            }
        });
    }
});


$(document).ready(function () {
    $('#add-to-collection').submit(function (event) {
        event.preventDefault();
        ace.addToCollection();

    });
});
