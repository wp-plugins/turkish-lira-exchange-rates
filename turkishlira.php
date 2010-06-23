<?php
/*
Plugin Name: Turkish Lira Exchange Rates
Plugin URI: http://www.iwasinturkey.com/turkish-lira-exchange-rates
Description: Display daily exchange rates from the Central Bank of Turkey (Türkiye Cumhuriyet Merkez Bankasi).
Author: Onur Kocatas
Version: 2.0
Author URI: http://www.iwasinturkey.com
*/

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'turkish_lira_load_widgets' );

/* Function that registers our widget. */
function turkish_lira_load_widgets() {
	register_widget( 'Turkish_Lira_Widget' );
}

class Turkish_Lira_Widget extends WP_Widget {
function Turkish_Lira_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Turkish_Lira', 'description' => 'Display daily exchange rates from the Central Bank of Turkey (Türkiye Cumhuriyet Merkez Bankasi).' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300,  'id_base' => 'turkish-lira-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'turkish-lira-widget', 'Turkish Lira Widget', $widget_ops, $control_ops );
	}
	
	
	function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
$buyLabel = $instance['buyLabel'];
$sellLabel = $instance['sellLabel'];
$updateLabel = $instance['updateLabel'];
$add_CSS = isset( $instance['add_CSS'] ) ? $instance['add_CSS'] : true;
$display_AUD = isset( $instance['display_AUD'] ) ? $instance['display_AUD'] : true;
$display_CAD = isset( $instance['display_CAD'] ) ? $instance['display_CAD'] : true;
$display_CHF = isset( $instance['display_CHF'] ) ? $instance['display_CHF'] : true;
$display_DKK = isset( $instance['display_DKK'] ) ? $instance['display_DKK'] : true;
$display_EUR = isset( $instance['display_EUR'] ) ? $instance['display_EUR'] : true;
$display_GBP = isset( $instance['display_GBP'] ) ? $instance['display_GBP'] : true;
$display_JPY = isset( $instance['display_JPY'] ) ? $instance['display_JPY'] : true;
$display_KWD = isset( $instance['display_KWD'] ) ? $instance['display_KWD'] : true;
$display_NOK = isset( $instance['display_NOK'] ) ? $instance['display_NOK'] : true;
$display_SAR = isset( $instance['display_SAR'] ) ? $instance['display_SAR'] : true;
$display_SEK = isset( $instance['display_SEK'] ) ? $instance['display_SEK'] : true;
$display_USD = isset( $instance['display_USD'] ) ? $instance['display_USD'] : true;
$display_date = isset( $instance['display_date'] ) ? $instance['display_date'] : true;
$display_credit = isset( $instance['display_credit'] ) ? $instance['display_credit'] : true;


		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		/*Css*/
		if ( $add_CSS )
			echo '
