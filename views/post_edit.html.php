<div class="post">
    
        <script>
            $(document).ready(function() {
                
               $("form").submit(function(event) {
                   
                   event.preventDefault();
                   
                   var title = $("#edit_title").val().replace(/^\s+|\s+$/g,"");
                   var body = $("#edit_body").val().replace(/^\s+|\s+$/g,"");
                   var path = $("#edit_path").val().replace(/^\s+|\s+$/g,"");
                   var date =$("#edit_date").text().replace(/^\s+|\s+$/g,"");
                   date = (new Date(date)).getTime()/1000;
                   var url = $(this).attr("action");  
                                     
                   if(title==null || title=="" || body==null || body=="") {
                       return false;
                   } else {
                       $posting = $.post(url, {
                           'action' : 'edit',
                           'title'  : title,
                           'body'   : body,
                           'path'   : path,
                           'date'   : date
                       })
                       .done(function(data) {
                        
                            var redirect_url = document.URL.split("?")[0];
                            window.location = redirect_url;
                       })
                   }
                   
               });
            });
            
        </script>
    
        <form action="admin.php" class="post_mani">
            <input type="hidden" id="edit_path" value="<?php echo $p->path?>"/>
            <textarea class="title_edit" id="edit_title"><?php echo $p->title ?></textarea>
            <div class="date" id="edit_date"><?php echo date('d F Y', $p->date)?></div>
            <textarea class="body_edit" id="edit_body"><?php echo $p->body?></textarea>
            <input type="submit" value="Submit">
        </form>
</div>