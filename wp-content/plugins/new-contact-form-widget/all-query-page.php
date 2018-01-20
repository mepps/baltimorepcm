<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// load css and js files
wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/bootstrap.css' );
wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
wp_enqueue_script( 'cfw-boostrap-js', plugin_dir_url( __FILE__ ).'js/bootstrap.js', array('jquery'), '3.3.6', true );

// load pagination setting
$all_setttings = get_option('contact_form_settings');

if(isset($all_setttings['show_query'])) {
	$show_record_per_page = $all_setttings['show_query'];
} else {
	$show_record_per_page = 10;
}
?>
<div>
	<h1 style="float: left; width:45%; ">All Users Queries</h1>
	<a class="btn btn-primary" style="float: right; margin-top: 20px; margin-right: 20px;" onclick="return DownloadList();"><i class="fa fa-download"></i> Download All User List</a>
</div>
<table class="table  table-bordered table-hover" style="background-color: #FFFFFF;">
	<thead>
		<tr class="info">	
			<th># ID</th>
			<th>User Name</th>
			<th>User Email</th>
			<th>Date Time</th>
			<th class="text-center">View Query</th>
			<th class="text-center">Delete</th>
			<th class="text-center"><input type="checkbox" name="checkAll" id="checkAll"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			
		
			//fetch all user queries
			global $wpdb;
			$contact_form_table_name = $wpdb->prefix . 'awp_contact_form';
			$all_contact_queries_result = $wpdb->get_results("SELECT * FROM `$contact_form_table_name`", OBJECT );
			
			// pagination limit start
			$per_page = $show_record_per_page;															// number of results to show per page
			$total_results = $all_contact_queries_result;							// all results
			$total_pages = ceil(count($all_contact_queries_result) / $per_page);	// total pages we going to have
			$show_page = 1;															// which page will be display

			//-------------if page is set check------------------//
			if (isset($_GET['page_no'])) {
				$show_page =sanitize_text_field($_GET['page_no']);             //it will tells the current page
				if ($show_page > 0 && $show_page <= $total_pages) {
					$start = ($show_page - 1) * $per_page;
					$end = $start + $per_page;
				} else {
					// error - show first set of results
					$start = 0;              
					$end = $per_page;
				}
			} else {
				// if page isn't set, show first set of results
				$start = 0;
				$end = $per_page;
			}
			// display pagination
			if(isset($_GET['page_no'])) {
				$page = intval($_GET['page_no']);
			} else {
				$page = 5;
			}
			$tpages = $total_pages;
			if ($page <= 0) $page = 1;
			// pagination limit end
			$new_all_contact_queries_result = $wpdb->get_results("SELECT * FROM `$contact_form_table_name` LIMIT $per_page OFFSET $start", OBJECT );
			//print_r($new_all_contact_queries_result);
			if(count($new_all_contact_queries_result)){
				$no = 1;
				if($show_page > 1) { $no = $start + 1; }
				foreach($new_all_contact_queries_result as $single_row ){
					$id = $single_row->id;
					$name = $single_row->name;
					$email = $single_row->email;
					$date_time = $single_row->date_time;
					?>
					<tr id="cq-<?php echo $id; ?>">
						<td><?php echo $no; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $email; ?></td>
						<td><?php echo $date_time; ?></td>
						<td class="text-center">
							<button class="btn btn-info" data-toggle="modal" data-target="#user-modal-form" onclick="ManageContactQueries('view-contact-query','<?php echo $id; ?>');"><i class="fa fa-eye"></i> View Query</button>
						</td>
						<td class="text-center">
							<button class="btn btn-danger text-center" href="?page=cfw-all-queries&action=delete-contact-query&id=<?php echo $id; ?>" onclick="ManageContactQueries('delete-contact-query','<?php echo $id; ?>');"><i class="fa fa-trash-o"></i></button>
						</td>
						<td class="text-center"><input type="checkbox" name="checkAll" id="checkAll" value="<?php echo $id; ?>"></td>
					</tr>
					<?php
					$no++;
				}
			}
		?>
		<tr class="">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-center"><button class="btn btn-danger" onclick="return ManageContactQueries('delete-all-queries','-1');"><i class="fa fa-trash-o"></i></button></td>					
		</tr>
		<tr class="info">			
			<?php
			/*--------------------------------------------------------------------------------------------
			|    @desc:         pagination 
			---------------------------------------------------------------------------------------------*/
			function paginate($reload, $page, $tpages) {
				$adjacents = 1;
				$prevlabel = "&lsaquo; Prev";
				$nextlabel = "Next &rsaquo;";
				$out = "";
				// previous
				if ($page == 1) {
					$out.= "";
				} elseif ($page == 2) {
					$out.= "<li><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
				} else {
					// previous
					$out.= "<li><a  href=\"" . $reload . "&amp;page_no=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";//beech ka diffrance change krega 
				}
			  
				$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
				$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
				for ($i = $pmin; $i <= $pmax; $i++) {
					if ($i == $page) {
						$out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
					} elseif ($i == 1) {
						$out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
					} else {
						$out.= "<li><a  href=\"" . $reload . "&amp;page_no=" . $i . "\">" . $i . "</a>\n</li>";
					}
				}
				
				if ($page < ($tpages - $adjacents)) {
					$out.= "<li><a href=\"" . $reload . "&amp;page_no=" . $tpages . "\">" . $tpages . "</a></li>\n";
				}
				// next
				if ($page < $tpages) {
					$out.= "<li><a href=\"" . $reload . "&amp;page_no=" . ($page + 1) . "\">" . $nextlabel . "</a></li>";
				} else {
					$out.= "";
				}
				$out.= "";
				return $out;
			}
			?>			
			<td colspan="7" class="text-center">
				<?php
				$reload = $_SERVER['PHP_SELF'] . "?page=cfw-all-queries&tpages=" . $tpages;
				echo '<ul class="pagination">';
				if ($total_pages > 1) {
					echo paginate($reload, $show_page, $total_pages);
				}
				echo "</ul>";
				?>
			</td>
		</tr>
	</tbody>
