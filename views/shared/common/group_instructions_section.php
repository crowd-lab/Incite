<head>
    <?php
        function populateWorkingGroupInstructions() {
            $db = get_db();
            $groupsUsersTable = $db->getTable('InciteGroupsUsers');
            $user = $_SESSION['Incite']['USER_DATA'];
            $groupsWhoseInstructionsHaveBeenSeenByUserArr = array();
            $groupsWhoseInstructionsHaveBeenSeenByUser = $groupsUsersTable->findSeenGroupInstructionsByUserId($user->id);
            foreach ((array)$groupsWhoseInstructionsHaveBeenSeenByUser as $group) {
                $groupsWhoseInstructionsHaveBeenSeenByUserArr[] = $group->id;
            }

            $workingGroupId = 0;
            $workingGroupHasInstructions = false;
            if (isset($user->working_group_id)) {
                $workingGroupId = $user->working_group_id;
            }

            foreach((array)$groupsUsersTable->findGroupsByUserId($user->id) as $group) {
                if ($group->instructions != '' && $workingGroupId == $group->id) {
                    $workingGroupHasInstructions = true;
                    echo 'addGroupInstruction(' . sanitizeStringInput($group->name) . '.value, ' . sanitizeStringInput($group->instructions) . '.value);';
            
                    if (!in_array($group->id, $groupsWhoseInstructionsHaveBeenSeenByUserArr)) {
                        echo 'addNewIconToSection();';
                    }
                }
            }

            if (!$workingGroupHasInstructions) {
                echo 'hideGroupInstructions();';
            }
        }
    ?>

    <script type="text/javascript">
        function addGroupInstruction(groupName, instructions) {
            var section = $('<p class="group-instructions-text">' + instructions + '</p>');

            $('#group-instructions-collapsible-section').append(section);
        }

        function addNewIconToSection() {
            var icon = $('<span class="label label-danger instructions-alert-icon" aria-hidden="true">New</span>');

            $('#collapse-glyph').append(icon);
        }

        function hideGroupInstructions() {
            $('#group-instructions-container').remove();
        }

        function setGlyphiconToCollapsed() {
            $('#group-instructions-section-header').find('.glyphicon').removeClass('glyphicon-collapse-up');
            $('#group-instructions-section-header').find('.glyphicon').addClass('glyphicon-collapse-down');
        }

        function setGlyphiconToExpanded() {
            $('#group-instructions-section-header').find('.glyphicon').removeClass('glyphicon-collapse-down');
            $('#group-instructions-section-header').find('.glyphicon').addClass('glyphicon-collapse-up');
        }

        $(document).ready(function () {
            <?php populateWorkingGroupInstructions(); ?>

            $('#group-instructions-collapsible-section').on('hidden.bs.collapse', function (e) {
                setGlyphiconToCollapsed();

                //comes from header
                <?php markWorkingGroupInstructionsAsSeen(); ?>
            });

            $('#group-instructions-collapsible-section').on('shown.bs.collapse', function (e) {
                setGlyphiconToExpanded();
            });
        });
    </script>
</head>

<body>
    <div id="group-instructions-container">
        <span data-toggle="collapse" data-target="#group-instructions-collapsible-section" id="group-instructions-section-header">
            Instructions for <?php echo $_SESSION['Incite']['USER_DATA']['working_group']['name']; ?>
            <span id="collapse-glyph" class="glyphicon glyphicon-collapse-down"></span>
        <span>
        <div id="group-instructions-collapsible-section" class="collapse">
        </div>
    </div>
</body>

<style>
    #group-instructions-container {
        max-width: 97%;
        margin: 0px auto;
    }

    #group-instructions-section-header {
        color: #286090;
        cursor: pointer;
    }

    #group-instructions-collapsible-section {
        border: 1px solid lightgrey;
        color: black;
        margin-bottom: 50px;
    }

    #collapse-glyph {
        margin-bottom: 10px;
    }

    .group-instructions-text {
        margin-top: 10px;
    }
</style>
