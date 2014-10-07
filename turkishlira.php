<?php
/*
Plugin Name: Turkish Lira Exchange Rates
Plugin URI: http://lycie.com
Description: Display daily exchange rates from the Central Bank of Turkey (Türkiye Cumhuriyet Merkez Bankasi).
Author: Onur Kocatas
Version: 3.1
Author URI: http://lycie.com
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
#currency {margin:0 auto;background: #fff; color: #000;padding: 0 3px;}
#currency div.c_header {clear:both;height:17px;}
#currency div.c_row{font-size:1em;height:17px;padding:0;font-weight:normal;clear:both;overflow: hidden;}
#currency div.odd {background:#efefef;}
#currency div.c_row .c_symbol{float:left;padding-left:20px;background:transparent no-repeat scroll 0 3px;}
#currency div.difi{background:#efefef;}
#currency div.c_row div.c_rate{float: right;overflow: hidden;padding: 0 4px 0 0;text-align: right;width: 49px;}
#currency div.c_header div.c_rate{min-width:50px;float:right;text-align:center;font-weight:bold;padding:0px;}
#currency div.c_row .AUD{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/AUD.gif);}
#currency div.c_row .CAD{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/CAD.gif);}
#currency div.c_row .CHF{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/CHF.gif);}
#currency div.c_row .DKK{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/DKK.gif);}
#currency div.c_row .EUR{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/EUR.gif);}
#currency div.c_row .GBP{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/GBP.gif);}
#currency div.c_row .JPY{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/JPY.gif);}
#currency div.c_row .KWD{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/KWD.gif);}
#currency div.c_row .NOK{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/NOK.gif);}
#currency div.c_row .SAR{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/SAR.gif);}
#currency div.c_row .SEK{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/SEK.gif);}
#currency div.c_row .USD{background-image:url(wp-content/plugins/turkish-lira-exchange-rates/USD.gif);}
#currency div.c_credit{font-size:0.9em;color:#999;clear:both;text-align:center;font-weight:bold;display:none;}
#currency div.c_credit a{color:#369;clear:both;text-align:center;font-weight:bold;}
</style>';
		
$rates=fopen("http://www.tcmb.gov.tr/kurlar/today.xml","r") or die ("Something is wrong");
$AUD="AUSTRALIAN DOLLAR";
$CAD="CANADIAN DOLLAR";
$CHF="SWISS FRANK";
$DKK="DANISH KRONE";
$EUR="EURO";
$GBP="POUND STERLING";
$JPY="JAPENESE YEN";
$KWD="KUWAITI DINAR";
$NOK="NORWEGIAN KRONE";
$SAR="SAUDI RIYAL";
$SEK="SWEDISH KRONA";
$USD="US DOLLAR";

if ($rates == false) {

} else {


	$xml = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');

	foreach($xml->Currency as $Currency) {
		$currency = $Currency->{'CurrencyName'};	
		switch ($currency) {
			case $AUD:
			$AUDbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$AUDsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $CAD:
			$CADbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$CADsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $CHF:
			$CHFbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$CHFsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $EUR:
			$EURbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$EURsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $DKK:
			$DKKbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$DKKsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $GBP:
			$GBPbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$GBPsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $JPY:
			$JPYbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$JPYsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $KWD:
			$KWDbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$KWDsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $NOK:
			$NOKbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$NOKsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $SAR:
			$SARbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$SARsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $SEK:
			$SEKbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$SEKsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
			case $USD:
			$USDbuy = number_format(substr(trim($Currency->{'ForexBuying'}),0,6),4);
			$USDsell = number_format(substr(trim($Currency->{'ForexSelling'}),0,6),4);
			break;
		}
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
		$defaults = array( 'title' => 'Turkish Lira Exchange Rates', 'buyLabel' => 'BUY', 'sellLabel' => 'SELL', 'add_CSS' => true,'display_AUD' => false ,'display_CAD' => true ,'display_CHF' => true ,'display_DKK' => true ,'display_EUR' => true ,'display_GBP' => true ,'display_JPY' => true ,'display_KWD' => true ,'display_NOK' => true ,'display_SAR' => true ,'display_SEK' => true ,'display_USD' => true ,'display_date' => true ,'display_credit' => true );
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
		<?php
	}
}
?>