</table>

<!-- View Query Modal Form -->
<div id="user-modal-form" name="user-modal-form" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">View Contact Query Details</h3>
			</div>
			<div id="query-details" class="modal-body">
				<div id="loading-msg" class="text-center">
					<h3>Loading Contact Query Details...</h3>
					<i class="fa fa-cog fa-spin fa-3x "></i>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
</div>

<script>
function DownloadList(){
	//Ajax download user list code
	var DownloadUsersList = new XMLHttpRequest();
	//check object request & response
	DownloadUsersList.onreadystatechange = function() {
		if (DownloadUsersList.readyState == 4 && DownloadUsersList.status == 200) {
			if((DownloadUsersList.responseText.indexOf("file-created") >= 0)) {
				<?php 
					$upload_dir = wp_upload_dir();
					$create_file_url = $upload_dir['baseurl'].'/all-users-list.csv';
				?>
				window.open('<?php echo $create_file_url; ?>', '_blank');
			}
		}

		if(DownloadUsersList.status == 404) {
			alert('File not found & object not responding.');
			return false;
		}
	};		
	//data post by object
	DownloadUsersList.open("POST", location.href, true);
	DownloadUsersList.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	DownloadUsersList.send('action=download-user-list');
}

function ManageContactQueries(action, id) {
	//delete query
	if(action == "delete-contact-query") {
		if(confirm('Are you sure want to delete this contact query?')) {
			//ajax code
			var MangeCQ = new XMLHttpRequest();
			//check object request & response
			MangeCQ.onreadystatechange = function() {
				if (MangeCQ.readyState == 4 && MangeCQ.status == 200) {
					if((MangeCQ.responseText.indexOf("success-delete") >= 0)) {
						jQuery( "#cq-" + id ).fadeOut( 1500, "linear" );
					}
				}

				if(MangeCQ.status == 404) {
					alert('File not found & object not responding.');
					return false;
				}
			};		
			//data post by object
			MangeCQ.open("POST", location.href, true);
			MangeCQ.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			MangeCQ.send('action=' + action + '&id=' + id );
		}
	}
	
	//delete all query
	if(action == "delete-all-queries") {
		if(confirm('Are you sure want to delete all selected Queries?')) {
			var deleteallids = [];
			jQuery('input:checkbox:checked').map(function() {
				if(jQuery.isNumeric(this.value)) {
					deleteallids.push(this.value);
				}
			});
			console.log(deleteallids);
			
			// check if any website selected or not
			if(deleteallids.length) {
			
				//create ajax object
				var ManageCQ = new XMLHttpRequest();

				//check object request & response
				ManageCQ .onreadystatechange = function() {
					if (ManageCQ .readyState == 4 && ManageCQ .status == 200) {
						console.log(ManageCQ .responseText);
						for (i = 0; i < deleteallids.length; i++) {
							jQuery( "#cq-" + deleteallids[i] ).fadeOut( 1500, "linear" );
						}
					}

					if(ManageCQ .status == 404) {
						alert('File not found & object not responding.');
						return false;
					}
				};		

				//data post by object
				ManageCQ .open("POST", location.href, true);
				ManageCQ .setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ManageCQ .send('action=' + action + '&id=' + deleteallids );
			} else {
				alert("No Queries is selected to delete.");
			}
		}
	}
	
	
	//show query
	if(action == "view-contact-query") {
		//show modal form
		jQuery("#user-modal-form").show();
		jQuery("#loading-msg").show();
					
		var PostData = 'action=' + action + '&id=' + id ;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : "?page=cfw-all-queries",
			cache: false,
			data : PostData,
			complete : function() {  },
			success: function(data) {
				jQuery("#loading-msg").hide();
				jQuery('#query-details').html(jQuery(data).find('div#view-query-data'));					
			}
		});
	}
}



