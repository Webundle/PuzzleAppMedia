<?php
namespace Puzzle\Admin\MediaBundle\Controller;

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
class FolderController extends Controller
{
	/***
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function listAction(Request $request) {
        try {
            /** @var Puzzle\ConectBundle\Service\PuzzleAPIClient $apiClient */
            $apiClient = $this->get('puzzle_connect.api_client');
            $folders = $apiClient->pull('/media/folders', $request->query->all());
        }catch (BadResponseException $e) {
            /** @var Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $request));
            $folders = [];
        }
       
        return $this->render($this->getParameter('app_media.templates')['folder']['list'],[
		    'folders' => $folders
		]);
	}
	
    
    /***
     * Show Folder
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, $id) {
        try {
            /** @var Puzzle\ConectBundle\Service\PuzzleAPIClient $apiClient */
            $apiClient = $this->get('puzzle_connect.api_client');
            $folder = $apiClient->pull('/media/folders/'.$id);
            
            if (isset($folder['files']) && count($folder['files']) > 0){
                $criteria = [];
                $criteria['filter'] = 'id=:'.implode(';', $folder['files']);
                
                $files = $apiClient->pull('/media/files', $criteria);
            }else {
                $files = null;
            }
            
            $parent = null;
            if (isset($folder['_embedded'])) {
                $parent = $folder['_embedded']['parent'] ?? null;
            }
        }catch (BadResponseException $e) {
            /** @var EventDispatcher $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $request));
            $folder = $files = $parent = [];
        }
        
        return $this->render($this->getParameter('app_media.templates')['folder']['show'], array(
            'folder' => $folder,
            'files' => $files,
            'parent' => $parent
        ));
    }
}
