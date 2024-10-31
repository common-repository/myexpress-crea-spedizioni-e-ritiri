<?php 

class CLASS_myexpress_spedizioni_Routine {        

	public function httpPOST($params,$url,$azione) {
		$response = wp_remote_post( $url, array(
	    'method'      => 'POST',
	    'timeout'     => 20,
	    'redirection' => 5,
	    'httpversion' => '1.0',
	    'blocking'    => true,
	    'body'        => $params
	    )
		);
		if ( is_wp_error( $response ) ) {
		  echo 'errore';  
		} else {
			parse_str($response['body'],$RETURN);
			return $RETURN;	      
		}		
	}

	public function do_login($POST) {    								     	
		$url = "https://api.myexpress.it/wordpress/v1.0/login";			
		$POST = array('ACTION' => 'LOGIN', 'USER' => $_POST['email'], 'PASSWORD' => $_POST['password']); 			
		$RETURN = $this->httpPOST($POST,$url,'LOGIN');			

		if (!empty($RETURN['LOGIN'])) {					    	
			$_SESSION['MyexpressSpedizioni']['ID_CLIENTE']      = $RETURN['ID_CLIENTE'];				  	
			$_SESSION['MyexpressSpedizioni']['RAGIONE_SOCIALE'] = $RETURN['RAGIONE_SOCIALE'];				  	
			$_SESSION['MyexpressSpedizioni']['NOME']            = $RETURN['NOME'];				  	
			$_SESSION['MyexpressSpedizioni']['COGNOME']         = $RETURN['COGNOME'];				  					    	
			$_SESSION['MyexpressSpedizioni']['EMAIL']           = $RETURN['EMAIL'];				  					    	
			$_SESSION['MyexpressSpedizioni']['CREDITI']         = $RETURN['CREDITI'];				  	
			$_SESSION['MyexpressSpedizioni']['FATTURAZIONE']    = $RETURN['FATTURAZIONE'];				  	
			$_SESSION['MyexpressSpedizioni']['RDFST']           = $RETURN['RDFST'];				  	
			$_SESSION['MyexpressSpedizioni']['RDFCT']           = $RETURN['RDFCT'];				  	
			$_SESSION['MyexpressSpedizioni']['MyEXPRESS_TOKEN'] = $RETURN['TOKEN'];				  						  	
			$_SESSION['MyexpressSpedizioni']['LOGIN']           = 1;
			$this->updateOption('TOKEN',$RETURN['TOKEN']);

			$check_option_nominativo = $this->getOption('NOMINATIVO');
			if (empty($check_option_nominativo)) {
				$url = "https://api.myexpress.it/wordpress/v1.0/curl";			
				$POST = array('ACTION' => 'INDIRIZZO_RITIRO', 'TOKEN' => $RETURN['TOKEN']);
				$RETURN2 = $this->httpPOST($POST,$url,'INDIRIZZO_RITIRO');
				if (!empty($RETURN2)) {
					$this->updateOption('NOMINATIVO',$RETURN2['NOMINATIVO']);					  
					$this->updateOption('REFERENTE',$RETURN2['REFERENTE']);
					$this->updateOption('TELEFONO',$RETURN2['TELEFONO']);
					$this->updateOption('EMAIL',$RETURN['EMAIL']);
					$this->updateOption('INDIRIZZO',$RETURN2['INDIRIZZO']);
					$this->updateOption('CIVICO',$RETURN2['CIVICO']);
					$this->updateOption('CAP',$RETURN2['CAP']);
					$this->updateOption('CITTA',$RETURN2['CITTA']);
					$this->updateOption('PROVINCIA',$RETURN2['PROVINCIA']);
					$this->updateOption('NAZIONE',$RETURN2['NAZIONE']);
					$this->updateOption('NOTE',$RETURN2['NOTE']);
				}
			}				
	  } else {
			$_SESSION['MyexpressSpedizioni']['ID_CLIENTE']      = '';
			$_SESSION['MyexpressSpedizioni']['RAGIONE_SOCIALE'] = '';				  	
			$_SESSION['MyexpressSpedizioni']['NOME']            = '';				  	
			$_SESSION['MyexpressSpedizioni']['COGNOME']         = '';				  					    					    	
			$_SESSION['MyexpressSpedizioni']['EMAIL']           = '';				  					    	
			$_SESSION['MyexpressSpedizioni']['RDFST']           = 0;				  	
			$_SESSION['MyexpressSpedizioni']['RDFCT']           = 0;				  	
			$_SESSION['MyexpressSpedizioni']['MyEXPRESS_TOKEN'] = '';				  						  	
			$_SESSION['MyexpressSpedizioni']['LOGIN']           = 0;
			$check_token = $this->getOption('TOKEN');
			if (!empty($check_token)) {
				$this->deleteOption('TOKEN');
	    }				      
	  }					
	  return $RETURN['LOGIN'];				
	}        

