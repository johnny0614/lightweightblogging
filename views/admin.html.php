<script>
    $(document).ready(function() {

        $("div.body").hide();
        
        $("#post_title").empty();
        $("#post_body").empty();

        //post add
        $("form").submit(function(event) {

            //disable default submit event
            event.preventDefault();

            var title = $("#post_title").val().replace(/^\s+|\s+$/g,"");         
            var body = $("#post_body").val().replace(/^\s+|\s+$/g,"");
            var url = $(this).attr('action');
            if (title == "" || title == null || body == "" || body == null) {
                $("#post_submit_result").text("Title or Body is Empty !").show().fadeOut(3000);
                return false;
            } else {

                var posting = $.post(url, {
                    'action': "add",
                    'title': title,
                    'body': body
                })
                        .done(function(data) {

                    var received_data = jQuery.parseJSON(data);
                    var new_post_date = $.datepicker.formatDate("dd MM yy", new Date(received_data.date * 1000));
                    var content = "<h2><a href=" + received_data.url + ">" + received_data.title +
                            "</a></h2>" +
                            "<div class=\"date\">" + new_post_date + "</div>" +
                            "<div class=\"body\"></div>" +
                            "<a href=\"javascript:void(0)\" class=\"post_mani\" name=\"show\">show</a>\n" +
                            "<a href=\"javascript:void(0)\" class=\"post_mani\" name=\"edit\">edit</a>\n" +
                            "<a href=\"javascript:void(0)\" class=\"post_mani\" name=\"delete\">delete</a>\n";
                    
                    $("#new_post").empty().append(content).hide().slideDown();
                    $("#post_title").empty();
                    $("#post_body").empty();
                })
                        .fail(function(data) {

                })
            }
        });
    });
    
    //show post
    $(document).on("click", "a.post_mani[name='show']", function() {
    
        var url = $(this).parent().children("h2").children("a").attr("href") + "/post";
        var div_body = $(this).parent().children("div.body");
        //alert($(this).text());
        if ($(this).text() == "show") {

            var getting = $.get(url)
                    .done(function(data) {
                var received_data = jQuery.parseJSON(data);
                div_body.empty().append(received_data.body).slideDown();
            })
                    .fail(function(data) {

            });
        } else {
            div_body.slideUp();
        }

        $(this).text(function(index, text) {
            return text === "show" ? "hide" : "show";
        }

        );
            
    });
    
    $(document).on("click", "a.post_mani[name='edit']", function() {
        
        var info = $(this).parent().children("h2").children("a").attr("href").split("/");
        var name = info.pop();
        var month = info.pop();
        var year = info.pop();
        var title = $("#post_title").val().replace(/^\s+|\s+$/g,"");
        var redirect_url = document.URL.split("?")[0]+"?action=edit&year="+year+"&month="+month+"&name="+name;
        
        window.location=redirect_url;
    });
    
    $(document).on("click", "a.post_mani[name='delete']", function() {
        
        
        var div_post = $(this).parent();       
        var info = $(this).parent().children("h2").children("a").attr("href").split("/");
        var name = info.pop();
        var month = info.pop();
        var year = info.pop();
        var title = $(this).parent().children("h2").children("a").val();
        var url = $("#post_add").attr('action');
        
        $.post(url, {
            'action' : 'delete',
            'year'   : year,
            'month'  : month,
            'name'   : name,
            'title'  : title
        })
            .done(function(data) {
                div_post.slideUp();
            })
            .fail(function(data) {
                
            })
        
    });

</script>
<div>
    <form action='admin.php' id="post_add" class="post_mani">
        
        <textarea class="title_edit" id="post_title"></textarea><br>
        <textarea class="body_edit" name="body" id="post_body"/></textarea><br>
        <input type="submit" value="New Post">
    </form>
    <span id="post_submit_result"></span>
</div>

<!--adding a new post entry-->
<div class='post' id='new_post'>
</div>

<?php foreach ($posts as $p): ?>
    <div class="post">
        <h2><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h2>
        <div class="date"><?php echo date('d F Y', $p->date) ?></div>
        <div class="body"></div>
        <a href="javascript:void(0)" class="post_mani" name="show">show</a>
        <a href="javascript:void(0)" class="post_mani" name="edit">edit</a>
        <a href="javascript:void(0)" class="post_mani" name="delete">delete</a>
    </div>
<?php endforeach; ?>

<?php if ($has_pagination['prev']): ?>
    <a href="?page=<?php echo $page - 1 ?>" class="pagination-arrow newer">Newer</a>
<?php endif; ?>

<?php if ($has_pagination['next']): ?>
    <a href="?page=<?php echo $page + 1 ?>" class="pagination-arrow older">Older</a>
<?php endif; ?>
