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
function selectButtonTabActiveOnRefresh(){
    $(function() {
        $('button[data-toggle="tab"]').on('click', function(e) {
            window.localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = window.localStorage.getItem('activeTab');
        if (activeTab) {
            $('button[data-toggle="tab"]').removeClass("btn-primary")
            $('#profileTab button[href="' + activeTab + '"]').tab('show').addClass("btn-primary");
        }
    });
}

function swapTabColor(){
    $(document).ready(function() {
        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
            // $(".tab").addClass("active"); // instead of this do the below
            $(this).removeClass("btn-default").addClass("btn-primary");
        });
    });
}

function addFavoriteMovie(movieId, movieTitle, posterPath){
    var path = Routing.generate('user_add_favorite_movie');
    var movieData = {"Movie_Id": movieId, "Movie_Title": movieTitle, "Poster_Path": posterPath};

    $.ajax({
        type: "POST",
        data: movieData,
        url: path,
        success: function(data){
            if(data["state"] == "success"){
                $("#addMovieToProfile").removeClass("alert-warning");
                $("#addMovieToProfile").addClass("alert-success");
                $("#addMovieToProfile").find("span").text("Le film a été ajouté à vos favoris !");
                $("#addMovieToProfile").fadeIn("slow");
            }
            else{
                $("#addMovieToProfile").removeClass("alert-success");
                $("#addMovieToProfile").addClass("alert-warning");
                $("#addMovieToProfile").find("span").text("Le film est déjà dans votre liste de favoris.");
                $("#addMovieToProfile").fadeIn("slow");
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function removeFavoriteMovie(movieId){
    var path = Routing.generate('user_remove_favorite_movie');
    var movieData = {"Movie_Id": movieId};

    $.ajax({
        type: "POST",
        data: movieData,
        url: path,
        success: function(data){
            if(data){
                $("#favoriteMovie" + movieId).remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function addMovieToWatch(movieId, movieTitle, posterPath){
    var path = Routing.generate('user_add_movie_to_watch');
    var movieData = {"Movie_Id": movieId, "Movie_Title": movieTitle, "Poster_Path": posterPath};

    $.ajax({
        type: "POST",
        data: movieData,
        url: path,
        success: function(data){
            if(data["state"] == "success"){
                $("#addMovieToProfile").removeClass("alert-warning");
                $("#addMovieToProfile").addClass("alert-success");
                $("#addMovieToProfile").find("span").text("Le film a été ajouté dans votre playlist des films à voir !");
                $("#addMovieToProfile").fadeIn("slow");
            }
            else{
                $("#addMovieToProfile").removeClass("alert-success");
                $("#addMovieToProfile").addClass("alert-warning");
                $("#addMovieToProfile").find("span").text("Le film est déjà dans votre playlist des films à voir.");
                $("#addMovieToProfile").fadeIn("slow");
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function removeMovieToWatch(movieId){
    var path = Routing.generate('user_remove_movie_to_watch');
    var movieData = {"Movie_Id": movieId};

    $.ajax({
        type: "POST",
        data: movieData,
        url: path,
        success: function(data){
            if(data){
                $("#movieToWatch" + movieId).remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function closeAlert(id){
    $(function(){
        $("#" + id).css("display", "none");
    })
}

function addLikeOnComment(commentId){
    var path = Routing.generate('add_like_comment');
    var comment_id = {"Comment_Id": commentId};

    $.ajax({
        type: "POST",
        data: comment_id,
        url: path,
        success: function(data){
            if(data["class"] == "success"){
                $("#likeMovieState").removeClass("alert-warning");
                $("#likeMovieState").addClass("alert-" + data["class"]);
                $("#likeMovieState").find("span").text(data["message"]);
                $("#likeMovieState").fadeIn("slow");
                $("#likeComment" + commentId).text(data["likeNumber"])
            }
            else{
                $("#likeMovieState").removeClass("alert-success");
                $("#likeMovieState").addClass("alert-" + data["class"]);
                $("#likeMovieState").find("span").text(data["message"]);
                $("#likeMovieState").fadeIn("slow");
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

function addDislikeOnComment(commentId){
    var path = Routing.generate('add_dislike_comment');
    var comment_id = {"Comment_Id": commentId};

    $.ajax({
        type: "POST",
        data: comment_id,
        url: path,
        success: function(data){
            if(data["class"] == "success"){
                $("#likeMovieState").removeClass("alert-warning");
                $("#likeMovieState").addClass("alert-" + data["class"]);
                $("#likeMovieState").find("span").text(data["message"]);
                $("#likeMovieState").fadeIn("slow");
                $("#dislikeComment" + commentId).text(data["dislikeNumber"])
            }
            else{
                $("#likeMovieState").removeClass("alert-success");
                $("#likeMovieState").addClass("alert-" + data["class"]);
                $("#likeMovieState").find("span").text(data["message"]);
                $("#likeMovieState").fadeIn("slow");
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

$(".remove-event").click(function(event){
    event.preventDefault();
});
