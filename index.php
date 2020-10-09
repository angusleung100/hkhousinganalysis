<?php 
    $pageTitle = "HK Housing Analysis";
?>

<?php include("header.php"); ?>

<h3>Price Averages</h3>
<table>
    <thead>
        <tr>
            <th>Region</th>
            <th>Price per sq ft (HKD)</th>
            <th>Change from previous year (%)</th>
        </tr>
    </thead>
    <tbody>

    <?php
        //Calculate all territories average
        
        $hkiAverage = 0;
        $kowAverage = 0;
        $ntAverage = 0;

        $hkiPreviousAverage = 0;
        $kowPreviousAverage = 0;
        $ntPreviousAverage = 0;

    ?>
    <?php 

        //Get housing price data HONG KONG ISLAND

        $getHKIPricesDataQuery = mysqli_query($connection, "SELECT * FROM `rv_price_by_class_data_hki` ORDER BY `year` DESC;");

        $getHKIPricesData = mysqli_fetch_all($getHKIPricesDataQuery);

        //var_dump($getHKIPricesData);

        $average = 0;

        for($i=0; $i < 6; $i++)
        {
            $average += $getHKIPricesData[0][$i];
        }
        $average = $average / 5;

        $previousYearAverage = 0;

        for($i=0; $i < 6; $i++)
        {
            $previousYearAverage += $getHKIPricesData[1][$i];
        }
        $previousYearAverage = $previousYearAverage / 5;

        $deltaChange = $average - $previousYearAverage;

        $symbol = "";

        if($deltaChange >= 0)
        {
            $symbol = "+";
        }

        $hkiAverage = $average;
        $hkiPreviousAverage = $previousYearAverage;
    ?>
    <tr>
        <td>Hong Kong</td>
        <td>$<?php echo $average; ?></td>
        <td><?php echo $symbol . round($deltaChange / $previousYearAverage, 2) . "% ($" . $deltaChange . ")"; ?></td>
    </tr>

    
    <?php 

        //Get housing price data KOWLOON

        $getKOWPricesDataQuery = mysqli_query($connection, "SELECT * FROM `rv_price_by_class_data_kow` ORDER BY `year` DESC;");

        $getKOWPricesData = mysqli_fetch_all($getKOWPricesDataQuery);

        //var_dump($getKOWPricesData);

        $average = 0;

        for($i=0; $i < 6; $i++)
        {
            $average += $getKOWPricesData[0][$i];
        }
        $average = $average / 5;

        $previousYearAverage = 0;

        for($i=0; $i < 6; $i++)
        {
            $previousYearAverage += $getKOWPricesData[1][$i];
        }
        $previousYearAverage = $previousYearAverage / 5;

        $deltaChange = $average - $previousYearAverage;

        $symbol = "";

        if($deltaChange >= 0)
        {
            $symbol = "+";
        }

        $kowAverage = $average;
        $kowPreviousAverage = $previousYearAverage;
    ?>
    <tr>
        <td>Kowloon</td>
        <td>$<?php echo $average; ?></td>
        <td><?php echo $symbol . round($deltaChange / $previousYearAverage, 2) . "% ($" . $deltaChange . ")"; ?></td>
    </tr>

    
    <?php 

        //Get housing price data NEW TERRITORIES

        $getntPricesDataQuery = mysqli_query($connection, "SELECT * FROM `rv_price_by_class_data_nt` ORDER BY `year` DESC;");

        $getntPricesData = mysqli_fetch_all($getntPricesDataQuery);

        //var_dump($getHKIPricesData);

        $average = 0;

        for($i=0; $i < 6; $i++)
        {
            $average += $getntPricesData[0][$i];
        }
        $average = $average / 5;

        $previousYearAverage = 0;

        for($i=0; $i < 6; $i++)
        {
            $previousYearAverage += $getntPricesData[1][$i];
        }
        $previousYearAverage = $previousYearAverage / 5;

        $deltaChange = $average - $previousYearAverage;

        $symbol = "";

        if($deltaChange >= 0)
        {
            $symbol = "+";
        }

        $ntAverage = $average;
        $ntPreviousAverage = $previousYearAverage;
    ?>
    <tr>
        <td>New Territories</td>
        <td>$<?php echo $average; ?></td>
        <td><?php echo $symbol . round($deltaChange / $previousYearAverage, 2) . "% ($" . $deltaChange . ")"; ?></td>
    </tr>

    
    <?php 

        //Get housing price data ALL

        $average = $hkiAverage + $kowAverage + $ntAverage;
        $average = $average / 5;

        $previousYearAverage = $hkiPreviousAverage + $kowPreviousAverage + $ntPreviousAverage;

        $previousYearAverage = $previousYearAverage / 5;

        $deltaChange = $average - $previousYearAverage;

        $symbol = "";

        if($deltaChange >= 0)
        {
            $symbol = "+";
        }

    ?>
    <tr>
        <td><b>All Territories</b></td>
        <td>$<?php echo $average; ?></td>
        <td><?php echo $symbol . round($deltaChange / $previousYearAverage, 2) . "% ($" . $deltaChange . ")"; ?></td>
    </tr>
    </tbody>
