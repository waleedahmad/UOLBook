<div id="new_status" class="create-post" data-post-type="text">

    <ul class="navbar-nav col-xs-12" id="post_header" role="navigation">
        <li><a href="#" id="status-post"><span class="glyphicon glyphicon-pencil"></span>Update Status</a></li>
        <li><a href="#" id="file-post"><span class="glyphicon glyphicon-picture" ></span>Add Photos/Video</a></li>
    </ul>

    <form action="" id="file-upload-form">
        <div class="col-xs-12" id="post_content">
            <div class="image-holder col-xs-1">
                <img alt="profile picture" class="col-xs-1" src="/storage/{{Auth::user()->image_uri}}">
            </div>

            <div class="textarea_wrap col-xs-11">
                <textarea  id="post-text" name="post_text" placeholder="What's on your mind?"></textarea>
            </div>
        </div>

        <div class="col-xs-12" id="post_footer">
            <ul class="navbar-nav col-xs-7">
                <input type="file" id="upload-file" name="file_upload">
                <li><a href="#" id="init-file-upload"> <span class="glyphicon glyphicon-camera"> </span> <span id="file-name"></span></a></li>
            </ul>
            <div class="col-xs-5">
                <button class="btn btn-primary post-now" data-id-type="text" data-source="{{$source}}" data-source-id="{{$source_id}}">Post</button>
            </div>
        </div>
    </form>
</div>