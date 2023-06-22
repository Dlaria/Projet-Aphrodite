jQuery(function($) {
    $(document).ready(function(){
            document.getElementById('insert-media-button').style.display = 'none';
            $('#insert-my-media').click(open_media_window);
        });

    function open_media_window() {
        if (this.window === undefined) {
            this.window = wp.media({
                    title: 'Insert a media',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'Insert'}
                });
    
            var self = this;
            this.window.on('select', function() {
                    var first = self.window.state().get('selection').first().toJSON();
                    // console.log(first);
                    // console.log(wp.media.string.image(first));
                    var img = document.getElementById('imageProduct');
                    var input = document.getElementById('src');
                    img.src = first.url
                    input.value = first.url
                });
        }
    
        this.window.open();
        return false;
    }
});