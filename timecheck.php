<style>
    .report-container {
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .report-table th {
        background: #2563eb;
        color: white;
    }

    .task-list {
        max-height: 90px;
        /* approximately 3 tasks */
        overflow-y: auto;
        text-align: left;
    }

    .task-list div {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
</style>
<div class="report-container">
    <table class="report-table">
        <thead>
            <tr>
                <th>Month</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>Written Work</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>June</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>
                <td>✓</td>

                <td>
                    <div class="task-list">
                        <div>Task 1 - Site Visit</div>
                        <div>Task 2 - Attendance Check</div>
                        <div>Task 3 - Report Submission</div>
                        <div>Task 4 - Client Meeting</div>
                        <div>Task 5 - Documentation</div>
                        <div>Task 6 - Follow Up</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>