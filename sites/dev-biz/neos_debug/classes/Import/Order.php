<?php

namespace Neos\classes\Import;

use DateTime;
use Neos\Import1C\Entities\Document as ImportDocument;
use RuntimeException;
use stdClass;

class Order extends AbstractImport
{

    /**
     * @inheritDoc
     */
    public function import($import)
    {
        if (is_null($import->order_status)) {
            throw new RuntimeException('Empty order status');
        }
        $context = $this->getContext($import);
        return $this->atomicImport(function () use ($import, $context) {
            $order = parent::import($import);
            $this->logger->debug(
                'Created history: ' . ($this->createOrderHistory($order, $import) ? 'yes' : 'no'),
                $context
            );
            return $order;
        });
    }

    /**
     * @param ImportDocument $import
     * @return stdClass
     */
    public function fetch($import)
    {
        $order = $this->db->getRow(
            'SELECT * FROM ?n WHERE order_id = ?i',
            $this->getTable('order'),
            $import->id
        );
        if (!$order) {
            return null;
        }
        return (object) $order;
    }

    /**
     * @param ImportDocument $import
     * @return stdClass
     */
    public function create($import)
    {
        throw new RuntimeException('Order create is not supported, order id: ' . $import->id);
    }

    /**
     * @param stdClass $order
     * @param ImportDocument $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $order, $import, &$updated)
    {
        $this->setOrderData($order, $import);
        $this->db->query(
            'UPDATE ?n SET `order_status_id` = ?i, `tracking_number` = ?s WHERE order_id = ?i',
            $this->getTable('order'),
            $order->order_status_id,
            $order->tracking_number,
            $order->order_id
        );
        $updated = $this->db->affectedRows() > 0;
        return $order;
    }

    /**
     * @param stdClass $order
     * @param ImportDocument $import
     * @return array
     */
    protected function setOrderData(stdClass $order, ImportDocument $import)
    {
        if (!is_null($import->order_status)) {
            $order->order_status_id = $import->order_status->id;
        }
        if (!empty($import->shipping_params->tracking_number)) {
            $order->tracking_number = $import->shipping_params->tracking_number;
        }
    }

    /**
     * @param stdClass $order
     * @param ImportDocument $import
     * @return boolean
     */
    public function createOrderHistory(stdClass $order, ImportDocument $import)
    {
        $exists = $this->db->getOne(
            'SELECT 1 FROM ?n WHERE order_id = ?i AND order_status_id = ?i',
            $this->getTable('order_history'),
            $order->order_id,
            $import->order_status->id
        );
        if ($exists) {
            return false;
        }
        $now = new DateTime();
        $this->db->query(
            'INSERT INTO ?n (order_id, order_status_id, comment, date_added) VALUES ?p',
            $this->getTable('order_history'),
            $this->db->parse(
                '(?i, ?i, ?s, ?s)',
                $order->order_id,
                $import->order_status->id,
                '',
                $now->format('Y-m-d H:i:s')
            )
        );
        return $this->db->affectedRows() > 0;
    }


  
    /**
     * @param stdClass $order
     * @param ImportDocument $import
     * @return integer
     */
    public function importOrderHistory(stdClass $order, ImportDocument $import)
    {
        $context = $this->getContext($import);
        $history = array_map(
            function ($item) {
                return (object) $item;
            },
            $this->db->getAll(
                'SELECT * FROM ?n WHERE order_id = ?i',
                $this->getTable('order_history'),
                $order->order_id
            )
        );
        $history_matched_ids = [];
        foreach ($import->history as $index => $new_item) {
            // If there is no order_status ignore new history item
            if (is_null($new_item->order_status)) {
                $this->logger->warning('No order status for history item, index: ' . $index, $context);
                unset($import->history[$index]);
                continue;
            }
            foreach ($history as $key => $old_item) {
                // Match new history item to old history item
                if ($new_item->order_status->id === $old_item->order_status_id) {
                    $new_item->id = $old_item->order_history_id;
                    $history_matched_ids[] = $new_item->id;
                    // Delete old item to prevent duplicate matching
                    unset($history[$key]);
                    break;
                }
            }
        }
        // Get items to delete
        $history_to_delete = array_filter($history, function ($item) use ($history_matched_ids) {
            return !in_array($item->order_history_id, $history_matched_ids);
        });
        if (count($history_to_delete) > 0) {
            $delete_ids = [];
            // Log
            foreach ($history_to_delete as $item) {
                $delete_ids[] = $item->order_history_id;
                $this->logger->notice(sprintf(
                    'Removing order history, id: %s, status_id: %s, comment: %s',
                    $item->order_history_id,
                    $item->order_status_id,
                    str_replace(["\r\n", "\n"], "", $item->comment)
                ), $context);
            }
            $this->db->query(
                'DELETE FROM ?n WHERE order_history_id IN (?a)',
                $this->getTable('order_history'),
                $delete_ids
            );
            $removed = $this->db->affectedRows();
            if ($removed > 0) {
                $this->logger->notice('Removed order history from database, count: ' . $removed, $context);
            }
        }
        $create = [];
        foreach ($import->history as $item) {
            if (is_null($item->id)) {
                $create[] = $item;
            } else {
                $this->db->query(
                    'UPDATE ?n SET comment = ?s, date_added = ?s WHERE order_history_id = ?i',
                    $this->getTable('order_history'),
                    $item->comment,
                    $item->date->format('Y-m-d H:i:s'),
                    $item->id
                );
                if ($this->db->affectedRows() > 0) {
                    $this->logger->debug('Order history did not change, id: ' . $item->id, $context);
                } else {
                    $this->logger->info('Update order history, id: ' . $item->id, $context);
                }
            }
        }
        if (count($create) > 0) {
            $this->db->query(
                'INSERT INTO ?n (order_id, order_status_id, comment, date_added) VALUES ?p',
                $this->getTable('order_history'),
                implode(',', array_map(
                    function ($item) use ($order) {
                        /** @var \Neos\Import1C\Entities\OrderHistoryItem $item */
                        return $this->db->parse(
                            '(?i, ?i, ?s, ?s)',
                            $order->order_id,
                            $item->order_status->id,
                            $item->comment,
                            $item->date->format('Y-m-d H:i:s')
                        );
                    },
                    $create
                ))
            );
            return $this->db->affectedRows();
        }
        return 0;
    }

    /**
     * @param ImportDocument $import
     * @return array
     */
    public function getContext($import)
    {
        return [
            'order_id' => (int) $import->id
        ];
    }
}
