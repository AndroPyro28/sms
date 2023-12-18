<?php
include_once "../classes/DBConnection.php";

// Your existing code...

// Create an instance of DBConnection
$dbConnection = new DBConnection();

// Access the first database connection
$conn2 = $dbConnection->conn2;
$conn1 = $dbConnection->conn1;

$requestData = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $qry = $conn2->query("
    SELECT r.*, b.name as barangay_name, b.barangay_id as barangay_id FROM requests r 
    INNER JOIN barangay b 
    ON b.barangay_id = r.barangay_id
    LEFT JOIN inventory i
    ON i.barangay_id = r.barangay_id
    WHERE id = {$id}
    ");

    // Fetch data and store it in $requestData array using mysqli_fetch_assoc
    if ($qry) {
        $requestData = $qry->fetch_assoc();
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
                            <?php echo $requestData['barangay_name'] ?>
                            <input type="hidden" value="<?php echo $requestData['id'] ?>" id="request_id">

                            <input type="hidden" value="<?php echo $requestData['barangay_id'] ?>" id="barangay_id">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-info">Description of the request</label>
                        <div>
                            <?php echo $requestData['content'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-stripped">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="20%">
                    <col width="40%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Supplier</th>
                        <th>Description</th>
                        <th>Available Stocks</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn1->query("SELECT i.*,s.name as supplier FROM `item_list` i inner join supplier_list s on i.supplier_id = s.id order by `name` asc");
                    while ($row = $qry->fetch_assoc()):
                        $in = $conn1->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type = 1")->fetch_array()['total'];
                        $out = $conn1->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type = 2")->fetch_array()['total'];
                        $row['available'] = $in - $out;
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo $row['id'] ?>
                            </td>
                            <td>
                                <?php echo $row['name'] ?>
                            </td>
                            <td>
                                <?php echo $row['supplier'] ?>
                            </td>
                            <td>
                                <?php echo $row['description'] ?>
                            </td>
                            <td class="text-right available-stock">
                                <?php echo number_format($row['available']) ?>
                            </td>
                            <td class="text-right">
                                <input type="number" class="form-control qty" value="0" min="0"
                                    max="<?php echo $row['available']; ?>">
                            </td>
                            <td style="display:flex; gap:5px;">
                                <button class="btn btn-danger qty-decrement"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-success qty-increment"><i class="fa fa-plus"></i></button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=requests' ?>">Back To List</a>
        <button class="btn btn-flat btn-success" type="button" id="submitBtn">Submit</button>
    </div>
</div>
<table id="clone_list" class="d-none">
    <!-- ... (your existing code) ... -->
</table>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Increment quantity
        $('body').on('click', '.qty-increment', function () {
            var input = $(this).closest('tr').find('.qty');
            var currentQty = parseInt(input.val());
            var maxQty = parseInt(input.attr('max'));
            if (currentQty < maxQty) {
                input.val(currentQty + 1);
            }
        });

        // Decrement quantity
        $('body').on('click', '.qty-decrement', function () {
            var input = $(this).closest('tr').find('.qty');
            var currentQty = parseInt(input.val());
            if (currentQty > 0) {
                input.val(currentQty - 1);
            }
        });

        // Submit button click event
        $('#submitBtn').on('click', function () {
            var data = [];

            // Loop through each row in the table
            $('.table tbody tr').each(function () {
                var row = $(this);
                var id = row.find('.text-center').text().trim(); // Item ID
                var name = row.find('td:nth-child(2)').text().trim(); // Item Name
                var qty = row.find('.qty').val(); // Quantity

                // Add the data to the array
                data.push({
                    id: id,
                    name: name,
                    qty: qty
                });
            });

            // Log the data to the console
            console.log(data);

            // You can now use the 'data' array to send the information to your backend or perform any other actions.
            // Example: Send data to a PHP script using AJAX

            // i want to submit this when clicked the submit button
            const barangay_id = document.querySelector('#barangay_id').value;
            const request_id = document.querySelector('#request_id').value;
            $.ajax({
                type: 'POST',
				url:_base_url_+"action/update_request.php",
                data: {items: data, barangay_id: barangay_id, request_id},
                dataType: 'json',
                success: function (response) {
                    console.log('success');
                    console.log(response);
                    if (response.success) {
                        alert('Request submitted successfully');
                        window.location.reload()
                    } else {
                        alert('Error submitting request: ' + response.message);
                    }
                },
                error: function (error) {
                    console.log('error');
                    console.log(error);
                    alert('Error submitting request. Check the console for details.');
                }
            });
        });
    });
</script>