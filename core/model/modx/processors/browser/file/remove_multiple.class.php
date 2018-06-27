<?php

class modBrowserFilesRemoveProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        $files = json_decode($this->getProperty('files'), true);
        if (empty($files)) {
            return $this->success();
        }

        $source = $this->getProperty('source', 1);
        foreach ($files as $file) {
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('browser/file/remove', [
                'file' => $file,
                'source' => $source,
            ]);
            if ($response && $response->isError()) {
                return $response->getResponse();
            }
        }

        return !empty($response)
            ? $response->getResponse()
            : $this->success();
    }

}

return 'modBrowserFilesRemoveProcessor';