<?php

class Search extends modProcessor
{
    public $maxResults = 5;

    /**
     * @inherit
     * @return mixed|string
     */
    public function process()
    {
        $output = array();
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $output['resources'] = $this->searchResources($query);
//            $output['snippets'] = $this->searchResources($query);
//            $output['chunks'] = $this->searchResources($query);
//            $output['templates'] = $this->searchResources($query);
        }

        return $this->outputArray($output);
    }

    /**
     * Perform search in resources
     *
     * @param string $query The string being searched
     *
     * @return array The results
     */
    public function searchResources($query)
    {
        $output = array();
        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            'pagetitle:LIKE' => '%' . $query .'%',
            'OR:longtitle:LIKE' => '%' . $query .'%',
            'OR:alias:LIKE' => '%' . $query .'%',
            'OR:description:LIKE' => '%' . $query .'%',
            'OR:introtext:LIKE' => '%' . $query .'%',
        ));
        $c->sortby('createdon', 'DESC');

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modResource', $c);
        /** @var modResource $record */
        foreach ($collection as $record) {
            $output[] = array(
                'name' => $record->get('pagetitle'),
                'action' => 'resource/update&id=' . $record->get('id'),
            );
        }

        return $output;
    }
}

return 'Search';
