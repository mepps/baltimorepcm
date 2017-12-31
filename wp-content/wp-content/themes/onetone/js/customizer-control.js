( function( $ ) {

wp.customize.bind( 'ready', function() {
    var customize = this;
	
	$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'html',
			data: {
				action: 'onetone_options_export',
			},success: function(e){
				 $('#onetone_options_export').val(e);
			}
		});
   

} );

$(document).on('click','.options-import',function(){
	
	if(confirm( onetone_customize_params.confirm_import )){
	  var new_options = $('#onetone_options_import').val();
  
	  $.ajax({
			  url: ajaxurl,
			  type: 'post',
			  dataType: 'html',
			  data: {
				  action: 'onetone_options_import',
				  options:new_options
			  },success: function(e){
				  $('#onetone_options_import').val('')
				  $('.options-import-result').html(e);
				  window.location.reload();
			  },error:function(){
				  $('.options-import-result').html('Import failed.');
				  }
		  });
	  }
	
});


$('#customize-theme-controls > ul').prepend('<li id="accordion-section-importer" class="accordion-section control-section control-section-importer" style="display: list-item;padding: 15px 10px 15px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;background: #fff;"><a href="#" id="import-theme-options" class="button">'+onetone_customize_params.import_options+'</a><div class="import-status"></div></li>');
	$(document).on('click','#import-theme-options',function(){
			
		if(confirm( onetone_customize_params.confirm )){
			
		$('#accordion-section-importer .import-status').text(onetone_customize_params.loading);							
		$.ajax({type:"POST",dataType:"html",url:ajaxurl,data:{action:'onetone_otpions_restore'},
			success:function(data){
				$('#accordion-section-importer .import-status').text(onetone_customize_params.complete);
				location.reload() ;
			},error:function(e){
				$('#accordion-section-importer .import-status').text(onetone_customize_params.error);
		}});
		}
	});

} )( jQuery );