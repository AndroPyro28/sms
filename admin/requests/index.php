<?php 
include_once "../classes/DBConnection.php";

// Your existing code...

// Create an instance of DBConnection
$dbConnection = new DBConnection();

// Access the first database connection
$conn = $dbConnection->conn2;
?>


<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Orders and Back Order List</h3>
        <!-- <div class="card-tools">
			<a href="<?php echo base_url ?>admin/?page=back_order/manage_bo" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date requested</th>
                            <th>Content</th>
                            <th>Barangay Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT  r.*,  b.name as barangay_name FROM requests r INNER JOIN barangay b ON b.barangay_id = r.barangay_id");
                        while($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['createdAt'])) ?></td>
                                <td><?php echo $row['content'] ?></td>
                                <td><?php echo $row['barangay_name'] ?></td>
                                <td class="text-center">
                                    <?php if($row['request_status'] == "PENDING"): ?>
                                        <span class="badge badge-warning rounded-pill">Pending</span>
                                    <?php elseif($row['request_status'] == "ONGOING"): ?>
                                        <span class="badge badge-primary rounded-pill">On-Going</span>
                                        <?php elseif($row['request_status'] == "COMPLETED"): ?>
                                        <span class="badge badge-success rounded-pill">Received</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger rounded-pill">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                    <?php if($row['request_status'] == "PENDING"): ?>
                                        <a class="dropdown-item" href="<?php echo base_url.'admin?page=requests/manage_request&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-boxes text-dark"></span> Manage</a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                        <a class="dropdown-item" href="<?php echo base_url.'admin?page=back_order/view_bo&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Back Order permanently?","delete_bo",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Payment Details","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_bo($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_bo",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>