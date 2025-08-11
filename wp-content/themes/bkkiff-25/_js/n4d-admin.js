var n4dAdmin;

( function( $ ) {

	var inputs = {},
		total  = 0,
		n4dAdmin = {

		init: function() {
			inputs.nonce = $( '#_ajax_linking_nonce' );
			inputs.btn = $( '.submit-ajax' );
			inputs.btn.on( 'click', function() {
				$("#status-app").html("Importing please be patient");
				n4dAdmin.generate(0, total);
			});

console.log('init')

		},
		generate: function(processed, offset){
			var	query = {
				action : 'n4d_import_ajax',
				added  : processed,
				offset : offset
			};
//console.log("generating", processed, offset, total);
			$.post( window.ajaxurl, query, function( r ) {
console.log("R", r);
				var per = (total/Number(r.total))*100;

				if (r.status === false){
					$("#status-app").html("Generating ("+( Number(r.processed)+Number(r.offset) )+"/"+r.total+")");

					per = (( Number(r.processed)+Number(r.offset) ) / Number(r.total))*100;

					n4dAdmin.generate(r.processed, offset);
				} else if (r.status === true){
//console.log("status true", total, r.offset, r.total, r.limit, r.added );
					total += Number(r.processed);
					per = (total/Number(r.total))*100;

					if (total > r.total || r.processed >= r.total ) {
						$("#status-app").html("<strong>Process Completed</strong> ("+r.total+"/"+r.total+")");
					}
					else if (r.limit == r.processed){
						$("#status-app").html("Generating ("+total+"/"+r.total+")");
						n4dAdmin.generate(0, total);
					}
				}

				$("#progress-app").width(per+"%");


			}, 'json' );
		},
	};
	$( document ).ready( n4dAdmin.init );
})( jQuery );





