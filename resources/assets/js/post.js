let $status = $('#new_status'),
    $post_footer = $('#post_footer'),
    $upload_icon = $('#init-file-upload'),
    $file = $('#upload-file'),
    $file_name = $("#file-name"),
    $status_form = $("#file-upload-form"),
    $post_text = $("#post-text"),
    token = $("meta[name=token]").attr('content'),
    $posts = $(".posts");

/**
 * Display file icon when Add Photo/Video clicked in status header
 */
$($status).find('#file-post').on('click', function(e){
    e.preventDefault();
    $($post_footer).find("li").css({"display" : "inline-block"});
    $($status).attr('data-post-type', 'file');

});

/**
 * Hide file icon when Add Photo/Video clicked in status header
 */
$($status).find('#status-post').on('click', function(e){
    e.preventDefault();
    $($post_footer).find("li").css({"display" : "none"});
    $($status).attr('data-post-type', 'text');
});

/**
 * Facade for hidden file input, trigger a file select menu when footer photo icon clicked
 */
$($upload_icon).on('click', function(e){
    $($file).click();
});

/**
 * Detect a change in hidden file input and set name in icon text holder
 */
$($file).change(function() {
    if(this.value){
        let file = $($file)[0].files[0];
        let fileName = file.name;
        $($file_name).text(fileName);
        console.log(file.type);
    }
});

/**
 * Handle text and file posts submissions
 */
$($status_form).on('submit', function(e){
    e.preventDefault();
    let text = $($post_text).val(),
        post_type = $($status).attr('data-post-type');

    if(post_type === 'text'){
        console.log("Text Post");
        if(text.length > 0){
            createTextPost(text);
        }else{
            showMessageModel('Post is empty', 'This post appears to be blank. Please write something or attach a link or photo to post.');
        }
    }else{
        console.log("File Post");
        if(fileInputExist()){
            console.log("Exist");
            if(fileFormatSupported(getFileMimeType())){
                console.log("Supported");
                let formData = new FormData(this);
                createFilePost(formData, text);
            }else{
                showMessageModel('File type not supported', 'Sorry we do not support ('+ getFileExtension() +') file type for uploading');
            }
        }
    }
});

/**
 * Make an ajax call to create a new text post and on success generate post DOM and append to posts
 * @param text
 */
function createTextPost(text){
    $.ajax({
        type : 'POST',
        url : '/posts/text/create',
        data : {
            post_text : text,
            _token : token
        },
        success : function(res){
            if(res.created){
                $($post_text).val('');
                let $postDOM = generateTextPOSTDOM(res.id, text, res.name, res.image_uri, res.user_id, res.time_stamp);
                $($posts).prepend($postDOM);
            }
        }
    });
}

function createFilePost(formData, text){
    $.ajax( {
        url: '/posts/file/create',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        async: true,
        success : function(res){
            if(res.created){
                $($post_text).val('');
                let $postDOM = generateFilePOSTDOM(res.id, text, res.name, res.image_uri, res.type, res.upload_path, res.user_id, res.time_stamp);
                $($posts).prepend($postDOM);
                $($file).val("");
                $($file_name).text('');
            }
        }
    });
}

function fileFormatSupported(format){
    let supported = ['image/jpg', 'image/jpeg', 'image/png', 'video/mp4'];
    return (supported.indexOf(format) != -1);
}

function getFileMimeType(){
    return $($file)[0].files[0].type;

}

function getFileExtension(){
    return '.'+$($file)[0].files[0].name.replace(/^.*\./, '');
}

function showMessageModel(title, message){
    $($message_modal).modal('show');
    $($message_modal_title).text(title);
    $($message_modal_body).text(message);
}

function fileInputExist(){
    return $($file)[0].files.length;
}

/**
 * Returns TEXT post DOM
 * @param id
 * @param text
 * @param name
 * @param image_uri
 * @returns {string}
 */
function generateTextPOSTDOM(id, text, name, image_uri, user_id, time_stamp){
    return `
        <div class="post" data-post-id="${id}">
            <div class="col-xs-1 img-holder">
                <img alt="profile picture" class="user-img" src="${image_uri}">
            </div>
    
            <div class="col-xs-11 post-content">
                <div class="name">
                    <a href="/profile/${user_id}">
                        ${name}
                    </a>
                </div>
                
                <div class="link">
                    <a href="/post/${id}">${time_stamp}</a>
                </div>
    
                <div class="text">
                    ${text}
                </div>
            </div>
        </div>`;
}

function generateFilePOSTDOM(id, text, name, image_uri, type, file_uri, user_id, time_stamp){
    let media;

    if(type === 'photo') {
        media = `<div class="photo"><img src="/storage/${file_uri}"></div>`;
    }

    if(type === 'video'){
        media = `<div class="video">
                    <video controls>
                        <source src="/storage/${file_uri}" type="video/mp4">    
                    </video>
                </div>`;
    }
    return `
            <div class="post" data-post-id="{{$post->id}}">
                <div class="col-xs-1 img-holder">
                    <img alt="profile picture" class="user-img" src="${image_uri}">
                </div>

                <div class="col-xs-11 post-content">
                    <div class="name">
                        <a href="/profile/${user_id}">
                            ${name}
                        </a>
                    </div>
                    
                    <div class="link">
                        <a href="/post/${id}">${time_stamp}</a>
                    </div>

                    <div class="text">
                        ${text}
                    </div>

                    <div class="photo">
                        `+media+`
                    </div>
                </div>
            </div>`;
}
