<h1>Status</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Name</th>
			<th colspan="2">Status</th>
        </tr>
        <tr>
			<th style="width: 130px;">No</th>
            <th style="width: 130px;">Yes</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                Stock Daily
                <div class="explain">
                    Once a day, 4:22, update all stocks day for previous day
                </div>
            </td>
            <td colspan="2" class="text-center"><?php echo number_format($company_daily_update_waiting_count); ?></td>
        </tr>
        <tr>
            <td>
                Matrix Period
                <div class="explain">
                    Companies during the time frame (start_date, end_date) generate their matrix size
                </div>
            </td>
            <td class="text-right"><?php echo number_format($matrix_not_processed_count); ?></td>
			<td class="text-right"><?php echo number_format($matrix_processed_count); ?></td>
        </tr>
        <tr>
            <td>
                Matrix Match
                <div class="explain">
                    During a matrix perioud, check check company most similar items
                </div>
            </td>
            <td class="text-right"><?php echo number_format($matrix_match_not_processed_count); ?></td>
			<td class="text-right"><?php echo number_format($matrix_match_processed_count); ?></td>
        </tr>
    </tbody>
</table>