	public function check_token() {    	    	      	
		$check_token = $this->getOption('TOKEN');
		if (!empty($check_token)) {
			$url = "https://api.myexpress.it/wordpress/v1.0/login";			
			$POST = array('ACTION' => 'AUTOLOGIN', 'TOKEN' => $check_token); 			
			$RETURN = $this->httpPOST($POST,$url,'AUTOLOGIN');			

			if (!empty($RETURN['LOGIN'])) {
				$_SESSION['MyexpressSpedizioni']['ID_CLIENTE']      = $RETURN['ID_CLIENTE'];				  	
				$_SESSION['MyexpressSpedizioni']['RAGIONE_SOCIALE'] = $RETURN['RAGIONE_SOCIALE'];				  	
				$_SESSION['MyexpressSpedizioni']['NOME']            = $RETURN['NOME'];				  	
				$_SESSION['MyexpressSpedizioni']['COGNOME']         = $RETURN['COGNOME'];				  					    	
				$_SESSION['MyexpressSpedizioni']['EMAIL']           = $RETURN['EMAIL'];				  					    	
				$_SESSION['MyexpressSpedizioni']['CREDITI']         = $RETURN['CREDITI'];				  	
				$_SESSION['MyexpressSpedizioni']['FATTURAZIONE']    = $RETURN['FATTURAZIONE'];				  	
				$_SESSION['MyexpressSpedizioni']['RDFST']           = $RETURN['RDFST'];				  	
				$_SESSION['MyexpressSpedizioni']['RDFCT']           = $RETURN['RDFCT'];				  	
				$_SESSION['MyexpressSpedizioni']['MyEXPRESS_TOKEN'] = $RETURN['TOKEN'];				  						  	
				$_SESSION['MyexpressSpedizioni']['LOGIN']           = 1;
			} else {
				$_SESSION['MyexpressSpedizioni']['ID_CLIENTE']      = '';
				$_SESSION['MyexpressSpedizioni']['RAGIONE_SOCIALE'] = '';				  	
				$_SESSION['MyexpressSpedizioni']['NOME']            = '';				  	
				$_SESSION['MyexpressSpedizioni']['COGNOME']         = '';				  					    					    	
				$_SESSION['MyexpressSpedizioni']['EMAIL']           = '';				  					    	
				$_SESSION['MyexpressSpedizioni']['CREDITI']         = 0;
				$_SESSION['MyexpressSpedizioni']['FATTURAZIONE']    = 0;				  	
				$_SESSION['MyexpressSpedizioni']['RDFST']           = 0;				  	
				$_SESSION['MyexpressSpedizioni']['RDFCT']           = 0;				  	
				$_SESSION['MyexpressSpedizioni']['MyEXPRESS_TOKEN'] = '';				  						  	
				$_SESSION['MyexpressSpedizioni']['LOGIN']           = 0;
				$check_token = $this->getOption('TOKEN');
				if (!empty($check_token)) {
					$this->deleteOption('TOKEN');
				}				      
			}					
			return $RETURN['LOGIN'];				
		} else {
		  return 0;
		}	    	
	}    

