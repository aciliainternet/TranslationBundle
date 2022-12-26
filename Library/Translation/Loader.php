<?php
namespace Acilia\Bundle\TranslationBundle\Library\Translation;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Exception;

class Loader
{
    const TTL = 6;

    protected $cacheDir;
    protected $doctrine;
    protected $logger;
    protected $debug;
    protected $cache;

    public function __construct($cacheDir, $doctrine, $logger, $debug = false, $cache = true)
    {
        $this->cacheDir = $cacheDir;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->debug = $debug;
        $this->cache = $cache;
    }

    public function load($resource = null, $culture = null, $version = 0)
    {
        $resourceName = ($resource === null) ? 'Global * Debug' : ($resource . '/' . $culture);
        $cacheFile = $this->cacheDir . '/translations/resource-' . ($resource === null ? 'global' : $resource) . '-' . $culture . '-v' . $version . '.php';

        if (! $this->cache || !file_exists($cacheFile) || ((filemtime($cacheFile) + (self::TTL * 3600)) < time())) {
            try {
                // Delete Symfony Catalogue
                $fs = new Filesystem();
                $finder = new Finder();
                $sfCacheFiles = $finder->in($this->cacheDir . '/translations/')->name('catalogue.' . $culture. '.*');
                $fs->remove($sfCacheFiles);
            } catch (Exception $e) {
            }

            $catalogue = new MessageCatalogue($culture);
            if ($resource ===  null) {
                $nodesSql = 'SELECT N.node_name, N.node_type, A.attrib_name, A.attrib_original, NULL AS value_translation FROM translation_node N INNER JOIN translation_attribute A ON N.node_id = A.attrib_node';
                $nodesStmt = $this->doctrine->getManager()->getConnection()->prepare($nodesSql);
                $nodesStmt = $nodesStmt->executeQuery();

            } else {
                $nodesSql = 'SELECT N.node_name, N.node_type, A.attrib_name, A.attrib_original, V.value_translation FROM translation_node N INNER JOIN translation_attribute A ON N.node_id=A.attrib_node LEFT JOIN translation_value V ON A.attrib_id = V.value_attribute AND (V.value_resource = :resource OR V.value_resource IS NULL)';
                $nodesStmt = $this->doctrine->getManager()->getConnection()->prepare($nodesSql);
                $nodesStmt = $nodesStmt->executeQuery(['resource' => $resource]);
            }

            $nodes = $nodesStmt->fetchAllAssociative();
            foreach ($nodes as $node) {
                $id = $node['node_name'] . '.' . $node['attrib_name'];
                $value = $node['value_translation'];
                $type = $node['node_type'];

                if ($value != null) {
                    $catalogue->set($id, $value, $type);
                } else {
                    if ($resource !=  null) {
                        if ($this->debug) {
                            $this->logger->warning('Translation for key "' . $id . '" and region "' . $resourceName . '" not found!');
                        }
                    }

                    if ($this->debug) {
                        $catalogue->set($id, '[--' . $node['attrib_original'] . '--]', $type);
                    } else {
                        $catalogue->set($id, $node['attrib_original'], $type);
                    }
                }
            }

            // Save Cache
            $cacheContent = '<?php ' . PHP_EOL
                          . 'use Symfony\Component\Translation\MessageCatalogue; ' . PHP_EOL
                          . '$catalogue = new MessageCatalogue(\'' . $culture . '\', ' . var_export($catalogue->all(), true) . '); ' . PHP_EOL
                          . 'return $catalogue;';

            if (!is_dir($this->cacheDir . '/translations')) {
                mkdir($this->cacheDir . '/translations');
            }
            file_put_contents($cacheFile, $cacheContent);
        } else {
            $catalogue = include $cacheFile;
        }

        return $catalogue;
    }
}
