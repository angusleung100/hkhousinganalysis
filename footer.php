              
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
                        <div class="section">
                            <h1>100</h1>
                            <h5>+10%</h5>
                            <h4>Units Sold (YTD)</h4>
                            
                        </div>
                        <div class="section">
                            <h1>100</h1>
                            <h5>+10%</h5>
                            <h4>Housing Index (YTD)</h4>
                            
                        </div>
                        <div class="section">
                            <h1>100</h1>
                            <h5>+10%</h5>
                            <h4>Rental Index (YTD)</h4>
                            
                        </div>
                       
                    </div>
                    <div class="card">
                        <div class="section">
                            <small><b>Disclaimer:</b> </small>
                        </div>
                        <div class="section">
                            <small>
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