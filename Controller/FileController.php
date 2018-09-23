<?php

namespace Puzzle\App\MediaBundle\Controller;

use GuzzleHttp\Exception\BadResponseException;
use Puzzle\ConnectBundle\ApiEvents;
use Puzzle\ConnectBundle\Event\ApiResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @author AGNES Gnagne Cedric <cecenho55@gmail.com>
 * 
 */
class FileController extends Controller
{
	/***
	 * Show All Media
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request){
		$criteria = [];
		
		// Format criteria
		if ($request->query->get('search')) {
		    $criteria['filter'] = 'name=^'.$request->query->get('search');
		}
		
		if ($type = $request->query->get('type')) {
		    $criteria['filter'] = isset($criteria['filter']) && $criteria['filter'] != '' ? 
		                          $criteria['filter'].'&type=='.$type : 'type=='.$type;
		}
		
		try {
    		/** @var Puzzle\ConectBundle\Service\PuzzleAPIClient $apiClient */
    		$apiClient = $this->get('puzzle_connect.api_client');
    		$files = $apiClient->pull('/media/files', $criteria);
		}catch (BadResponseException $e) {
		    /** @var EventDispatcher $dispatcher */
		    $dispatcher = $this->get('event_dispatcher');
		    $dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $request));
		    $files = [];
		}
		
		return $this->render($this->getParameter('app_media.templates')['file']['list'],[
		    'files' => $files,
		    'type' => $request->query->get('type'),
		]);
	}
	
	/***
	 * Show File
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction(Request $request, $id) {
	    try {
	        /** @var Puzzle\ConectBundle\Service\PuzzleAPIClient $apiClient */
	        $apiClient = $this->get('puzzle_connect.api_client');
	        $file = $apiClient->pull('/media/files/'.$id);
	    }catch (BadResponseException $e) {
	        /** @var EventDispatcher $dispatcher */
	        $dispatcher = $this->get('event_dispatcher');
	        $dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $request));
	        $file = [];
	    }
	    
	    return $this->render($this->getParameter('app_media.templates')['file']['show'], array('file' => $file));
	}
}
