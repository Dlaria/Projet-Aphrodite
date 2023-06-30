jQuery(function($) {
    $(document).ready(function(){
            // Dissparition du bouton média par défaut
            document.getElementById('insert-media-button').style.display = 'none';
            $('#pp-insert-media').click(open_media_window);
        });

    function open_media_window() {
        // Si la fenêtre de média n'est pas défini
        if (this.window === undefined) {
            // Alors on créé la fenêtre
            this.window = wp.media({
                    title: 'Insert a media',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'Insert'}
                });
    
            var self = this;
            // La même fenêtre au moment de la séléction
            this.window.on('select', function() {
                    // Récupération des infos de la séléction
                    var first = self.window.state().get('selection').first().toJSON();
                    // console.log(first);
                    // console.log(wp.media.string.image(first));

                    // Ajout de la séléction dans une balise img et un input hidden
                    var img = document.getElementById('imageProduct');
                    var input = document.getElementById('src');
                    input.value = first.url
                    img.src = input.value
                });
        }
    
        this.window.open();
        return false;
    }
});