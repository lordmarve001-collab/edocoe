<form method="POST">
    <input type="hidden" name="del" value="<?php echo $result['coursecode']; ?>">

    <div class="modal fade" id="update_modal<?php echo $result['coursecode']?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Course</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete 
                    <strong><?php echo $result['coursecode']; ?></strong>?
                </div>

                <div class="modal-footer">
                    <button type="submit" name="updaterol" class="btn btn-danger">
                        Yes, Delete
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
