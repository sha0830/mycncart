<?php
class ControllerStartupWeiXin extends Controller {
	public function index() {
		$wechat_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		
		$this->load->helper('mobile');
		
		if(is_weixin()) {
			
			if (!$this->customer->isLogged()) {
				
				  if(isset($this->session->data['weixin_login_openid'])) {
					 
					   
					   if(isset($this->session->data['weixin_logout_status'])) {
						  
						   if($this->session->data['weixin_logout_status'] == 0) {
					   
								if ($this->customer->login_weixin($this->session->data['weixin_login_openid'])) {
									
									unset($this->session->data['guest']);
			
									// Default Shipping Address
									$this->load->model('account/address');
						
									if ($this->config->get('config_tax_customer') == 'payment') {
										$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
									}
						
									if ($this->config->get('config_tax_customer') == 'shipping') {
										$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
									}
						
									
								}else{
									
								}
							
						   }
						
					   } else {
							
							
					   
							if ($this->customer->login_weixin($this->session->data['weixin_login_openid'])) {
								
								unset($this->session->data['guest']);
		
								// Default Shipping Address
								$this->load->model('account/address');
					
								if ($this->config->get('config_tax_customer') == 'payment') {
									$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
								}
					
								if ($this->config->get('config_tax_customer') == 'shipping') {
									$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
								}
					
								$this->response->redirect($wechat_url);
							} else {
								
							}
							
						      
						   
					   }
						
						
				   } elseif (isset($this->request->get['code'])) {
					 
					  $appid = $this->config->get('wxpay_appid');
					  $appsecret = $this->config->get('wxpay_appsecret');
					  
					  $weixin_result = getWeiXinUserInfo($appid, $appsecret, $this->request->get['code']);
							  
					  if ($weixin_result['openid']) {	
						
						  $this->session->data['weixin_login_openid'] = $weixin_result['openid'];
						  
						  $this->session->data['weixin_openid'] = $weixin_result['openid'];
					  
					  } else {
						  
						  $this->session->data['weixin_login_openid'] = '';
					  
						  $this->session->data['weixin_openid'] = '';
					  }
					  
					  header('Location: '.$wechat_url);
					  
				  } else {
					  $appid = $this->config->get('wxpay_appid');
					  header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$wechat_url.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect');
					  
				  }
			  
			}
		
		}
		
		
	}

}
