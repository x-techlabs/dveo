<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Validator\Csrf;
use Zend\InputFilter\Factory;

class IndexController extends AbstractActionController
{
    var $_csrf;

    public function indexAction()
    {
       // return new ViewModel();
        return $this->getResponse();
    }

    public function saveAction()
    {

        $this->checkOrigin("POST, GET, OPTIONS");

        if (!$this->getRequest()->isPost())
        {
            return $this->dispatchError("Not a proper request");
        }

        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array(
            'token' => array(
                'name'       => 'token',
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    )
                ),
            ),
            'type' => array(
                'name'       => 'token',
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    )
                ),
            ),
            'image' => array(
                'name'       => 'token',
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    )
                ),
            ),
            /*'name' => array(
                'name'       => 'name',
                'required'   => false,
                'validators' => array(
                    array(
                        'name' => 'alnum',
                    )

                ),
            ),*/
        ));

        //return $this->dispatchError($this->params()->fromPost('image',null));

        $inputFilter->setData($this->params()->fromPost(null,null) );

        if (!$inputFilter->isValid()) {

            $missing = "";
            $notalnum = "";
            $message = "";

            foreach ($inputFilter->getMessages() as $key=>$value)
            {
                if (isset($value["isEmpty"])) $missing .= $key.",";
                if (isset($value["notAlnum"])) $notalnum .= $key.",";

            }

            if ($missing) $message.= "Following are required $missing \n";
            if ($notalnum) $message.= "Following are alpha numeric only $notalnum";

            return $this->dispatchError($message);
        }



        $csrf = new Csrf();

        if (!$csrf->isValid($this->params()->fromPost('token',null))) {
            return $this->dispatchError("Token Invalid");
        }

        $type = $this->params()->fromPost('type',null);
        $name = "public/snapshots/".($this->params()->fromPost('name',null) !=="" ? $this->params()->fromPost('name',null) . ".".$type : "snapshot_" . rand() . ".".$type);


        if ($this->params()->fromPost('image',null)) {
            $im = imagecreatefromstring(base64_decode($this->params()->fromPost('image',null)));

            if ($im) {
                file_put_contents($name, base64_decode($this->params()->fromPost('image',null)));
                $result = new JsonModel(array(
                    'success' => "Successfully Saved"
                ));

                return $result;
            } else {
                return $this->dispatchError("Image invalid");
            }

        }

        return $this->getResponse();
    }

    protected function dispatchError($message)
    {
        $result = new JsonModel(array(
            'error' => $message
        ));

        return $result;
    }

    private function checkOrigin($methods)
    {
        $config = $this->getServiceLocator()->get('Config');


        //ajax request
        //check if the origin matches and send the origin access header
        if ($this->getRequest()->getHeader("Origin")) {
            $origin = $this->getRequest()->getHeader("Origin")->getFieldValue();

            $response = $this->getResponse();
            $headers = $response->getHeaders();

            foreach($config["config"]["origin_hosts"] as $host) {
                if ($origin == $host) {
                    $headers->addHeaderLine('Access-Control-Allow-Origin',$host);
                    $headers->addHeaderLine('Access-Control-Allow-Methods',$methods);
                    $headers->addHeaderLine('Access-Control-Allow-Headers','Content-Type, Accept');
                    $headers->addHeaderLine('Access-Control-Allow-Credentials',"true");
                }
            }
        }
    }

    public function tokenAction()
    {
        $this->checkOrigin("POST");

        if (!$this->getRequest()->isPost())
        {
            return $this->dispatchError("Not a proper request");
        }

        $csrf = new Csrf();

        $result = new JsonModel(array(
            'token' => $csrf->getHash()
        ));

        return $result;
    }


}
