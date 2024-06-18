<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');
    $_SESSION['table'] = 'adminstaffs';
    $user = $_SESSION["user"];

    $show_table = 'adminstaffs';
    $users = include("../process/show.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Add Users</title>
    <?php include("../fixed/app-header-scripts.php"); ?>
</head>
<body>
    <div id="dashboardMainCont">
        <?php include('../fixed/app-sidebar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('../fixed/app-topnav.php') ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="sectionHeader"><i class="fa fa-list"></i> List of Admins</h1>
                            <div class="sectionContent">
                                <div class="admins">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tbody>
                                                    <?php foreach($users as $index => $user){?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td class="firstName"><?= $user['first_name'] ?></td>
                                                        <td class="lastName"><?= $user['last_name'] ?></td>
                                                        <td class="email"><?= $user['email'] ?></td>
                                                        <td><?= date('M d,Y @ H:i:s A', strtotime($user['created_at'])) ?></td>
                                                        <td><?= date('M d,Y @ H:i:s A', strtotime($user['updated_at'])) ?></td>
                                                        <td>
                                                            <a href="" class="updateAdmin" data-userid="<?= $user['id'] ?>"><i class="fa fa-pencil"></i>Edit</a>
                                                            <a href="" class="deleteAdmin" data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>" data-lname="<?= $user['last_name'] ?>"><i class="fa fa-trash"></i>Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </thead>
                                        </table>
                                    <p class="adminCount"><?= count($users)?> Admins </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
    </div>
    <?php include("../fixed/app-scripts.php"); ?>
    <script>
        var script = function script() {
    this.initialize = function() {
        this.registerEvents();
    },
    this.registerEvents = function() {
        document.addEventListener('click', function(e){
            targetElement = e.target;
            classList = targetElement.classList;

            if (classList.contains('deleteAdmin')) {
                e.preventDefault();
                userId = targetElement.dataset.userid;
                fname = targetElement.dataset.fname;
                lname = targetElement.dataset.lname;
                fullName = fname + ' ' + lname;

                if (typeof fullName !== 'undefined' && window.confirm('Are you sure to delete: ' + fullName + '?')) {
                    $.ajax({
                        method: 'POST',
                        data: {
                            id: userId,
                            table: 'adminstaffs'
                        },
                        url: '../process/delete.php',
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                // Show a confirmation modal here
                                alert(data.message); // This alert confirms the deletion

                                // Automatically reload the page after the alert is dismissed
                                location.reload();
                            } else {
                                console.log(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    console.log('will not delete');
                }
            }


            if (classList.contains('updateAdmin')) {
                e.preventDefault();

                // Get data
                firstName = targetElement.closest('tr').querySelector('td.firstName').textContent;
                lastName = targetElement.closest('tr').querySelector('td.lastName').textContent;
                email = targetElement.closest('tr').querySelector('td.email').textContent;
                userId = targetElement.dataset.userid;

                // Create Bootstrap modal HTML
                var modalHTML = '<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" style="z-index: 1050;">\
                    <div class="modal-dialog" role="document">\
                        <div class="modal-content">\
                            <div class="modal-header">\
                                <h5 class="modal-title" id="updateModalLabel">Update ' + firstName + ' ' + lastName + '</h5>\
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                    <span aria-hidden="true">&times;</span>\
                                </button>\
                            </div>\
                            <div class="modal-body">\
                                <form id="updateForm">\
                                    <div class="form-group">\
                                        <label for="firstName">First Name:</label>\
                                        <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="lastName">Last Name:</label>\
                                        <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="email">Email address:</label>\
                                        <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                                    </div>\
                                </form>\
                            </div>\
                            <div class="modal-footer">\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
                                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>\
                            </div>\
                        </div>\
                    </div>\
                </div>';

                // Append modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);

                // Show modal
                $('#updateModal').modal('show');

                // Handle save changes button click
                document.getElementById('saveChanges').addEventListener('click', function() {
                    $.ajax({
                        method: 'POST',
                        data: {
                            user_id: userId,
                            f_name: document.getElementById('firstName').value,
                            l_name: document.getElementById('lastName').value,
                            email: document.getElementById('emailUpdate').value,
                        },
                        url: 'user-update.php',
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                if (window.confirm(data.message)) {
                                    location.reload();
                                }
                            } else {
                                console.log(data.message);
                            }
                        }    
                    });

                    // Hide modal
                    $('#updateModal').modal('hide');
                });
            }
        });
    }
};

var script = new script;
script.initialize();
    </script>
</body>
</html>