	public function do_logout() {
		$this->deleteOption('TOKEN');		
		unset($_SESSION['MyexpressSpedizioni']);	
	}		

	public function do_assistenza($POST) {    						
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";					     	
		$POST = array('ACTION' => 'ASSISTENZA', 'TOKEN' => $check_token, 'NOMINATIVO' => $_SESSION['MyexpressSpedizioni']['NOME'] . ' ' . $_SESSION['MyexpressSpedizioni']['COGNOME'], 'EMAIL' => $_SESSION['MyexpressSpedizioni']['EMAIL'], 'OGGETTO' => $_POST['OGGETTO'], 'MESSAGGIO' => $_POST['MESSAGGIO']); 			
		$RETURN = $this->httpPOST($POST,$url,'ASSISTENZA');									
		return $RETURN['RICHIESTA'];
	}        

	public function do_impostazioni_base($POST) {    								     	
		if (!empty($POST['NOMINATIVO_CONTRASSEGNO'])) {      
			$check_option_nominativo_contrassegno = $this->getOption('NOMINATIVO_CONTRASSEGNO');
			if (!empty($check_option_nominativo_contrassegno)) {
				$this->updateOption('NOMINATIVO_CONTRASSEGNO',$POST['NOMINATIVO_CONTRASSEGNO']);
			} else {
				$this->updateOption('NOMINATIVO_CONTRASSEGNO',$POST['NOMINATIVO_CONTRASSEGNO']);
			}	      
		}
		if (!empty($POST['IBAN_CONTRASSEGNO'])) {   
			$check_option_iban_contrassegno = $this->getOption('IBAN_CONTRASSEGNO');
			if (!empty($check_option_iban_contrassegno)) {
				$this->updateOption('IBAN_CONTRASSEGNO',$POST['IBAN_CONTRASSEGNO']);
			} else {
				$this->updateOption('IBAN_CONTRASSEGNO',$POST['IBAN_CONTRASSEGNO']);
			}	      
		}
		return 1;
	}    

	public function do_impostazioni_indirizzo_base($POST) {    
		$this->updateOption('NOMINATIVO',$POST['impostazioni_mittente']);
		$this->updateOption('REFERENTE',$POST['impostazioni_referente']);
		$this->updateOption('EMAIL',$POST['impostazioni_email']);
		$this->updateOption('TELEFONO',$POST['impostazioni_telefono']);
		$this->updateOption('INDIRIZZO',$POST['impostazioni_indirizzo']);
		$this->updateOption('CIVICO',$POST['impostazioni_civico']);
		$this->updateOption('CAP',$POST['impostazioni_cap']);
		$this->updateOption('CITTA',$POST['impostazioni_citta']);
		$this->updateOption('PROVINCIA',$POST['impostazioni_provincia']);
		$this->updateOption('NAZIONE',$POST['impostazioni_nazione']);
		$this->updateOption('NOTE',$POST['impostazioni_note']);
		return 1;
	}

	public function do_controllo_accessori_possibili($POST) {    						
	  $check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";					     	
		$POST = array('ACTION' => 'ACCESSORI_POSSIBILI', 'TOKEN' => $check_token, 'MITTENTE_NAZIONE' => $POST['MITTENTE_NAZIONE'], 'DESTINATARIO_NAZIONE' => $POST['DESTINATARIO_NAZIONE'], 'MITTENTE_CITTA' => $POST['MITTENTE_CITTA'], 'DESTINATARIO_CITTA' => $POST['DESTINATARIO_CITTA']); 			
		$RETURN = $this->httpPOST($POST,$url,'ACCESSORI_POSSIBILI');									
		return $RETURN;
	}        

