<h1>Monthly Report</h1>

<table border="1">

    <tr>
        <th>Employee</th>
        <th>Present Days</th>
    </tr>

    <?php if ($result): ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>
                <td><?= $row['employee_name'] ?></td>
                <td><?= $row['present_days'] ?></td>
            </tr>

        <?php } ?>

    <?php else: ?>

        <tr>
            <td colspan="2">No Data Found</td>
        </tr>

    <?php endif; ?>

</table>