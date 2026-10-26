<!-- New Modal -->
            <div id="myModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header flex-column">
                                  
                <h4 class="modal-title w-100">Are you sure?</h4>    
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you really want to Approved Results? This process cannot be undones.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="float: left;">NO</button>
                <form method="POST">
                    <input type="hidden" name="ccode" value="<?php echo htmlentities($ccode)?>">
                    <input type="hidden" name="code" value="<?php echo htmlentities($code)?>">
                    <button name="complete" class="btn btn-danger">YES</button>
                </form>
            </div>
         
        </div>
    </div>
</div>     