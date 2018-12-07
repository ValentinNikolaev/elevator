<?php declare(strict_types=1);

namespace Elevator\Helpers;


class queueHelper
{
    const DOT = '.';

    /**
     * @param $from
     * @param $to
     * @return string
     */
    public function preparePairItem($from, $to)
    {
        return $from . self::DOT . $to;
    }

    /**
     * @param string $item
     * @return string
     */
    public function getPairItemFromField(string $item)
    {
        $exploded = explode(self::DOT, $item);
        return $exploded[0];
    }

    /**
     * @param string $item
     * @return string
     */
    public function getPairItemToField(string $item)
    {
        $exploded = explode(self::DOT, $item);
        return $exploded[1];
    }

    /**
     * for up
     *
     * @param $compareValue
     * @return string
     */
    public function getMaxFloorFromPairs($compareValue)
    {
        $max = $compareValue;
        $pairs = queue()->list();
        foreach ($pairs as $pair) {
            $from = $this->getPairItemFromField($pair);
            $to = $this->getPairItemToField($pair);
            if ($from && $from > $compareValue) {
                $max = $from;
            }
            if ($from <= $to && $to > $max) {
                $max = $to;
            }
        }


        return $max;
    }

    /**
     * for down
     *
     * @param $compareValue
     * @return string
     */
    public function getMinFloorFromPairs($compareValue)
    {
        $min = $compareValue;
        $pairs = queue()->list();
        foreach ($pairs as $pair) {

            $from = $this->getPairItemFromField($pair);
            $to = $this->getPairItemToField($pair);
            if ($from && $from < $compareValue) {
                $min = $from;
            }
            if ($to && $from < $compareValue && $from <= $to && $to < $min) {
                $min = $to;
            }
        }
        return $min;
    }

    /**
     * for up
     *
     * @param $currentFloor
     * @return bool
     */
    public function isNeedToOpenDoorForUpStrategy($currentFloor)
    {
        $pairs = queue()->list();
        /**
         * for multiple items
         */
        $result = false;
        foreach ($pairs as $pair) {
            $from = $this->getPairItemFromField($pair);
            $to = $this->getPairItemToField($pair);
            if (!$from && $to == $currentFloor) {
                queue()->removeItem($pair);
                // from not exists only if we already open the doors
                $result = true;
            }

            if ($from == $currentFloor) {
                queue()->removeItem($pair);
                queue()->addItem(self::DOT . $to);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * for up
     *
     * @param $currentFloor
     * @return bool
     */
    public function isNeedToOpenDoorForDownStrategy($currentFloor)
    {
        $pairs = queue()->list();
        /**
         * for multiple items
         */
        $result = false;
        foreach ($pairs as $pair) {
            $from = $this->getPairItemFromField($pair);
            $to = $this->getPairItemToField($pair);
            if ($from == $currentFloor) {
                queue()->removeItem($pair);
                queue()->addItem(self::DOT . $to);
                // from not exists only if we already open the doors
                $result = true;
            }

            if (!$from && $to == $currentFloor) {
                queue()->removeItem($pair);

                $result = true;
            }
        }

        return $result;
    }
}