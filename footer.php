              
            </div>  
            <div class="col-sm col-md-3 col-lg-3">
                <div class="row">
                    <div class="card">
                        <div class="section">
                        <?php
                            $hsiDataQuery = mysqli_query($connection, "SELECT * FROM `hkma_capitalmarkets_data` ORDER BY `end_of_month` DESC;");
                            $hsiData = mysqli_fetch_all($hsiDataQuery, MYSQLI_ASSOC);

                            //HSI Delta Change
                            $hsiDeltaChange = $hsiData[0]['eq_mkt_hs_index'] - $hsiData[1]['eq_mkt_hs_index'];
                            $hsiDeltaChangePercent = ($hsiDeltaChange / $hsiData[1]['eq_mkt_hs_index']) * 100;

                            if($hsiDeltaChange < 0)
                            {
                                $hsiMonthlyChangeSymbol = "-";
                            }
                            else
                            {
                                $hsiMonthlyChangeSymbol = "+";
                            }
                        ?>
                            <h1 id="hsi-monthly-price"><?php echo "$" . $hsiData[0]['eq_mkt_hs_index']; ?></h1>
                            <h5 id="hsi-delta-change"><?php echo $hsiMonthlyChangeSymbol . round($hsiDeltaChangePercent, 2); ?>% (<?php echo "$" . $hsiDeltaChange;?>)</h5>
                            <h4>Monthly Hang Seng Index (HKD)</h4>
                            
                        </div>

                        <?php
                            $getUnitsSoldDataQuery = mysqli_query($connection, "SELECT * FROM `rv_units_sold_all_territories_data` ORDER BY `date` DESC;");
                            $getUnitsSoldData = mysqli_fetch_all($getUnitsSoldDataQuery);

                            $combinedUnitsSold = $getUnitsSoldData[0][1] + $getUnitsSoldData[0][3];

                            $combinedUnitsSoldPreviousMonth = $getUnitsSoldData[1][1] + $getUnitsSoldData[1][3];
                        ?>

                        <div class="section">
                            <h1><?php echo $combinedUnitsSold; ?></h1>
                            <h5>
                            <?php

                                $deltaChange = $combinedUnitsSold - $combinedUnitsSoldPreviousMonth;

                                $symbol = "";

                                if($deltaChange >= 0)
                                {
                                    $symbol = "+";
                                }
                                echo $symbol . $deltaChange;
                            ?>
                             units</h5>
                            <h4>Units Sold (vs prev month)</h4>
                            
                        </div>

                        <?php
                            //Get housing index data
                            $getPricingIndexDataQuery = mysqli_query($connection, "SELECT * FROM `rv_price_index_by_class_data` ORDER BY `year` DESC;");

                            $getPricingIndexData = mysqli_fetch_all($getPricingIndexDataQuery);
                        ?>
                        <div class="section">
                            <h1>
                            <?php echo round($getPricingIndexData[0][6], 2) ?>
                            </h1>
                            <h5>
                            <?php

                                $deltaChange = ($getPricingIndexData[0][6] - $getPricingIndexData[1][6]);

                                $symbol = "";

                                if($deltaChange >= 0)
                                {
                                    $symbol = "+";
                                }
                                echo $symbol . round(($deltaChange / $getPricingIndexData[0][6]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
                            ?>
                            </h5>
                            <h4>Housing Index (vs prev month)</h4>
                            
                        </div>

                        <?php
                            //Get renting index data
                            $getRentingIndexDataQuery = mysqli_query($connection, "SELECT * FROM `rv_rent_index_by_class_data` ORDER BY `year` DESC;");

                            $getRentingIndexData = mysqli_fetch_all($getRentingIndexDataQuery);
                        ?>
                        <div class="section">
                            <h1><?php echo round($getRentingIndexData[0][6], 2) ?></h1>
                            <h5>
                            <?php

                                $deltaChange = ($getRentingIndexData[0][6] - $getRentingIndexData[1][6]);

                                $symbol = "";

                                if($deltaChange >= 0)
                                {
                                    $symbol = "+";
                                }
                                echo $symbol . round(($deltaChange / $getRentingIndexData[0][6]) * 100, 2) . "% (" . round($deltaChange, 2) .  " pts)";
                            ?>
                            </h5>
                            <h4>Rental Index (vs prev month)</h4>
                            
                        </div>
                       
                    </div>
                    <div class="card">
                        <div class="section">
                            <small><b>Disclaimer:</b> </small>
                        </div>
                        <div class="section">
                            <small>
                                Data displayed and provided are either monthly or yearly data, not necessarily up-to-date for the current month. HK Housing Analysis will note what time interval is currently being displayed for data, as well as what date is the data shown for.
                                <br><br>
                                Data by third-party providers is not guaranteed to be fully accurate and should only be used as reference and not financial advice whatsoever.
                                Please refer to a real estate agent, financial advisor, or accredited person(s) on your current financial situation. HK Housing Analysis is not liable or responsible
                                for financial or legal damages resulting in the use of the data provided.
                                
                            </small>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    
    </body>


<footer>
  <p>Made by <a href="https://www.linkedin.com/in/angus-leung/">Angus Leung</a> | <a href="https://github.com/angusleung100">Github</a> | CM Data: <a href="https://apidocs.hkma.gov.hk/">HKMA</a> | Housing Data: <a href="https://www.rvd.gov.hk/en/property_market_statistics/index.html">RVD</a> | Salary Data: <a href="https://www.censtatd.gov.hk/hkstat/sub/sp210.jsp">CenStatd</a> | HK Data: <a href="https://data.gov.hk/en/">HK Gov</a></p>
</footer>
</html>