	public function do_check_quantita_carrello() {    						
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";					     	
		$POST = array('ACTION' => 'CHECK_QUANTITA_CARRELLO', 'TOKEN' => $check_token); 			
		$RETURN = $this->httpPOST($POST,$url,'CHECK_QUANTITA_CARRELLO');	
		if (!empty($RETURN['RCST'])) { $_SESSION['MyexpressSpedizioni']['RCST'] = $RETURN['RCST'];	} 
		else { $_SESSION['MyexpressSpedizioni']['RCST'] = 0; }
		if (!empty($RETURN['RCCT'])) { $_SESSION['MyexpressSpedizioni']['RCCT'] = $RETURN['RCCT'];	} 
		else { $_SESSION['MyexpressSpedizioni']['RCCT'] = 0; }	  	
	}        


	public function do_get_carrello() {    			
		$check_token = $this->getOption('TOKEN');			
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";					     	
		$POST = array('ACTION' => 'GET_CARRELLO', 'TOKEN' => $check_token); 			
		$RETURN = $this->httpPOST($POST,$url,'GET_CARRELLO');									
		return $RETURN;
	}    

	public function do_elimina_spedizione($ID_SPEDIZIONE) {    						
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";					     	
		$POST = array('ACTION' => 'ELIMINA_SPEDIZIONE', 'TOKEN' => $check_token, 'ID_SPEDIZIONE' => $ID_SPEDIZIONE);
		$RETURN = $this->httpPOST($POST,$url,'ELIMINA_SPEDIZIONE');									
		return $RETURN;
	}          

	public function do_modifica_spedizione($ID_SPEDIZIONE) {  			
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";			
		$POST = array('ACTION' => 'MODIFICA_SPEDIZIONE', 'TOKEN' => $check_token, 'ID_SPEDIZIONE' => $ID_SPEDIZIONE);  
		$RETURN = CLASS_myexpress_spedizioni_Routine::httpPOST($POST,$url,'MODIFICA_SPEDIZIONE');									
		return $RETURN;	      			
	}      

	public function do_crea_reso($ID_SPEDIZIONE) {  			
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";			
		$POST = array('ACTION' => 'CREA_RESO', 'TOKEN' => $check_token, 'ID_SPEDIZIONE' => $ID_SPEDIZIONE);  
		$RETURN = CLASS_myexpress_spedizioni_Routine::httpPOST($POST,$url,'CREA_RESO');									
		return $RETURN;	      			
	}

	public function get_date_ritiro($ID_SPEDIZIONE) {
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";			
		$POST = array('ACTION' => 'GET_DATE_RITIRO', 'TOKEN' => $check_token, 'ID_SPEDIZIONE' => $ID_SPEDIZIONE);  
		$RETURN = CLASS_myexpress_spedizioni_Routine::httpPOST($POST,$url,'GET_DATE_RITIRO');									
		return $RETURN;	      			
	}      

	public function do_get_storico($tipo_estrazione,$data_spedizioni_from,$data_spedizioni_to, $paginazione = 0, $tracking, $ordine_woocommerce) {
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";
		$POST = array('ACTION' => 'GET_STORICO', 'TOKEN' => $check_token, 'TIPO_ESTRAZIONE' => $tipo_estrazione, 'DATA_SPEDIZIONI_FROM' => $data_spedizioni_from, 'DATA_SPEDIZIONI_TO' => $data_spedizioni_to, 'PAGINAZIONE' => $paginazione, 'TRACKING' => $tracking, 'ORDINE_WOOCOMMERCE' => $ordine_woocommerce);
		$RETURN = $this->httpPOST($POST,$url,'GET_STORICO');									
		return $RETURN;
	}    

	public function do_check_storico_ordine($ID_ORDINE) {
		$check_token = $this->getOption('TOKEN');
		$url = "https://api.myexpress.it/wordpress/v1.0/curl";			
		$POST = array('ACTION' => 'CHECK_STORICO_ORDINE', 'TOKEN' => $check_token, 'ORDINE_WOOCOMMERCE' => $ID_ORDINE);  
		$RETURN = CLASS_myexpress_spedizioni_Routine::httpPOST($POST,$url,'CHECK_STORICO_ORDINE');									
		return $RETURN['RISULTATO'];	      			
	}

}

?>