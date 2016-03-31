<head>
    <script type="text/javascript">
        $(document).ready(function () {
            removeDuplicateOfCurrentlySelectedGroupFromOptions();
            addChangeListenerToGroupSelector();
        });

        function removeDuplicateOfCurrentlySelectedGroupFromOptions() {
            //if the user currently has a set group
            if ($('.option-for-current-working-group').length > 0) {
                var selectedGroupId = $('.option-for-current-working-group').val();

                $('#working-group-selector option[value=' + selectedGroupId + ']')[1].remove();
            } else {
                $('#working-group-selector').val($('#default-working-group-selector-option').val());
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

            var groupName = $('#working-group-selector option[value=' + groupId + ']').data("name");

            var explanationText = $('<p>If you change your working group to "' + 
                groupName + 
                '" all tasks (transcribing, tagging, connected and discussing) will be logged as work done for "' +
                groupName +
                '".</p>' +
                '<p><strong>Work can only be logged as being done for one group at a time.</strong></p>' +
                '<p>Are you sure you want to proceed with this change?</p>');

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
                        markNewOptionSelection(groupId);
                        notifyOfSuccessfulActionWithTimeout("Working group successfully changed!");
                    } else {
                        resetOptionSelection();
                        notifyOfErrorInForm("Something went wrong, try again.");
                    }
                }
            });
        };

        function markNewOptionSelection(groupId) {
            $('.option-for-current-working-group').prop('disabled', false);
            $('.option-for-current-working-group').removeClass('option-for-current-working-group');

            $('#working-group-selector option[value=' + groupId + ']').addClass('option-for-current-working-group');
            $('.option-for-current-working-group').prop('disabled', true);
        };

        function resetOptionSelection() {
            $('#working-group-selector').val($('.option-for-current-working-group').val());
        };
    </script>
</head>

<body>
    <span id="working-group-header">Working Group:</span>
    <select id="working-group-selector" class="form-control" name="task">
        <option id="default-working-group-selector-option" value="none" disabled>No group selected</option>
        <?php if ($_SESSION['Incite']['USER_DATA']['working_group']['id'] > 0): ?>
            <option value="<?php echo $_SESSION['Incite']['USER_DATA']['working_group']['id']; ?>" class="option-for-current-working-group" selected disabled><?php echo (strlen($_SESSION['Incite']['USER_DATA']['working_group']['name']) > 18) ? substr($_SESSION['Incite']['USER_DATA']['working_group']['name'],0,15).'...' : $_SESSION['Incite']['USER_DATA']['working_group']['name']; ?></option>
        <?php endif; ?>

        <?php foreach ((array)getGroupsByUserId($_SESSION['Incite']['USER_DATA']['id']) as $group): ?>
            <option data-name="<?php echo $group['name']; ?>" value="<?php echo $group['id']; ?>"><?php echo (strlen($group['name']) > 18) ? substr($group['name'],0,15).'...' : $group['name']; ?></option>
        <?php endforeach; ?>
    </select>
</body>

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
</style>
