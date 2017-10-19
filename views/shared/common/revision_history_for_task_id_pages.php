<head>
    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>
</head>
<body>
    <div id="revision-history-container" style="display: none;">
        <div id="revision-history-header-container">
            <h3 id="revision-history-header">Revision History</h3>
            <a id="view-editing-link">Return to editing..</a>
        </div>

        <table  style="border: 1px solid black;">
            <tbody id="revision-history-table-body">
                <tr>
                    <th>User</th>
                    <th>Edited On</th>
                </tr>
                <?php foreach ((array)$this->revision_history as $revision): ?>
                    <tr>
                        <td><a href="<?php echo getFullInciteUrl();?>/users/view/<?php echo $revision->user_id; ?>"><?php echo $revision->email; ?></a></td>
                        <td><?php echo $revision->timestamp; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

<style>
    tr {
        text-align: center;
    }

    th {
        text-align: center;
    }

    table {
        width: 100%;
    }

    #revision-history-container {
        position: relative;
        top: -13px;
    }

    #revision-history-header {
        text-align: center;
        margin-top: -20px;
    }

    #view-editing-link {
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
    }
</style>
