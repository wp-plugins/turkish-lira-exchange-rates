		$tclink = 'http://www.tcmb.gov.tr/kurlar/'.date('Ym', $currenycDate).'/'.date('dmY', $currenycDate).'.xml';
		$rates=fopen($tclink,"r");
		if ($rates == false) {
			//date for all dates
			$currenycDate = mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y"));
			$data = array(
			   'date' => date('Y-m-j', $currenycDate)
			);

			$this->db->insert($this->exchangerates_table, $data);
		} else {
			fclose($rates);
			//date for all dates
			$currenycDate = mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y"));
			//update the table if needed

			$xml = simplexml_load_file($tclink);
			foreach($xml->Currency as $Currency) {
				$currency = $Currency->{'CurrencyName'};	
				switch ($currency) {
					case 'US DOLLAR':
					$USD = substr(trim($Currency->{'ForexBuying'}),0,4);
					$USD = number_format($USD,2);
					break;
					case 'EURO':
					$EUR = substr(trim($Currency->{'ForexBuying'}),0,4);
					$EUR = number_format($EUR,2);
					break;
				}
			}
			$data = array(
			   'date' => date('Y-m-j', $currenycDate),
			   'eur' => $EUR,
			   'usd' => $USD,
			   'url' => $tclink
			);
			$this->db->insert($this->exchangerates_table, $data);
		}
	}