</table>


<h3>Housing Indices</h3>
<table>
    <thead>
        <tr>
            <th>Grade</th>
            <th>Index (Pts)</th>
            <th>Change from previous year (%)</th>
        </tr>
    </thead>
    <tbody>

    <?php
        //Get housing index data
        $getPricingIndexDataQuery = mysqli_query($connection, "SELECT * FROM `rv_price_index_by_class_data` ORDER BY `year` DESC;");

        $getPricingIndexData = mysqli_fetch_all($getPricingIndexDataQuery);
    ?>
    <tr>
        <td>A</td>
        <td><?php echo round($getPricingIndexData[0][1], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getPricingIndexData[0][1] - $getPricingIndexData[1][1]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][1]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>B</td>
        <td><?php echo round($getPricingIndexData[0][2], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getPricingIndexData[0][2] - $getPricingIndexData[1][2]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][2]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>C</td>
        <td><?php echo round($getPricingIndexData[0][3], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getPricingIndexData[0][3] - $getPricingIndexData[1][3]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][3]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>D</td>
        <td><?php echo round($getPricingIndexData[0][4], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getPricingIndexData[0][4] - $getPricingIndexData[1][4]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][4]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>E</td>
        <td><?php echo round($getPricingIndexData[0][5], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getPricingIndexData[0][5] - $getPricingIndexData[1][5]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][5]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td><b>All Grades</b></td>
        <td><b><?php echo round($getPricingIndexData[0][6], 2) ?></b></td>
        <td><b>
            <?php

                $deltaChange = ($getPricingIndexData[0][6] - $getPricingIndexData[1][6]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getPricingIndexData[0][6]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </b></td>
    </tr>
    </tbody>
</table>

<h3>Renting Indices</h3>
<table>
    <thead>
        <tr>
            <th>Grade</th>
            <th>Index (Pts)</th>
            <th>Change from previous year (%)</th>
        </tr>
    </thead>
    <tbody>

    <?php
        //Get renting index data
        $getRentingIndexDataQuery = mysqli_query($connection, "SELECT * FROM `rv_rent_index_by_class_data` ORDER BY `year` DESC;");

        $getRentingIndexData = mysqli_fetch_all($getRentingIndexDataQuery);
    ?>
    <tr>
        <td>A</td>
        <td><?php echo round($getRentingIndexData[0][1], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getRentingIndexData[0][1] - $getRentingIndexData[1][1]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][1]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>B</td>
        <td><?php echo round($getRentingIndexData[0][2], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getRentingIndexData[0][2] - $getRentingIndexData[1][2]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][2]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>C</td>
        <td><?php echo round($getRentingIndexData[0][3], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getRentingIndexData[0][3] - $getRentingIndexData[1][3]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][3]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>D</td>
        <td><?php echo round($getRentingIndexData[0][4], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getRentingIndexData[0][4] - $getRentingIndexData[1][4]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][4]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td>E</td>
        <td><?php echo round($getRentingIndexData[0][5], 2) ?></td>
        <td>
            <?php

                $deltaChange = ($getRentingIndexData[0][5] - $getRentingIndexData[1][5]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][5]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </td>
    </tr>
    <tr>
        <td><b>All Grades</b></td>
        <td><b><?php echo round($getRentingIndexData[0][6], 2) ?></b></td>
        <td><b>
            <?php

                $deltaChange = ($getRentingIndexData[0][6] - $getRentingIndexData[1][6]);

                $symbol = "";

                if($deltaChange >= 0)
                {
                    $symbol = "+";
                }
                echo $symbol . round(($deltaChange / $getRentingIndexData[0][6]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
            ?>
        </b></td>
    </tr>
    </tbody>
</table>



<?php include("footer.php"); ?>