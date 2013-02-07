<html>
<title>ddd</title>
<style type="text/css">
ul.products li {
    width: 200px;
    margin: 0 10px 10px 0;
    padding: 15px;
    border: 0px solid red;
    display: inline-block;
    vertical-align: top;
    *display: inline;
    *zoom: 1;
}

ul.products li img {
max-width:180px;
height:auto;
}

.break-word {
  	word-wrap: break-word;
}
</style>
<body>


<?php

error_reporting(0);
require_once('Prosperent_Api.php'); 

    $prosperentApi = new Prosperent_Api(array( 
        'api_key' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 
        'query'   => 'dress',
        //if you want to use pagination, use the following arguments 
        'page'                  => 2, 
        'limit'                 => 12, 
        
        //if you want to enable facets, that must be done by 
        //setting the enableFacets option 
        'enableFacets'          => true, 

		 //if you want to enable querySuggestion, that must be done by 
        //setting the enableQuerySuggestion option 
        'enableQuerySuggestion' => true, 

    )); 
    
    /* 
     * it is recommended that you log impressions separately from your 
     * search requests, this way you can cache API results, but still 
     * log accurate impression data. Note: errors and warnings can 
     * still occur during a log call, so it is wise to log them 
     */ 
    $prosperentApi->log(); 

    /* 
     * since we have called the log method, we can now access a 
     * response array 
     * 
     * See the Stats -> log endpoint documentation for more details 
     * 
     * We now know whether this visitor is a search engine bot or 
     * not, and we also know what country the visitor is from if 
     * it is not a bot 
     */ 
    $logData = $prosperentApi->getJsonResponse(); 
    $country = $logData['countryCode']; 
    if (true == ($isBot = $logData['data'][0]['isBot'])) 
    { 
        $botName = $logData['data'][0]['botType']; 
    } 
    
     /* 
     * log warnings or errors 
     */ 
    if ($prosperentApi->hasErrors() || $prosperentApi->hasWarnings()) 
    { 
        /* 
         * you can retrieve the errors or warnings using: 
         * $prosperentApi->getErrors() or $prosperentApi->getWarnings() 
         */ 
    } 
    
    /* 
     * if there was a query suggestion, you may want to display 
     * that to the visitor 
     */ 
    if (null != ($querySuggestion = $prosperentApi->getQuerySuggestion())) 
    { 
        //echo $querySuggestion; 
    } 


    //fetch the result 
    $prosperentApi->fetch(); 
    
    define('RECORDSPERPAGE', 10);
    //use getTotalRecordsFound to process pagination
    $_totalRecordsFound = $prosperentApi->getTotalRecordsFound();
    print ("<br> Total Records: " . $_totalRecordsFound . " <hr>");
    
    //Total Pages to be spanned
    $_totalPages = 0;
    if($_totalRecordsFound > 0 && !empty($_totalRecordsFound)) {
      $_totalPages = ceil($_totalRecordsFound/RECORDSPERPAGE); 
    }
    
    print("Total Pages = " .  $_totalPages . " <hr>" );
    
    
    //iterate through the data response 
    echo '<ul class="products">'; 
    $i=0;
    foreach ($prosperentApi->getData() as $row) 
    {
	   echo '<li>';
	   echo '<a href="buy.php?productid='. $row['productId'] . '" title="' . $row['keyword'] . '">';
	   echo '<img src="' . $row['image_url'] . '">';
	   echo '<h4>'. $row['keyword'] . '</h4>';
	   echo '</a>';	
	   echo '<p>';
	   //pricing info
	   echo ' Our Price: ';
	   if(!empty($row['currency']) && $row['currency'] == 'USD' )
	   {
		   echo "$"; //dollar symbol
	   }
	   echo $row['price'] ;
	   echo  ' <br />';
	   
	   //Brand info
	   echo ' <strong>Brand: ' . $row['brand'] . '</strong>';
	   echo '</p>';
	   echo '</li>';
		
		/*print("<pre>");
		print_r($row); 
		print("</pre>"); */
	}
	echo '</ul>'; 

/*
    //iterate through the data response 
    echo '<table border="1">'; 
    $i=0; 
    foreach ($prosperentApi->getData() as $row) 
    { 
         // 
         // if this is the first row, set the titles 
         // 
        if ($i++ == 0) 
        { 
            echo '<tr>'; 

            foreach (array_keys($row) as $th) 
            { 
                echo '<th>' . $th . '</th>'; 
            } 

            echo '</tr>'; 
        } 

        echo '<tr>'; 

        foreach ($row as $value) 
        { 
            echo '<td>' . $value . '</td>'; 
        } 

        echo '</tr>'; 
    } 
    echo '</table>'; 

*/

?>


</body>
</html>