<style type="text/css">
#currency {margin:0 auto;}
#currency div.c_header {clear:both;height:17px;}
#currency div.c_row{font-size:1em;height:17px;padding:0;font-weight:normal;clear:both;}
#currency div.odd {background:#efefef;}
#currency div.c_row .c_symbol{float:left;}
#currency div.difi{background:#efefef;}
#currency div.c_row div.c_rate{width:50px;float:right;text-align:right;padding:0px 4px;}
#currency div.c_header div.c_rate{width:50px;float:right;text-align:center;font-weight:bold;padding:0px;}
#currency div.c_row .AUD{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/AUD.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .CAD{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/CAD.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .CHF{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/CHF.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .DKK{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/DKK.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .EUR{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/EUR.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .GBP{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/GBP.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .JPY{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/JPY.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .KWD{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/KWD.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .NOK{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/NOK.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .SAR{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/SAR.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .SEK{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/SEK.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_row .USD{background:transparent url(wp-content/plugins/turkish-lira-exchange-rates/USD.gif) no-repeat scroll 0 3px;padding-left:20px;}
#currency div.c_info{font-size:0.9em;color:#999;clear:both;text-align:center;font-weight:bold;}
#currency div.c_credit{font-size:0.9em;color:#999;clear:both;text-align:center;font-weight:bold;display:none;}
#currency div.c_credit a{color:#369;clear:both;text-align:center;font-weight:bold;}
</style>';
		$rates=fopen("http://www.tcmb.gov.tr/kurlar/today.html","r") or die ("Something is wrong");
		$AUD="AUD/TRY";
		$CAD="CAD/TRY";
		$CHF="CHF/TRY";
		$DKK="DKK/TRY";
		$EUR="EUR/TRY";
		$GBP="GBP/TRY";
		$JPY="JPY/TRY";
		$KWD="KWD/TRY";
		$NOK="NOK/TRY";
		$SAR="SAR/TRY";
		$SEK="SEK/TRY";
		$USD="USD/TRY";
		$DATE="Indicative Exchange Rates Announced at ";
		while (!feof($rates)) {
		$row= fgets($rates, 128);
		
		if ($findAUD=strpos($row,$AUD)===false){
		}else{
		$findAUD=strpos($row,$AUD);
		$AUDbuy=number_format(trim(substr($row,$findAUD+40,10)),4);
		$AUDsell=number_format(trim(substr($row,$findAUD+50,11)),4);
		}
		if ($findCAD=strpos($row,$CAD)===false){
		}else{
		$findCAD=strpos($row,$CAD);
		$CADbuy=number_format(trim(substr($row,$findCAD+40,10)),4);
		$CADsell=number_format(trim(substr($row,$findCAD+50,11)),4);
		}
		if ($findCHF=strpos($row,$CHF)===false){
		}else{
		$findCHF=strpos($row,$CHF);
		$CHFbuy=number_format(trim(substr($row,$findCHF+40,10)),4);
		$CHFsell=number_format(trim(substr($row,$findCHF+50,11)),4);
		}
		if ($findDKK=strpos($row,$DKK)===false){
		}else{
		$findDKK=strpos($row,$DKK);
		$DKKbuy=number_format(trim(substr($row,$findDKK+40,10)),4);
		$DKKsell=number_format(trim(substr($row,$findDKK+50,11)),4);
		}
		if ($findEUR=strpos($row,$EUR)===false){
		}else{
		$findEUR=strpos($row,$EUR);
		$EURbuy=number_format(trim(substr($row,$findEUR+40,10)),4);
		$EURsell=number_format(trim(substr($row,$findEUR+50,11)),4);
		}
		if ($findGBP=strpos($row,$GBP)===false){
		}else{
		$findGBP=strpos($row,$GBP);
		$GBPbuy=number_format(trim(substr($row,$findGBP+40,10)),4);
		$GBPsell=number_format(trim(substr($row,$findGBP+50,11)),4);
		}
		if ($findJPY=strpos($row,$JPY)===false){
		}else{
		$findJPY=strpos($row,$JPY);
		$JPYbuy=number_format(trim(substr($row,$findJPY+40,10)),4);
		$JPYsell=number_format(trim(substr($row,$findJPY+50,11)),4);
		}
		if ($findKWD=strpos($row,$KWD)===false){
		}else{
		$findKWD=strpos($row,$KWD);
		$KWDbuy=number_format(trim(substr($row,$findKWD+40,10)),4);
		$KWDsell=number_format(trim(substr($row,$findKWD+50,11)),4);
		}
		if ($findNOK=strpos($row,$NOK)===false){
		}else{
		$findNOK=strpos($row,$NOK);
		$NOKbuy=number_format(trim(substr($row,$findNOK+40,10)),4);
		$NOKsell=number_format(trim(substr($row,$findNOK+50,11)),4);
		}
		if ($findSAR=strpos($row,$SAR)===false){
		}else{
		$findSAR=strpos($row,$SAR);
		$SARbuy=number_format(trim(substr($row,$findSAR+40,10)),4);
		$SARsell=number_format(trim(substr($row,$findSAR+50,11)),4);
		}
		if ($findSEK=strpos($row,$SEK)===false){
		}else{
		$findSEK=strpos($row,$SEK);
		$SEKbuy=number_format(trim(substr($row,$findSEK+40,10)),4);
		$SEKsell=number_format(trim(substr($row,$findSEK+50,11)),4);
		}
		if ($findUSD=strpos($row,$USD)===false){
		}else{
		$findUSD=strpos($row,$USD);
		$USDbuy=number_format(trim(substr($row,$findUSD+40,10)),4);
		$USDsell=number_format(trim(substr($row,$findUSD+50,11)),4);
		}
		if ($findDATE=strpos($row,$DATE)===false){
		}else{
		$findDATE=strpos($row,$DATE);
		$ondegis = array(' on ' => ' - ');
		$DATEdisplay=strtr(trim(substr($row,$findDATE+39,+19)),$ondegis);
		}
		
		}
		fclose($rates);
		
		echo '<div id="currency"><div class="c_header"><div class="c_rate">';
		if ( $sellLabel )
			echo '' . $sellLabel . '';
		echo '</div><div class="c_rate">';
		if ( $buyLabel )
			echo '' . $buyLabel . '';
		echo '</div></div>';
		

		echo '<div class="c_row difi"><div class="c_symbol AUD">AUD</div><div class="c_rate">'.$AUDsell.'</div><div class="c_rate">'.$AUDbuy.'</div></div>';
	

		echo '<div class="c_row"><div class="c_symbol CAD">CAD</div><div class="c_rate">'.$CADsell.'</div><div class="c_rate">'.$CADbuy.'</div></div>';
	

		echo '<div class="c_row difi"><div class="c_symbol CHF">CHF</div><div class="c_rate">'.$CHFsell.'</div><div class="c_rate">'.$CHFbuy.'</div></div>';
	

		echo '<div class="c_row "><div class="c_symbol DKK">DKK</div><div class="c_rate">'.$DKKsell.'</div><div class="c_rate">'.$DKKbuy.'</div></div>';
	

		echo '<div class="c_row difi"><div class="c_symbol EUR">EUR</div><div class="c_rate">'.$EURsell.'</div><div class="c_rate">'.$EURbuy.'</div></div>';
	

		echo '<div class="c_row"><div class="c_symbol GBP">GBP</div><div class="c_rate">'.$GBPsell.'</div><div class="c_rate">'.$GBPbuy.'</div></div>';
	

		echo '<div class="c_row difi"><div class="c_symbol JPY">JPY</div><div class="c_rate">'.$JPYsell.'</div><div class="c_rate">'.$JPYbuy.'</div></div>';
	

		echo '<div class="c_row"><div class="c_symbol KWD">KWD</div><div class="c_rate">'.$KWDsell.'</div><div class="c_rate">'.$KWDbuy.'</div></div>';
	

		echo '<div class="c_row difi"><div class="c_symbol NOK">NOK</div><div class="c_rate">'.$NOKsell.'</div><div class="c_rate">'.$NOKbuy.'</div></div>';
	

		echo '<div class="c_row"><div class="c_symbol SAR">SAR</div><div class="c_rate">'.$SARsell.'</div><div class="c_rate">'.$SARbuy.'</div></div>';
	

		echo '<div class="c_row difi"><div class="c_symbol SEK">SEK</div><div class="c_rate">'.$SEKsell.'</div><div class="c_rate">'.$SEKbuy.'</div></div>';
	

		echo '<div class="c_row"><div class="c_symbol USD">USD</div><div class="c_rate">'.$USDsell.'</div><div class="c_rate">'.$USDbuy.'</div></div>';
	
	

	echo '<div class="c_info">';
	if ( $updateLabel )
			echo '' . $updateLabel . '';
	echo ''.$DATEdisplay.'</div>';

	echo '<div class="c_credit"><a href="http://www.iwasinturkey.com/turkish-lira-exchange-rates">Turkish Lira Exchange Rates</a></div></div>';
	
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	
		function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
