<?php 
include_once "../classes/DBConnection.php";

// Your existing code...

// Create an instance of DBConnection
$dbConnection = new DBConnection();

// Access the first database connection
$conn1 = $dbConnection->conn1;

?>


<h1 class="">Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Orders Records</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `purchase_order_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-boxes"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Receiving Records</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `receiving_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-exchange-alt"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">BO Records</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `back_order_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-undo"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Return Records</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `return_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
   
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-navy elevation-1"><i class="fas fa-truck-loading"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Suppliers</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `supplier_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    
    <?php if($_settings->userdata('type') == 1): ?>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-teal elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Users</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn1->query("SELECT * FROM `users` where id != 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <?php endif; ?>
</div>