<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    var siteUrl = '{SITE_URL}';

    function like(id, type) {
        var likes = $('span#likes_' + id + "_" + type).attr("no");
        $.ajax({
            url: siteUrl + "/gag/like",
            type: "POST",
            dataType: "Json",
            data: {
                id: id,
                type: type
            },
            success: function(response) {
                var id = response['id'];
                var postId = response['postId'];
                var likes = response['likes'];
                if (response == false) {
                    window.location = '{SITE_URL}/user/login/';
                } else {
                    if (id == 1) {
                        $("#like_" + postId + "_" + type).addClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    } else if (id == 0) {
                        $("#like_" + postId + "_" + type).removeClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    } else {
                        $("#dislike_" + postId + "_" + type).removeClass('active');
                        $("#like_" + postId + "_" + type).removeClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    }
                }
            }
        });
    }

    function dislike(id, type) {
        var likes = $('span#likes_' + id + "_" + type).attr("no");
        $.ajax({
            url: siteUrl + "/gag/dislike",
            type: "POST",
            dataType: "Json",
            data: {
                id: id,
                type: type
            },
            success: function(response) {
                var id = response['id'];
                var postId = response['postId'];
                var likes = response['likes'];
                if (response == false) {
                    window.location = '{SITE_URL}/user/login/';
                } else {
                    if (id == -1) {
                        $("#dislike_" + postId + "_" + type).addClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    } else if (id == 0) {
                        $("#dislike_" + postId + "_" + type).removeClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    } else {
                        $("#dislike_" + postId + "_" + type).removeClass('active');
                        $("#like_" + postId + "_" + type).removeClass('active');
                        $("span#likes_" + postId + "_" + type).text(likes + ' Likes');
                    }
                }
            }
        });
    }
</script>
<!-- BEGIN gag_list -->
<div class="row">
    <div class="col-md-1">

    </div>
    <div class="col-md-10 text-center">
        <a href="{SITE_URL}/gag/show/id/{GAG_ID}">
            <h1>{GAG_TITLE}</h1>
        </a>
    </div>
    <div class="col-md-1"></div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="btn-group-vertical text-center">
            <button class=" {USER_LIKED} btn btn-primary btn-sm" onclick='like({GAG_ID} , "post");' id="like_{GAG_ID}_post">⇧</button>
            <br>
            <a class="btn btn-default disabled"><span class="badge " id ="likes_{GAG_ID}_post" no ='{GAG_LIKES}'>{GAG_LIKES} Likes</span></a>
            <br>

            <button class=" {USER_DISLIKE} btn btn-primary btn-sm" onclick='dislike({GAG_ID} ,"post");' id="dislike_{GAG_ID}_post">⇩</button>
            <br>
        </div>
    </div>
    <div class="col-md-8">
        <a href="{SITE_URL}/gag/show/id/{GAG_ID}"><img src="{GAG_URLIMAGE}" class="img-responsive">
        </a>
    </div>
    <div class="col-md-2"></div>
</div>
<!-- END gag_list -->