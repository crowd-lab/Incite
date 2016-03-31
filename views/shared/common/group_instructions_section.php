<head>
    <?php
        function populateGroupInstructions() {
            //these are loaded with the header
            global $groupsWithOldInstructions;
            global $groupsWithNewInstructions;

            foreach((array)$groupsWithNewInstructions as $group) {
                echo 'addGroupInstruction(' . sanitizeStringInput($group['name']) . '.value, ' .sanitizeStringInput($group['instructions']) . '.value, true);';
            }

            foreach((array)$groupsWithOldInstructions as $group) {
                echo 'addGroupInstruction(' . sanitizeStringInput($group['name']) . '.value, ' .sanitizeStringInput($group['instructions']) . '.value, false);';
            }

            if (count($groupsWithNewInstructions) != 0) {
                echo 'addNewIconToSection();';
            }

            if (count($groupsWithNewInstructions) == 0 && count($groupsWithOldInstructions) == 0) {
                echo 'hideGroupInstructions();';
            }
        }
    ?>

    <script type="text/javascript">
        function addGroupInstruction(groupName, instructions, isNew) {
            if (isNew) {
                var section = $('<span class="label label-danger instructions-alert-icon-in-modal" aria-hidden="true">New</span><h1 class="group-instructions-header">' + groupName + ':</h1>' +
                    '<p class="group-instructions-body">' + instructions + '</p>' +
                    '<hr size=2>');
            } else {
                var section = $('<h1 class="group-instructions-header">' + groupName + ':</h1>' +
                    '<p class="group-instructions-body">' + instructions + '</p>' +
                    '<hr size=2>');
            }

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
            <?php populateGroupInstructions(); ?>

            $('#group-instructions-collapsible-section').on('hidden.bs.collapse', function (e) {
                setGlyphiconToCollapsed();

                //comes from header
                <?php markAllInstructionsAsSeen(); ?>
            });

            $('#group-instructions-collapsible-section').on('shown.bs.collapse', function (e) {
                setGlyphiconToExpanded();
            });

            $("#group-instructions-collapsible-section").find('hr:last').remove();
        });
    </script>
</head>

<body>
    <div id="group-instructions-container">
        <span data-toggle="collapse" data-target="#group-instructions-collapsible-section" id="group-instructions-section-header">
            Group Instructions
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
        max-height: 90px;
        overflow-y: scroll;
        border: 1px solid lightgrey;
        color: black;
    }
</style>
