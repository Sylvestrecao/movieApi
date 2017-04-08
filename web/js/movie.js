function selectTabActiveOnRefresh(){
    $(function() {
        $('a[data-toggle="tab"]').on('click', function(e) {
            window.localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = window.localStorage.getItem('activeTab');
        if (activeTab) {
            $('#moviesTab a[href="' + activeTab + '"]').tab('show');
           // window.localStorage.removeItem("activeTab");
        }
    });
}

function loadPageOnScroll(depth){
    $(window).scroll(function(){
        if($(window).scrollTop() == $(document).height() - $(window).height()){
            var path = 'https://api.themoviedb.org/3/movie/now_playing?api_key=1ec8fb13de4288846a552aa419f958c2&language=fr-FR&page='+depth+'&region=FR'
            $.ajax({
                type: "GET",
                url: path,
                success: function(data){
                    console.log(data)
                    loadPageOnScroll(depth++)
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    })

}