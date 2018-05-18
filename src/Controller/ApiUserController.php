<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use GuzzleHttp\Client;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/api",name="Api_controller_")
 */
class ApiUserController  extends Controller
{
    /**
     * @Route("/", name="index_api")
     */
    public function index(Request $request,SessionInterface $session)
    {
    	$client = new Client(['base_uri'=>"http://127.0.0.1:8000/"]);

		$response = $client->request('POST', '/api/login_check',
		["json"=>['security'=>['credentials'=>['username'=>'jason','password'=>'jason']]]]);
 		$token = json_decode($response->getBody()->read(1024))->token; 		

		$session->set('token',$token);

        return $this->redirect($this->generateUrl('Api_controller_info_api'));
    
    }
    
 	/**
     * @Route("/info", name="info_api")
     */
    public function info(Request $request,SessionInterface $session)
    {

		$token = $session->get('token');
		$client = new Client(['base_uri'=>"http://127.0.0.1:8000/"]);
        echo $token;
        echo "\n";
        $response = $client->request('GET', '/api/',['headers'=>['Authorization'=>'Bearer '.$token]]);
		$res = json_decode($response->getBody()->read(1024)); 		

        return new JsonResponse(['info' => $res]);
    }


}