<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
</script>
<script>
    $(document).ready(function() {
        $(".edit").click(function() {
            var editid = $(this).attr("id");
            $(this).prevAll('.content').contents().unwrap().wrap('<form method="POST" action=""><textarea name="comment"/><button name="id" value="' + editid + '" type="submit">Save</button></form>');
            $(this).hide();
        });
        $(document).on('click', '.reply', function() {
            var replying = $(this).attr("id");
            $(this).css('display', 'none');
            $("#form_" + replying).css('display', 'block');

        });


    });
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

    function comment(gagId, parent_id = '0') {
        var conntent = $('#conntent_' + parent_id).val();
        var parentName = $('#name_' + parent_id).text();
        var com = "'com'";
        var deleteUrl = "/gag/delete-comment/id/";
        $.ajax({
            url: siteUrl + "/gag/comment",
            type: "POST",
            dataType: "Json",
            data: {
                parent_id: parent_id,
                conntent: conntent,
                gagId: gagId
            },
            success: function(response) {
                var idComment = response['commentId'];
                var idParent = response['parent_id'];
                var postId = response['postId'];
                var likes = response['likes'];
                var commentDate = response['date'];
                var commentUsername = response['username'];
                var urlimage = response['urlimage'];
                var conntent = response['conntent'];
                if (response == false) {
                    window.location = '{SITE_URL}/user/login/';
                } else if (parent_id == '0') {
                    $("#append_" + gagId).prepend('<div class="row">    <div class="col-sm-2"><div class="thumbnail"><img class="img-responsive user-photo" src="' + urlimage + '"></div></div><div class="col-sm-10"><div class="panel panel-default"><div class="panel-heading"><div class="text-center"><div class="pull-left"><span class="badge" id="likes_' + idComment + '_com" no="' + likes + '"> ' + likes + ' Likes</span><button class=" btn btn-primary btn-sm" onclick="like(' + idComment + ' , ' + com + ');" id="like_' + idComment + '_com">⇧</button><button class=" btn btn-primary btn-sm" onclick="dislike(' + idComment + ' , ' + com + ');" id="dislike_' + idComment + '_com">⇩</button></div><strong> ' + commentUsername + '</strong> <span class="text-muted">commented on ' + commentDate + '</span><div class="btn-group pull-right"><button id="' + idComment + '" class="edit rightalign btn btn-primary btn-sm">Edit</button><button  onclick=window.location="' + siteUrl + deleteUrl + idComment + '" title="Delete" class="delete_state rightalign btn btn-primary btn-sm">Delete</button><button id="' + idComment + '" class="reply btn btn-primary btn-sm">Reply</button></div></div></div><div class="panel-body"><p class="content" no="' + idComment + '">' + conntent + '</p></div></div><div id="form_' + idComment + '" style="display: none;"><hr><textarea class="form-control" id="conntent_' + idComment + '">@' + commentUsername + ' </textarea><button class="btn btn-primary btn-sm" onclick="comment(' + gagId + ' , ' + idComment + ');">Reply</button></div><div id="append_' + idComment + '"></div></div></div>');
                    $('textarea#conntent_' + idParent).val('');
                } else {
                    $("#append_" + parent_id).prepend('<div class="row"><div class="col-sm-2"><div class="thumbnail"><img class="img-responsive user-photo" src="' + urlimage + '"></div></div><div class="col-sm-10"><div class="panel panel-default"><div class="panel-heading"><div class="text-center"><div class="pull-left"><span class="badge" id="likes_' + idComment + '_com" no="' + likes + '"> ' + likes + ' Likes</span><button class=" btn btn-primary btn-sm" onclick="like(' + idComment + ' , ' + com + ');" id="like_' + idComment + '_com">⇧</button><button class=" btn btn-primary btn-sm" onclick="dislike(' + idComment + ' , ' + com + ');" id="dislike_' + idComment + '_com">⇩</button></div><strong> ' + commentUsername + '</strong> <span class="text-muted">reply on ' + commentDate + '</span><div class="btn-group pull-right"><button id="' + idComment + '" class="edit rightalign btn btn-primary btn-sm">Edit</button><button onclick=window.location="' + siteUrl + deleteUrl + idComment + '" title="Delete" class="delete_state rightalign btn btn-primary btn-sm">Delete</button></div></div></div><div class="panel-body"><p class="content" no="' + idComment + '">' + conntent + '</p></div></div></div></div>');
                    $("#form_" + parent_id).css('display', 'none');
                    $("#" + parent_id + ".reply").css('display', 'inline');
                    $('textarea#conntent_' + idParent).val('@' + parentName + " ");
                }
            }
        });

    }
