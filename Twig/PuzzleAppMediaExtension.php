<?php
namespace Puzzle\App\MediaBundle\Twig;

use GuzzleHttp\Exception\BadResponseException;
use Puzzle\ConnectBundle\ApiEvents;
use Puzzle\ConnectBundle\Event\ApiResponseEvent;
use Puzzle\ConnectBundle\Service\PuzzleAPIClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 *
 * @author AGNES Gnagne Cedric <cecenho55@gmail.com>
 *
 */
class PuzzleAppMediaExtension extends \Twig_Extension
{
    /**
     * @var PuzzleAPIClient $apiClient
     */
    protected $apiClient;
    
    /**
     * @var RequestStack $requestStack
     */
    protected $requestStack;
    
    /**
     * @var EventDispatcherInterface $dispatcher
     */
    protected $dispatcher;
    
    public function __construct(RequestStack $requestStack, EventDispatcherInterface $dispatcher, PuzzleAPIClient $apiClient) {
        $this->requestStack = $requestStack;
        $this->apiClient = $apiClient;
        $this->dispatcher = $dispatcher;
    }
    
    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('render_media_folders', [$this, 'getFolders'], ['needs_environment' => false, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('render_media_folder', [$this, 'getFolder'], ['needs_environment' => false, 'is_safe' => ['html']]),
        ];
    }
    
    public function getFolders($filter = null, $limit = null, $order = null, $page = null) {
        try {
            $query = [
                'filter' => $filter,
                'limit' => $limit,
                'orderBy' => $order,
                'page' => $page
            ];
            $categories = $this->apiClient->pull('/media/folders', $query);
        }catch (BadResponseException $e) {
            $this->dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $this->requestStack->getCurrentRequest()));
            $categories = [];
        }
        
        return $categories;
    }
    
    public function getFolder($id) {
        try {
            $category = $this->apiClient->pull('/media/folders/'.$id);
        }catch (BadResponseException $e) {
            $this->dispatcher->dispatch(ApiEvents::API_BAD_RESPONSE, new ApiResponseEvent($e, $this->requestStack->getCurrentRequest()));
            $category = [];
        }
        
        return $category;
    }
}
