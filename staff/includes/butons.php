<script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquerys.js"></script>
        <script src="js/newjs.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/dselect.js"></script>
        <script src="js/main.js"></script>
        <script src="js/tables.js"></script>
        <script src="js/btable.js"></script>
        
        <script>
            $(function($) {
                $('#example').DataTable({
                    "aLengthMenu": [[10, 15, 25, -1], [10, 15, 25, "All"]],
                    "pageLength": 10
                });

                $('#example2').DataTable( {
                    
                    "paging":         false
                });

                $('#example3').DataTable();
            });

$(document).ready(function() {

  $('.selectpicker').selectpicker();

});
        </script>
        <script type="text/javascript">
            if (window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>