<!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/datatables.min.js"></script>
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
        </script>
        <script type="text/javascript">
            if (window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>
        <!-- <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript">
            $(".chosen-container").chosen();
        </script> -->
