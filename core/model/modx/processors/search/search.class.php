<?php
/**
 * Class modSearchProcessor
 * Searches for elements (all but plugins) & resources
 *
 * @todo : take care of users rights (ie. view/edit elements/contexts/resources...)
 */
class modSearchProcessor extends modProcessor
{
    public $maxResults = 5;
    public $actionToken = ':';

    /**
     * @return string JSON formatted results
     */
    public function process()
    {
        $output = array();
        $query = $this->getProperty('query');
        if (!empty($query)) {
            if (strpos($query, ':') === 0) {
                // upcoming "launch actions"
                $output = $this->searchActions($query, $output);
            } else {
                // Search elements & resources
                $output = $this->searchResources($query, $output);
                $output = $this->searchTVs($query, $output);
                $output = $this->searchSnippets($query, $output);
                $output = $this->searchChunks($query, $output);
                $output = $this->searchTemplates($query, $output);
            }
        }

        return $this->outputArray($output);
    }

    public function searchActions($query, array $output)
    {
        // @todo
        //$query = ltrim($query, $this->actionToken);
        $output[] = array(
            'name' => 'Welcome',
            'action' => 'welcome',
            'description' => 'Go back home',
            'type' => 'Actions'
        );
//        $class = 'modMenu';
//        $c = $this->modx->newQuery($class);
//        $c->where(array(
//            'action:LIKE' => '%' . $query . '%',
//        ));
//        $c->limit($this->maxResults);
//
//        $collection = $this->modx->getCollection($class, $c);
//        /** @var modMenu $record */
//        foreach ($collection as $record) {
//            $output[] = array(
//                'name' => $record->get('text'),
//                'action' => $record->get('action'),
//                'description' => $record->get('description'),
//                'type' => 'Actions',
//            );
//        }
//
        return $output;
    }

    /**
     * Perform search in resources
     *
     * @param string $query The string being searched
     * @param array $output The existing results
     *
     * @return array The results
     */
    public function searchResources($query, array &$output)
    {
        //$output = array();
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
                'description' => $record->get('description'),
                'type' => 'Resources',
            );
        }

        return $output;
    }

    public function searchSnippets($query, array $output)
    {
        $c = $this->modx->newQuery('modSnippet');
        $c->where(array(
            'name:LIKE' => '%' . $query . '%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection('modSnippet', $c);
        /** @var modSnippet $record */
        foreach ($collection as $record) {
            $output[] = array(
                'name' => $record->get('name'),
                'action' => 'element/snippet/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => 'Snippets',
            );
        }

        return $output;
    }

    public function searchChunks($query, array $output)
    {
        $class = 'modChunk';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $query . '%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modChunk $record */
        foreach ($collection as $record) {
            $output[] = array(
                'name' => $record->get('name'),
                'action' => 'element/snippet/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => 'Chunks',
            );
        }

        return $output;
    }

    public function searchTemplates($query, array $output)
    {
        $class = 'modTemplate';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'templatename:LIKE' => '%' . $query . '%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $output[] = array(
                'name' => $record->get('templatename'),
                'action' => 'element/snippet/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => 'Templates',
            );
        }

        return $output;
    }

    public function searchTVs($query, array $output)
    {
        $class = 'modTemplateVar';
        $c = $this->modx->newQuery($class);
        $c->where(array(
            'name:LIKE' => '%' . $query . '%',
        ));

        $c->limit($this->maxResults);

        $collection = $this->modx->getCollection($class, $c);
        /** @var modTemplate $record */
        foreach ($collection as $record) {
            $output[] = array(
                'name' => $record->get('name'),
                'action' => 'element/tv/update&id=' . $record->get('id'),
                'description' => $record->get('description'),
                'type' => 'TVs',
            );
        }

        return $output;
    }
}

return 'modSearchProcessor';