</script>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 text-center">
        <h1>{GAG_TITLE}</h1>
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
        <img src="{GAG_URLIMAGE}" class="img-responsive">
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <input type="hidden" name="parent_id" value="0">
                    <label for="comment">Comment:</label>
                    <textarea class="form-control" id="conntent_0"></textarea>
                    <button onclick='comment({GAG_ID});' class="btn btn-primary btn-sm">Submit</button>
                </div>
                <hr>
                <div id="append_{GAG_ID}">

                </div>
            </div>
        </div>


        <!-- BEGIN comment_list -->
        <div class="row">
            <div class="col-sm-2">
                <div class="thumbnail">
                    <img class="img-responsive user-photo" src="{COMMENT_URLIMAGE}">
                </div>
            </div>

            <div class="col-sm-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <div class="pull-left">
                                <span class="badge" id="likes_{COMMENT_ID}_com" no='{COMMENT_LIKES}'> {COMMENT_LIKES} Likes</span>

                                <button class=" {COMMENT_LIKED} btn btn-primary btn-sm" onclick='like({COMMENT_ID} , "com");' id="like_{COMMENT_ID}_com">⇧</button>
                                <button class=" {COMMENT_DISLIKE} btn btn-primary btn-sm" onclick='dislike({COMMENT_ID} ,"com");' id="dislike_{COMMENT_ID}_com">⇩</button>
                            </div>
                            <strong id="name_{COMMENT_ID}">{COMMENT_USERNAME}</strong> <span class="text-muted">commented on {COMMENT_DATE}</span>
                            <div class="btn-group pull-right">
                                <!-- BEGIN comment_list_buttones -->
                                <button id="{COMMENT_ID}" class="edit rightalign btn btn-primary btn-sm">Edit</button>
                                <button onclick="window.location='{SITE_URL}/gag/delete-comment/id/{COMMENT_ID}';" title="Delete" class="delete_state rightalign btn btn-primary btn-sm">Delete</button>
                                <!-- END comment_list_buttones -->
                                <button id="{COMMENT_ID}" class="reply btn btn-primary btn-sm">Reply</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p class="content" no="{COMMENT_ID}">{COMMENT_CONTENT}</p>
                    </div>
                </div>

                <div id="form_{COMMENT_ID}" style="display: none;">
                    <hr>
                    <textarea class="form-control" id="conntent_{COMMENT_ID}">@{COMMENT_USERNAME} </textarea>
                    <button class="btn btn-primary btn-sm" onclick='comment({GAG_ID} , {COMMENT_ID});'>Reply</button>
                </div>
                <div id="append_{COMMENT_ID}">

                </div>
                <!-- BEGIN comment_reply -->
                <div class="row">
                    <div class="col-sm-2">
                        <div class="thumbnail">
                            <img class="img-responsive user-photo" src="{REPLY_URLIMAGE}">
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="text-center">
                                    <div class="pull-left">
                                        <span class="badge" id="likes_{REPLY_ID}_com" no='{REPLY_LIKES}'> {REPLY_LIKES} Likes</span>

                                        <button class=" {REPLY_LIKED} btn btn-primary btn-sm" onclick='like({REPLY_ID} , "com");' id="like_{REPLY_ID}_com">⇧</button>
                                        <button class=" {REPLY_DISLIKE} btn btn-primary btn-sm" onclick='dislike({REPLY_ID} ,"com");' id="dislike_{REPLY_ID}_com">⇩</button>
                                    </div>

                                    <strong>{REPLY_USERNAME}</strong> <span class="text-muted">reply on {REPLY_DATE}</span>
                                    <div class="btn-group pull-right">
                                        <!-- BEGIN comment_reply_buttones -->
                                        <button id="{REPLY_ID}" class="edit rightalign btn btn-primary btn-sm">Edit</button>
                                        <button onclick="window.location='{SITE_URL}/gag/delete-comment/id/{REPLY_ID}';" title="Delete" class="delete_state rightalign btn btn-primary btn-sm">Delete</button>
                                        <!-- END comment_reply_buttones -->
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <p class="content" no="{REPLY_ID}">{REPLY_CONTENT}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END comment_reply -->

            </div>

        </div>
        <!-- END comment_list -->
    </div>
</div>