$instance['title'] = strip_tags( $new_instance['title'] );
$instance['buyLabel'] = $new_instance['buyLabel'];
$instance['sellLabel'] = $new_instance['sellLabel'];
$instance['updateLabel'] = $new_instance['updateLabel'];
$instance['add_CSS'] = $new_instance['add_CSS'];
$instance['display_AUD'] = $new_instance['display_AUD'];
$instance['display_CAD'] = $new_instance['display_CAD'];
$instance['display_CHF'] = $new_instance['display_CHF'];
$instance['display_DKK'] = $new_instance['display_DKK'];
$instance['display_EUR'] = $new_instance['display_EUR'];
$instance['display_GBP'] = $new_instance['display_GBP'];
$instance['display_JPY'] = $new_instance['display_JPY'];
$instance['display_KWD'] = $new_instance['display_KWD'];
$instance['display_NOK'] = $new_instance['display_NOK'];
$instance['display_SAR'] = $new_instance['display_SAR'];
$instance['display_SEK'] = $new_instance['display_SEK'];
$instance['display_USD'] = $new_instance['display_USD'];
$instance['display_date'] = $new_instance['display_date'];
$instance['display_credit'] = $new_instance['display_credit'];

		return $instance;
	}
	
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Turkish Lira Exchange Rates', 'buyLabel' => 'BUY', 'sellLabel' => 'SELL', 'updateLabel' => 'Updated on ',  'add_CSS' => true,'display_AUD' => false ,'display_CAD' => true ,'display_CHF' => true ,'display_DKK' => true ,'display_EUR' => true ,'display_GBP' => true ,'display_JPY' => true ,'display_KWD' => true ,'display_NOK' => true ,'display_SAR' => true ,'display_SEK' => true ,'display_USD' => true ,'display_date' => true ,'display_credit' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'buyLabel' ); ?>">Label for BUY:</label>
<input id="<?php echo $this->get_field_id( 'buyLabel' ); ?>" name="<?php echo $this->get_field_name( 'buyLabel' ); ?>" value="<?php echo $instance['buyLabel']; ?>" style="width:100%;" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'sellLabel' ); ?>">Label for SELL:</label>
<input id="<?php echo $this->get_field_id( 'sellLabel' ); ?>" name="<?php echo $this->get_field_name( 'sellLabel' ); ?>" value="<?php echo $instance['sellLabel']; ?>" style="width:100%;" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'updateLabel' ); ?>">Label for Update on:</label>
<input id="<?php echo $this->get_field_id( 'updateLabel' ); ?>" name="<?php echo $this->get_field_name( 'updateLabel' ); ?>" value="<?php echo $instance['updateLabel']; ?>" style="width:100%;" />
</p>
<?php
	}
}
?>