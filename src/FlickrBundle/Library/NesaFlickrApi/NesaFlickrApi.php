<?php

namespace FlickrBundle\Library\NesaFlickrApi;

use Ideato\FlickrApi\Wrapper\FlickrApi;

/**
 * Description of NesaFlickrApi
 *
 * @author nesa
 */
class NesaFlickrApi extends FlickrApi
{
    /**
     * @see http://www.flickr.com/services/api/
     *
     * @param \Ideato\FlickrApi\Wrapper\Curl $curl
     * @param string $url
     * @param string $user_id
     * @param string $api_key
     */    
    public function __construct(\Ideato\FlickrApi\Wrapper\Curl $curl, $url, $user_id, $api_key)
    {
        parent::__construct($curl, $url, $user_id, $api_key);
    }
    
    /**
     * Builds the basic url for any method of the flickr api
     *
     * @param string $method
     * @return string
     */
    protected function buildBaseUrl($method, $extra_parameters = '')
    {
        return $this->url.'method='.$method.'&api_key='.$this->api_key.$extra_parameters;
    }    
    
    /**
     * Calls the flickr.photos.search api method with the given limit and return the photo xml data
     * 
     * @param type $tag
     * @param type $limit
     * @return type
     */
    public function searchPhotos($tag, $limit = 50)
    {
        $url = $this->buildBaseUrl('flickr.photos.search', '&per_page='.$limit.'&tags='.$tag.'&extras=path_alias,url_t,url_s,url_m,url_l');
        $results = $this->curl->get($url);
        $xml = \simplexml_load_string($results);

        if (!$xml || count($xml->photos->photo) <= 0) {
            return null;
        }

        return $xml->photos;
    }    
    
    /**
     * Calls the flickr.photos.getInfo
     * 
     * @param type $id
     * @return type
     */
    public function getPhoto($id)
    {
        $url = $this->buildBaseUrl('flickr.photos.getInfo', '&photo_id='.$id);
        $results = $this->curl->get($url);
        $xml = \simplexml_load_string($results);

        if (!$xml) {
            return null;
        }

        return $xml->photo;
    }     
}
