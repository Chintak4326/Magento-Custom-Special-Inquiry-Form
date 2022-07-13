<?php

namespace ChintakExtensions\Special\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_special;
    protected $scopeConfig;
    protected $storeManager;
    public $messageManager;
    protected $request;
    protected $transportBuilder;

    public function __construct(
      Context $context,
      Request $request,
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Magento\Store\Model\StoreManagerInterface $storeManager,
      \Magento\Framework\Message\ManagerInterface $messageManager,
      \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
      \ChintakExtensions\Special\Model\SpecialFactory $special,
      \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
  ) {
        $this->_special =  $special;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->messageManager = $messageManager;
         parent::__construct($context);
    }
    public function execute()
    {
        // Get Store URL with Store Code
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeurl = $storeManager->getStore()->getBaseUrl();

        $specialpageurl = $storeurl . 'wholesale';

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $sendEmailTo =  $this->scopeConfig->getValue('special/email/special_recipient_email', $storeScope);
        $ccEmailTo =  $this->scopeConfig->getValue('special/email/copy_to', $storeScope);
        $emailSender =  $this->scopeConfig->getValue('special/email/special_sender_email_identity', $storeScope);

        if($emailSender=='sales')
        {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_sales/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_sales/email', $storeScope);

        }
        elseif ($emailSender=='general') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        }
        elseif ($emailSender=='support') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_support/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_support/email', $storeScope);
        }

        elseif ($emailSender=='custom1') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_custom1/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_custom1/email', $storeScope);
        }
        else
        {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope); 
        }

        $post = $this->getRequest()->getPostValue();
        $this->inlineTranslation->suspend();
        try{
            $recipientMail = $this->getRequest()->getPostValue('email');
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);
            $error = false;
            $sender = array('name' => $adminSenderName,'email' => $adminSenderEmail);
            $data=$this->getRequest()->getParams(); 
               $emailTemplateVariables = array(
                 'fname' => $data['fname']
            );

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('special_email_template_front') // this code we have mentioned in the email_templates.xml
                    ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($emailTemplateVariables)
                ->setFrom($sender)
                ->addTo($recipientMail)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
      
            $this->_redirect($specialpageurl);
            return;
        }
        catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.' . $e->getMessage())
            );
            $this->_redirect($specialpageurl);
            return;
        }
        finally{
            $data = $this->getRequest()->getParams();

            // For Mobile with Country Code
            $countrycode = $data['country_code'];
            $mobile = $data['mobile'];
            $mobilewithcode = '+' . $countrycode." ".$mobile;

            // Load Model
            $model = $this->_special->create();

            if(empty($data['fname']) || empty($data['email'])  || empty($data['mobile'])  || empty($data['subject']) || empty($data['message']))
                {
                    $this->messageManager->addErrorMessage(__('Someone feild was missing'));
                    $this->_redirect($specialpageurl);
                    return;
                }
            else{
                $emailTemplateVariables = array(
                    'fname' => $data['fname'],
                    'email'=>$data['email'],
                    'mobile'=>$mobilewithcode,
                    'subject'=>$data['subject'],
                    'message'=>$data['message']
                );
                $to = $sendEmailTo;
                $sender = array('name' => $adminSenderName,'email' => $adminSenderEmail);
                $transport = $this->transportBuilder->setTemplateIdentifier('special_email_template_admin')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($emailTemplateVariables)
                ->setFrom($sender)
                ->addTo($to);
                if($ccEmailTo != "")
                {
                    $transport->addCc($ccEmailTo);
                }
                $transport->setReplyTo($adminSenderEmail)
                ->getTransport()
                ->sendMessage();
                $this->inlineTranslation->resume();
                $model->setFName($data['fname'])
                        ->setEmail($data['email'])
                        ->setMobile($mobilewithcode)
                        ->setSubject($data['subject'])
                        ->setMessage($data['message']);
                if($model->save()){
                    $this->messageManager->addSuccess(__('Your form of special request has been submitted successfully.')
                    );
                }
                else{
                    $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                }
            }
        }
    }
}
?>



