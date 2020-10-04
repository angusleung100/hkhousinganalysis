<?php 
    $pageTitle = "HK Capital Markets";
?>
<?php include("header.php"); ?>
<h3>Hang Seng Index (HSI)</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Price (HKD)</th>
        </tr>
    </thead>
    <tbody>

    <?php
        //Get HSI data
        $getHSIDataQuery = mysqli_query($connection, "SELECT `end_of_month`,`eq_mkt_hs_index` FROM `hkma_capitalmarkets_data` ORDER BY `end_of_month` DESC;");

        while($entry = mysqli_fetch_assoc($getHSIDataQuery))
        {
            echo "<tr>";
            echo "<td>" . $entry['end_of_month'] . "</td>";
            echo "<td>$" . $entry['eq_mkt_hs_index'] . "</td>";
            echo "</tr>";
        }
    ?>
    
        
        
        

    
    </tbody>
</table>

<h3>10-year Government Bond Yield v.s. Savings Account Interest Rate (Annual)</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>10-Y Bond Yield %</th>
            <th>Savings Interest %</th>
        </tr>
    </thead>
    <tbody>

    <?php
        //Get HSI data
        $getHSIDataQuery = mysqli_query($connection, "SELECT `end_of_month`,`yield_govbond_10y`,`deposit_rate_saving` FROM `hkma_monetary_data` ORDER BY `end_of_month` DESC;");

        while($entry = mysqli_fetch_assoc($getHSIDataQuery))
        {
            echo "<tr>";
            echo "<td>" . $entry['end_of_month'] . "</td>";
            echo "<td>" . $entry['yield_govbond_10y'] . "%</td>";
            echo "<td>" . $entry['deposit_rate_saving'] . "%</td>";
            echo "</tr>";
        }
    ?>
    
    </tbody>
</table>

<small>Savings Account Interest Rate based on savings rates of major banks in Hong Kong SAR</small>
<?php include("footer.php"); ?>