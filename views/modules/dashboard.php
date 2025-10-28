<?php 

$reportes = ControllerReportes::ctrSumaReportes();  

?>
<div class="main-content">

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Informe</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="card-title text-muted">Total reportes</h4>
                        <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-up text-success me-2"></i><b><?php echo $reportes["total_reportes"] ?></b>
                        </h2>
                        <!-- <p class="text-muted mb-0 mt-3"><b>48%</b> From Last 24 Hours</p> -->
                    </div>
                </div>
            </div>

            <!-- <div class="col-sm-6 col-lg-3">
                <div class="card text-center">
                    <div class="card-body p-t-10">
                        <h4 class="card-title text-muted mb-0">Order Status</h4>
                        <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-up text-success me-2"></i><b>6521</b>
                        </h2>
                        <p class="text-muted mb-0 mt-3"><b>42%</b> Orders in Last 10 months</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card text-center">
                    <div class="card-body p-t-10">
                        <h4 class="card-title text-muted mb-0">Unique Visitors</h4>
                        <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-up text-success me-2"></i><b>452</b>
                        </h2>
                        <p class="text-muted mb-0 mt-3"><b>22%</b> From Last 24 Hours</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card text-center">
                    <div class="card-body p-t-10">
                        <h4 class="card-title text-muted mb-0">Monthly Earnings</h4>
                        <h2 class="mt-3 mb-2"><i class="mdi mdi-arrow-down text-danger me-2"></i><b>5621</b>
                        </h2>
                        <p class="text-muted mb-0 mt-3"><b>35%</b> From Last 1 Month</p>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- end row -->

        <!-- end row -->
    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © Appzia.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign
                </div>
            </div>
        </div>
    </div>
</footer>
</div>