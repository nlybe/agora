<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

namespace Elgg\Agora;

use Elgg\Database\Seeds\Seed;

/**
 * Add agora seed
 *
 * @access private
 */
class Seeder extends Seed {

    /**
     * {@inheritdoc}
     */
    public function seed() {

        $count_agora = function () {
            return elgg_get_entities([
                'types' => 'object',
                'subtypes' => 'agora',
                'metadata_names' => '__faker',
                'count' => true,
            ]);
        };

        while ($count_agora() < $this->limit) {
            $metadata = [
                'address' => $this->faker()->url,
            ];

            $attributes = [
                'subtype' => 'agora',
            ];

            $new = $this->createObject($attributes, $metadata);

            if (!$new) {
                continue;
            }

            $this->createComments($new);
            $this->createLikes($new);

            elgg_create_river_item([
                'view' => 'river/object/agora/create',
                'action_type' => 'create',
                'subject_guid' => $new->owner_guid,
                'object_guid' => $new->guid,
                'target_guid' => $new->container_guid,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unseed() {

        $entities = elgg_get_entities([
            'types' => 'object',
            'subtypes' => 'agora',
            'metadata_names' => '__faker',
            'limit' => 0,
            'batch' => true,
        ]);

        /* @var $entities \ElggBatch */

        $entities->setIncrementOffset(false);

        foreach ($entities as $n) {
            if ($n->delete()) {
                $this->log("Deleted agora $n->guid");
            } else {
                $this->log("Failed to delete agora $n->guid");
            }
        }
    }

}
