<link rel="stylesheet" href="/css/dashboard.css">
<link rel="stylesheet" href="/css/login.css">
<?php
    $profile = $this->content['profile'];
    ?>


    <div class="grid grid-pad">
        <div class="col-1-4">
            <div class="content">
                <img class="img-responsive" src="img/users/<?php echo $profile->image ?>" width="200px" height="200px">

            <h1>Greetings,  <?php echo $profile->username ?></h1>
            <blockquote>
                Is an instructor? : <?php echo $profile->isInstructor ? 'yes' : 'no' ?>
            </blockquote>
            <p><i class="fa fa-envelope"></i> <?php echo $profile->email ?>
                <br
                <br/> <i class="fa fa-gift"></i>Member since: <?php echo $profile->member_since ?></p>
                <p><i class="fa fa-dashboard"></i> <?php echo(app::getLoadTime()); ?></p>
            </div>
            <button class="button blue"><a href="/dashboard/shop">Shop editor</a></button>
        </div>

        <div class="col-1-4">

            <script type="text/javascript">
                $(document).on('ready', function(){
                    $modal = $('.modal-frame');
                    $form  = $('.apiData');
                    $overlay = $('.modal-overlay');

                    /* Need this to clear out the keyframe classes so they dont clash with each other between ener/leave. Cheers. */
                    $modal.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
                        if($modal.hasClass('state-leave')) {
                            $modal.removeClass('state-leave');
                        }
                    });

                    $('.close').on('click', function(){
                        $overlay.removeClass('state-show');
                        $modal.removeClass('state-appear').addClass('state-leave');
                    });

                    $('#send').on('click', function() {

                        if($('#edit').val().length > 0)
                            {
                                $id = '#edit';
                                $api = $($id).val();
                            }

                            if($('#new').val().length > 0)
                            {
                                $id = '#new';
                                $api = $($id).val();
                            }

                            if($('#delete').val().length > 0)
                            {
                                $id = '#delete';
                                $api = $($id).val();
                            }

                            $url = "<?php echo app::currentUrl(false, true) ?>"+ $api;

                        if($id == '#edit')
                        {
                            $.getJSON($url, function( data ) {

                                data.splice($.inArray('id', data), 1);

                                //console.log(data);

                                createModal(data);

                                $form.attr('action', $url.split('/', 1)+'/update');
                            });

                        }
                        if($id == '#delete')
                        {
                            $statement = [];
                            $statement[0] = $api.split('/');
                            $statement[1] = ['delete'];
                            $array = $url.split('/');
                            $array.pop();
                            $.ajax({
                                type: "POST",
                                url: $array.join('/')+'/delete',
                                data: $statement,
                                success: function (xhr, status) {
                                    alert(status);
                                },
                                error: function(xhr, status, error) {
                                    alert(status);
                                    console.log(xhr.responseText);
                                }
                            });
                            return;

                        } else {


                            $.getJSON($url+'/describe', function (data) {

                                var items = [];
                                data.splice($.inArray('id', data), 1);

                                $.each(data, function(key, val){
                                    items.push(
                                        "<span><label class='label'>"+ val +"</label>",
                                        "<input name='" + val + "'class='slide' placeholder='"+ val +"'></input></span><br>"
                                    );

                                });

                                items.push('<button class="button green" type="submit">Submit</button>');

                                $( "<div/>", {
                                    "class": "data",
                                    html: items.join( "" )
                                }).appendTo( $form );

                                if($id == '#new')
                                {
                                    $array = $url.split('/');
                                    $array.pop();
                                    $form.attr('action', $array.join('/')+'/create');
                                }

                            });

                        }

                        $overlay.addClass('state-show');
                        $modal.removeClass('state-leave').addClass('state-appear');
                    });

                 function createModal(data)
                 {
                     var items = [];

                     $.each( data, function( key, val ) {
                         $.each( val, function(k, v){
                             items.push(
                                 "<span><label class='label'>"+ k +"</label>",
                                 "<input name='" + k + "'class='slide' placeholder='"+ v +"'></input></span><br>"
                             );
                         });
                     });

                     items.push('<button class="button green" type="submit">Change</button>');

                     $( "<div/>", {
                         "class": "data",
                         html: items.join( "" )
                     }).appendTo( $form );
                 }
                });
            </script>
            <h2>Pages</h2>
                    <h3>Edit</h3>
                    <input id="edit" class="slide purple-slide" placeholder="eg. pages/home">

                    <h3>Insert</h3>
                    <input id="new" class="slide purple-slide" placeholder="eg. pages/new">

                    <h3>Delete</h3>
                    <input id="delete" class="slide purple-slide" placeholder="eg pages/obsolete">

                    <button id="send" class="button green verify" type="submit">Send</button>
        </div>
    </div>
<div class="modal-frame">
    <div class="modal">
        <div class="modal-inset">
            <div class="button red close"><i class="fa fa-close"></i></div>

            <div class="modal-body">
                <form action="" class="apiData" method="post">

                </form>

            </div>
        </div>
    </div>
</div>
<div class="modal-overlay"></div>