// select all check boxes jQuery
jQuery(document).ready(function() {
	jQuery("#checkAll").change(function () {
		jQuery("input:checkbox").prop('checked', jQuery(this).prop("checked"));
	});
});
</script>
<?php
// post query request
if(isset($_POST['action'])) {
	$action = $_POST['action'];
	
	//view contact query
	if($action == "view-contact-query") {
		$id =(int)$_POST['id'];
		global $wpdb;
		$table_name = $wpdb->prefix . 'awp_contact_form';
		$user_searh_query_result = $wpdb->get_row( "SELECT * FROM `$table_name` WHERE `id` ='$id'" );
		if($user_searh_query_result){
			$id = $user_searh_query_result->id;
			$name = $user_searh_query_result->name;
			$email = $user_searh_query_result->email;
			$date_time = $user_searh_query_result->date_time;
			$subject = $user_searh_query_result->subject;
			$message = $user_searh_query_result->message;
			?>
			<div id="view-query-data">
				<p><strong>User Name: </strong><?php echo $name; ?></p>
				<p><strong>User Email: </strong><?php echo $email; ?></p>
				<p><strong>User Subject: </strong><p><?php echo $subject; ?></p></p>
				<p><strong>User Query: </strong><p><?php echo $message; ?></p></p>
				<p><strong>Date Time: </strong><?php echo $date_time; ?></p>
			</div>
			<?php
		}
	}

	//delete query
	if($action == "delete-contact-query") {
		$id =(int)$_POST['id'];
		global $wpdb;
		$table_name = $wpdb->prefix . 'awp_contact_form';
		$where = array('id' => $id);
		$where_format = array( '%d' );
		if($wpdb->delete($table_name, $where, $where_format )) {
			echo "success-delete.";
		} else {
			echo "failed-delete.";
		}
	}

	//multiple delete user queries
	if($action == "delete-all-queries") {
		global $wpdb;
		$table_name = $wpdb->prefix . 'awp_contact_form';
		$ids = (int)explode("," ,$_POST['id']); 	
		$count = count($ids);
		$n = 0;
		if(is_array($ids)) {
			foreach($ids as $id) {
				if($wpdb->query("DELETE FROM `$table_name` WHERE  `id` = '$id'")) {
					$n++;
				}
			}

			if($n == $count){
				echo "<h1 class='text-center alert alert-success'>All selected contact query deleted successfully.</h1>";
			} else {
				echo"<h1 class='text-center alert alert-danger'>Error: Unable to delete selected contact query.</h1>";
			}
		}
		
	}
	
	//download user list
	if($action == "download-user-list") {
		$upload_dir = wp_upload_dir();
		$create_file_path = $upload_dir['basedir'];
		// file create and open
		if($users_list_file = fopen($create_file_path."/all-users-list.csv", "w")) {
			// fetch all users form database
			$first_line_to_write = "#, Name, Email \n";
			fwrite($users_list_file, $first_line_to_write);
			
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$user_searh_query_result = $wpdb->get_results( "SELECT * FROM `$table_name`" );
			if(count($user_searh_query_result)){
				$no = 1;
				foreach($user_searh_query_result as $single_row ){
					$id = $single_row->id;
					$name = $single_row->name;
					$email = $single_row->email;
					$txt_to_write = "$no, $name, $email \n";
					fwrite($users_list_file, $txt_to_write);
					$no++;
				}
			}
			fclose($users_list_file);
			echo "file-created.";
		} else {
			echo "File not created.";
		}
	}
}
?>