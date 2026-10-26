
  $(document).ready(function() {
 // executes when HTML-Document is loaded and DOM is ready
console.log("document is ready");
  
$( '.lightbox' ).each(function( index ) {
  $(this).click(function(){
      var imgSrc = $(this).children("img").attr("data-img");
      console.log(imgSrc);
      $('body').append( '<div id="lightbox-modal" class="modal fade" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"  id="printThis"><img src="' + imgSrc + '" class="img-responsive"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button id="btnPrint" onclick="window.print()" type="button" class="btn btn-default">Print</button></div></div></div></div>' );
      $('#lightbox-modal').modal({show:true});

      $('#lightbox-modal').on('hidden.bs.modal', function (e) {
        $(this).remove();
      })
      return false;
  });
});
   
   
// document ready  
});

