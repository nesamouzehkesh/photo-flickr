<?php

namespace FlickrBundle\Library\NesaFlickrApi;

/**
 * Description of NesaFlickrPhotoRepository
 *
 * @author nesa
 */
class NesaFlickrPhotoRepository
{
    /**
     * Builds an array of Ideato\FlickrApi\Model\Photo object based on the SimpleXMLElement given
     *
     * @param \SimpleXMLElement $xml
     * @return array of Ideato\FlickrApi\Model\Photo object
     */
    public function getPhotosFromXml(\SimpleXMLElement $xml)
    {
        $photos = array();
        $sizes = array('t', 's', 'm', 'l');
        foreach ($xml->photo as $photo)
        {
            // Get all attributes of a sigle xml element
            $attributes = $photo->attributes();
            
            // Get all image url of different sizes
            $urls = array();
            foreach ($sizes as $size) {
                if (isset($attributes['url_' . $size])) {
                    $urls[$size] = (string) $attributes['url_' . $size];
                }
            }
            
            $photos[] = array(
                'id'    => (string) $attributes['id'],
                'owner' => (string) $attributes['owner'],
                'title' => (string) $attributes['title'],
                'urls'  => $urls
            );
        }

        return $photos;
    }
    
    /**
     * 
     * @param \SimpleXMLElement $xml
     * @return type
     */
    public function getPhotoFromXml(\SimpleXMLElement $xml)
    {
        // Get all attributes of a sigle xml element
        $owner = $xml->owner->attributes();

        $photo = array(
            'owner' => array(
                'id' => (string) $owner['nsid'],
                'name' => (string) $owner['username']
            ),
            'title' => (string) $xml->title,
            'description' => (string) $xml->description,
        );
        
        return $photo;
    }
}
