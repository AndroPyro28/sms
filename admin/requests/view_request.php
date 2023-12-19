<?php
include_once "../classes/DBConnection.php";

// Your existing code...

// Create an instance of DBConnection
$dbConnection = new DBConnection();

// Access the first database connection
$conn2 = $dbConnection->conn2;
$conn1 = $dbConnection->conn1;


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $requestQuery = "SELECT r.request_status, r.content, b.name, b.barangay_id FROM requests r INNER JOIN barangay b ON r.barangay_id = b.barangay_id WHERE r.id = $id";
    $requestResult = $conn2->query($requestQuery);
    $requestDetails = $requestResult->fetch_assoc();
    // echo "<pre> $requestDetails </pre>" ;
    // Get inventory items associated with the request
        // check if status is not pending then redirect to requests list
        if ($requestDetails['request_status'] != "ONGOING") {
            // redirect('admin/?page=requests');
            // exit(); // Ensure that the script stops execution after the redirect
    }
}
?>
<style>
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>

<!-- design a php page like a manage request where we can fetch the request data -->
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Request details</h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">Barangay</label>
                        <div>
                            <?php echo $requestDetails['name'] ?>
                            <input type="hidden" value="<?php echo $requestDetails['content'] ?>">
                            <input type="hidden" value="<?php echo $requestDetails['barangay_id'] ?>" id="barangay_id">
                            <input type="hidden" value="<?php echo $id ?>" id="request_id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-info">Description of the request</label>
                        <div>
                            <?php echo $requestDetails['content'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-stripped">
                <colgroup>
                    <col width="20%">
                    <col width="20%">
                    <col width="40%">
                </colgroup>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $inventoryQuery = "SELECT * FROM inventory WHERE request_id = $id";
                    $inventoryResult = $conn2->query($inventoryQuery);
                    while ($row = $inventoryResult->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo $row['inventory_id'] ?>
                            </td>
                            <td>
                                <?php echo $row['name'] ?>
                            </td>
                            <td>
                                <?php echo $row['quantity'] ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=requests' ?>">Back To List</a>
        <button class="btn btn-flat btn-success" type="button" id="submitBtn">Mark as Completed</button>
    </div>
</div>
<table id="clone_list" class="d-none">
    <!-- ... (your existing code) ... -->
</table>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Submit button click event
        $('#submitBtn').on('click', function () {
            const request_id = $('#request_id').val();
            console.log(request_id)
            // AJAX call to your API
            $.ajax({
                type: 'POST',
                url:_base_url_+"action/mark_as_completed_request.php",
                data: { request_id: request_id },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.status === 200) {
                        alert('Request marked as completed successfully');
                        // window.location.href = 'sms/admin/?page=requests';
                    } else {
                        alert('Failed to mark request as completed: ' + response.message);
                    }
                },
            });
            alert('Request marked as completed successfully');
            window.location.assign('/sms/admin/?page=requests')
        });
    });
</script>