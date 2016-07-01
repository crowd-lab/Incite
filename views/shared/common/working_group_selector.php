<head>
    <script type="text/javascript">
        $(document).ready(function () {
            removeDuplicateOfCurrentlySelectedGroupFromOptions();
            addChangeListenerToGroupSelector();
            addListenerToInfoGlyphicon();
        });

        function removeDuplicateOfCurrentlySelectedGroupFromOptions() {
            //if the user currently has a set group
            if ($('.option-for-current-working-group').length > 0) {
                var selectedGroupId = $('.option-for-current-working-group').val();

                $('#working-group-selector option[value=' + selectedGroupId + ']')[1].remove();
            } else {
                $('#working-group-selector').val($('#default-working-group-selector-option').val());
                $('#reset-option').remove();
            }
        };

        function addChangeListenerToGroupSelector() {
            $('#working-group-selector').change(function(e) {
                var groupName = $('#working-group-selector option:selected').html();
                var groupId = $('#working-group-selector option:selected').val();

                if (groupName === "No group selected") {
                    return;   
                }

                promptUserToChangeWorkingGroup(groupId);
            });
        };

        /**
         * This function deals with the working-group-dialog, which is included with the header
         * as it needs to be at the top level of the html
         */
        function promptUserToChangeWorkingGroup(groupId) {
            $('#working-group-modal-body').empty();
            $('#working-group-dialog').modal('show');

            var toGroupName = $('#working-group-selector option[value=' + groupId + ']').data("name");
            var fromGroupName;
            
            if ($('.option-for-current-working-group').length) {
                fromGroupName = $('.option-for-current-working-group').data("name");
            } else {
                fromGroupName = "No Previous Working Group";
            }

            if (groupId === "0") {
                var explanationText = $('<p><strong>If you reset your working group tasks will no longer be logged under that group.</strong></p>' +
                    '<p>Are you sure you want to proceed with this change?</p>');

                $('#working-group-dialog-label').html("Are you sure you want to reset your working group?");
                $('#working-group-modal-yes-btn').html("Yes, Reset Group");
            } else {
                var explanationText = $('<h4><b><i>Changing From:</i></b> ' + fromGroupName + '</h4>' +
                    '<h4><b><i>Changing To:</i></b> ' + toGroupName + '</h4>' +
                    '<p>If you change your working group all tasks (transcribing, tagging, connected and discussing) will be logged as work done for "' +
                    toGroupName +
                    '".</p>' +
                    '<p><strong>Work can only be logged as being done for one group at a time.</strong></p>' +
                    '<p>Are you sure you want to proceed with this change?</p>');
            }
            

            $('#working-group-modal-body').append(explanationText);

            $('#working-group-modal-yes-btn').off();
            $('#working-group-modal-yes-btn').click(function(e) {
                setWorkingGroupAjaxRequest(groupId);
            });

            $('#working-group-modal-no-btn').off();
            $('#working-group-modal-cancel-btn').off();
            $('#working-group-modal-no-btn').click(resetOptionSelection);
            $('#working-group-modal-cancel-btn').click(resetOptionSelection);
        };

        function setWorkingGroupAjaxRequest(groupId) {
            var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/setworkinggroup'; ?>",
                data: {"userId": <?php echo $_SESSION['Incite']['USER_DATA']['id'] ?>, "groupId": groupId},
                success: function (response) {
                    //response will be true or false depending of if group is set or not
                    if (response) {
                        notifyOfSuccessfulActionWithTimeout("Working group successfully changed!");

                        //reloading is easiest way to get new instructions for group
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        resetOptionSelection();
                        notifyOfErrorInForm("Something went wrong, try again.");
                    }
                }
            });
        };

        function resetOptionSelection() {
            if ($('.option-for-current-working-group').val()) {
                $('#working-group-selector').val($('.option-for-current-working-group').val());
            } else {
                $('#working-group-selector').val("none");
            }
            
        };

        function addListenerToInfoGlyphicon() {
            $('#working-group-info-glyphicon').click(function(e) {
                $('#instructions-dialog').modal('show');
            });
        };
    </script>
</head>

<div style="margin-left: 25px;">
    <span id="working-group-header">Working Group:
        <span id="working-group-info-glyphicon" class="glyphicon glyphicon-info-sign"></span>
    </span>
    <select id="working-group-selector" class="form-control" name="task">
        <option id="default-working-group-selector-option" value="none" disabled>No Group Selected</option>
        <?php if ($_SESSION['Incite']['USER_DATA']['working_group']['id'] > 0): ?>
            <option data-name="<?php echo $_SESSION['Incite']['USER_DATA']['working_group']['name']; ?>" value="<?php echo $_SESSION['Incite']['USER_DATA']['working_group']['id']; ?>" class="option-for-current-working-group" selected disabled><?php echo (strlen($_SESSION['Incite']['USER_DATA']['working_group']['name']) > 23) ? substr($_SESSION['Incite']['USER_DATA']['working_group']['name'],0,20).'...' : $_SESSION['Incite']['USER_DATA']['working_group']['name']; ?></option>
        <?php endif; ?>

        <?php foreach ((array)getGroupsByUserId($_SESSION['Incite']['USER_DATA']['id']) as $group): ?>
            <option data-name="<?php echo $group['name']; ?>" value="<?php echo $group['id']; ?>"><?php echo (strlen($group['name']) > 23) ? substr($group['name'],0,20).'...' : $group['name']; ?></option>
        <?php endforeach; ?>
        <option id="reset-option" value="0">Reset Working Group</option>
    </select>
</div>

<style>
    #working-group-header {
        color: #9D9D9D;
    }

    #working-group-selector {
        height: 25px;
        line-height: 25px;
        padding: 1px;
    }

    #default-working-group-selector-option {
        display: none;
    }

    .option-for-current-working-group {
        display: none;
    }

    #working-group-info-glyphicon {
        top: 2px;
    }

    #working-group-info-glyphicon:hover {
        color: #3B6778; 
        cursor: pointer;
    }

    #reset-option {
        color: #D9534F;
    }
</style>
