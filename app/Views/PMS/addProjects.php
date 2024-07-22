<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle = '[Admin] Add Projects' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
</head>
<body>
<header>
    <?php include 'header.php' ?>
</header>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title text-center">Add New Project</h3>
                </div>
                <div class="card-body">
                    <form id="addProjectForm" action="<?php echo base_url('/projects/add') ?>" method="post">
                        <!-- Project Details -->
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Enter Project Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_accepted" class="form-label">Date Accepted</label>
                            <input type="date" class="form-control" id="date_accepted" name="date_accepted" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">In Progress</option>
                                <option value="2">Completed</option>
                                <option value="3">Canceled</option>
                                <option value="4">Postponed</option>
                            </select>
                        </div>

                        <!-- Address Details -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Project Address</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="street" class="form-label">Street</label>
                                    <input type="text" class="form-control" id="street" name="street" placeholder="Enter Street" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
                                </div>
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-select" id="state" name="stateID" required>
                                        <!-- Add options dynamically from your database -->
                                        <?php foreach ($states as $state): ?>
                                            <option value="<?php echo $state['stateID']; ?>"><?php echo $state['stateName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="zipCode" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="Enter Zip Code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="countryID" required>
                                        <!-- Add options dynamically from your database -->
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?php echo $country['countryID']; ?>"><?php echo $country['countryName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
