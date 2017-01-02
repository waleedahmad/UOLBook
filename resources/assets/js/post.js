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
 * Make an ajax call to create a new text post and on success generate post DOM and append to .posts
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
                registerCommentEventHandlers();
                registerPostLikeEventHandlers();
            }
        }
    });
}


/**
 * Make an ajax call to create a new photo/video post and on success generate post DOM and append to .posts
 * @param formData
 * @param text
 */
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

                registerCommentEventHandlers();
                registerPostLikeEventHandlers();
            }
        }
    });
}

/**
 * Validate file format
 * @param format
 * @returns {boolean}
 */
function fileFormatSupported(format){
    let supported = ['image/jpg', 'image/jpeg', 'image/png', 'video/mp4'];
    return (supported.indexOf(format) != -1);
}

/**
 * Return file mime type
 */
function getFileMimeType(){
    return $($file)[0].files[0].type;

}

/**
 * Return file extension
 * @returns {string}
 */
function getFileExtension(){
    return '.'+$($file)[0].files[0].name.replace(/^.*\./, '');
}

/**
 * Show bootstrap message modal
 * @param title
 * @param message
 */
function showMessageModel(title, message){
    $($message_modal).modal('show');
    $($message_modal_title).text(title);
    $($message_modal_body).text(message);
}

/**
 * Validate if file exists
 * @returns {Number|number}
 */
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
    return `<div class="post-wrap" data-post-id="${id}">
                <div class="post">
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
                </div>
                
                <div class="actions">
                    <span class="post-like" data-post-id="${id}" >
                        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Like
                        <span class="post-like-count">(0)</span>
                    </span>
                </div>
                
                <div class="comments">
                    <div class="comment-box">
                        <div class="comment-box col-xs-12">
                            <img alt="profile picture" class="col-xs-1" src="${image_uri}">
                            <input class="col-xs-11 comment-holder" data-post-id="${id}" name="post_text" placeholder="Comment">
                        </div>
                    </div>
                    
                    <div class="comments-wrapper">
                    
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

    return `<div class="post-wrap" data-post-id="${id}">
                <div class="post">
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
                </div>
                
                <div class="actions">
                    <span class="post-like" data-post-id="${id}" >
                        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Like
                        <span class="post-like-count">(0)</span>
                    </span>
                </div>
                
                <div class="comments">
                    <div class="comment-box">
                        <div class="comment-box col-xs-12">
                            <img alt="profile picture" class="col-xs-1" src="${image_uri}">
                            <input class="col-xs-11 comment-holder" data-post-id="${id}" name="post_text" placeholder="Comment">
                        </div>
                    </div>
                    
                    <div class="comments-wrapper">
                    
                    </div>
                </div>
            </div>`;
}

$('.comment-holder').on('keypress', initComments);

function initComments(e){
    if(e.which === 13){
        let _this = $(this),
            comment = $(this).val(),
            post_id = $(this).attr('data-post-id');

        if(comment.length){
            $.ajax({
                type : 'POST',
                url : '/comment/create',
                data : {
                    _token : token,
                    comment : comment,
                    post_id : post_id
                },
                success: function(res){

                    if(res.created === 'true'){
                        $(_this).val('');
                        let $commentDOM = generateCommentDOM(res.image_uri, comment, res.name, res.user_id);
                        $(_this).parents('.comments').children('.comments-wrapper').prepend($commentDOM);
                    }
                }
            });
        }
    }
}

function registerCommentEventHandlers(){
    $('.comment-holder').off('keypress');
    $('.comment-holder').on('keypress', initComments);
}

function generateCommentDOM(image_uri, comment, name, user_id){
    return `<div class="comment">
                <div class="col-xs-12">
                    <img alt="profile picture" class="dp col-xs-1" src="${image_uri}">
                    
                    
                    <div class="text col-xs-11">
                        <a href="/profile/${user_id}">${name}</a>
                        ${comment}
                    </div>
                </div>
            </div>`;
}

$('.post-like').on('click', initPostLikes);

function initPostLikes(e){
    e.preventDefault();
    var post_id = $(this).attr('data-post-id'),
        _this = this;

    $(_this).unbind('click', initPostLikes);

    if($(this).hasClass('liked')){
        $.ajax({
            type : 'POST',
            url : '/likes/unlike',
            data : {
                post_id : post_id,
                _token : token
            },
            success : function(res){
                if(res.unlike === 'true'){
                    $(_this).on('click', initPostLikes);
                    $(_this).removeClass('liked');
                    $(_this).find('.post-like-count').text('('+res.total_likes+')');
                }
            }
        });
    }else{
        $.ajax({
            type : 'POST',
            url : '/likes/like',
            data : {
                post_id : post_id,
                _token : token
            },
            success : function(res){
                if(res.like === 'true'){
                    $(_this).on('click', initPostLikes);
                    $(_this).addClass('liked');
                    $(_this).find('.post-like-count').text('('+res.total_likes+')');
                }
            }
        });
    }
}

function registerPostLikeEventHandlers(){
    $('.post-like').off('click');
    $('.post-like').on('click', initPostLikes);
}

function initInifiteScroll(){
    $('.posts').jscroll({
        debug: false,
        autoTrigger: true,
        nextSelector: '.pagination li:last a',
        contentSelector: '.post-wrap, .pagination',
        callback: function() {
            $('ul.pagination:visible:first').remove();
            $('.jscroll-added > *').unwrap();
            registerCommentEventHandlers();
            registerPostLikeEventHandlers();
        },

        loadingHtml: '<img src="http://i.imgur.com/qkKy8.gif" alt="Loading" />',
    });
}

initInifiteScroll();

